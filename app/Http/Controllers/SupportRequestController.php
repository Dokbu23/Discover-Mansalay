<?php

namespace App\Http\Controllers;

use App\Models\SupportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of support requests (Admin only)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $query = SupportRequest::with('user')->latest();
            
            // Filter by status
            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }
            
            // Filter by priority
            if ($request->has('priority') && $request->priority !== 'all') {
                $query->where('priority', $request->priority);
            }
            
            $supportRequests = $query->paginate(15);
            
            return view('support.index', compact('supportRequests'));
        }
        
        // For regular users, show their own requests
        $supportRequests = SupportRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);
            
        return view('support.my-requests', compact('supportRequests'));
    }

    /**
     * Store a new support request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'category' => 'required|in:technical,booking,payment,account,other',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $supportRequest = SupportRequest::create([
            'user_id' => Auth::id(),
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'category' => $validated['category'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your support request has been submitted. We will get back to you soon!',
                'ticket_id' => $supportRequest->id,
            ]);
        }

        return redirect()->back()->with('success', 'Support request submitted successfully! Ticket #' . $supportRequest->id);
    }

    /**
     * Show a specific support request
     */
    public function show(SupportRequest $supportRequest)
    {
        $user = Auth::user();
        
        // Only admin or the owner can view
        if (!$user->isAdmin() && $supportRequest->user_id !== $user->id) {
            abort(403);
        }
        
        return view('support.show', compact('supportRequest'));
    }

    /**
     * Update support request (Admin response)
     */
    public function update(Request $request, SupportRequest $supportRequest)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'admin_response' => 'nullable|string|max:2000',
        ]);

        $supportRequest->status = $validated['status'];
        
        if (!empty($validated['admin_response'])) {
            $supportRequest->admin_response = $validated['admin_response'];
        }
        
        if ($validated['status'] === 'resolved') {
            $supportRequest->resolved_at = now();
        }
        
        $supportRequest->save();

        return redirect()->back()->with('success', 'Support request updated successfully!');
    }

    /**
     * Get open ticket count for notification badge
     */
    public function getOpenCount()
    {
        $count = SupportRequest::where('status', 'open')->count();
        
        return response()->json(['count' => $count]);
    }
}
