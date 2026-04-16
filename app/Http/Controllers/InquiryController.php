<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Inquiry::with(['user', 'product', 'vendor']);

        if ($user->isAdmin()) {
            // Admin sees all inquiries
        } elseif ($user->isEnterpriseOwner()) {
            // Enterprise owner sees inquiries for their vendor
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id');
            $query->whereIn('vendor_id', $vendorIds);
        } else {
            // Tourist sees their own inquiries
            $query->where('user_id', $user->id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inquiries = $query->latest()->paginate(15);

        return view('dashboard.inquiries.index', compact('inquiries'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        $products = Product::where('is_approved', true)->with('vendor')->get();

        return view('dashboard.inquiries.create', compact('vendors', 'products'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'product_id' => 'nullable|exists:products,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Inquiry::create([
            'user_id' => $user->id,
            'vendor_id' => $validated['vendor_id'],
            'product_id' => $validated['product_id'] ?? null,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return redirect()->route('inquiries.index')->with('success', 'Inquiry sent successfully!');
    }

    public function show(Inquiry $inquiry)
    {
        $user = Auth::user();

        // Check authorization
        if (!$user->isAdmin()) {
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id')->toArray();
            if ($inquiry->user_id !== $user->id && !in_array($inquiry->vendor_id, $vendorIds)) {
                abort(403);
            }
        }

        $inquiry->load(['user', 'product', 'vendor']);

        return view('dashboard.inquiries.show', compact('inquiry'));
    }

    public function reply(Request $request, Inquiry $inquiry)
    {
        $user = Auth::user();

        // Only admin or vendor owner can reply
        if (!$user->isAdmin()) {
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id')->toArray();
            if (!in_array($inquiry->vendor_id, $vendorIds)) {
                abort(403);
            }
        }

        $validated = $request->validate([
            'reply' => 'required|string',
        ]);

        $inquiry->update([
            'reply' => $validated['reply'],
            'replied_at' => now(),
            'status' => 'replied',
        ]);

        return redirect()->back()->with('success', 'Reply sent successfully!');
    }

    public function close(Inquiry $inquiry)
    {
        $user = Auth::user();

        // Admin or the inquiry owner can close
        if (!$user->isAdmin() && $inquiry->user_id !== $user->id) {
            $vendorIds = Vendor::where('user_id', $user->id)->pluck('id')->toArray();
            if (!in_array($inquiry->vendor_id, $vendorIds)) {
                abort(403);
            }
        }

        $inquiry->update(['status' => 'closed']);

        return redirect()->back()->with('success', 'Inquiry closed!');
    }

    public function destroy(Inquiry $inquiry)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $inquiry->user_id !== $user->id) {
            abort(403);
        }

        $inquiry->delete();

        return redirect()->route('inquiries.index')->with('success', 'Inquiry deleted!');
    }
}
