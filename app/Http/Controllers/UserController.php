<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403);
        }

        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Approval filter
        if ($request->filled('approval')) {
            if ($request->approval === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->approval === 'approved') {
                $query->where('is_approved', true);
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403);
        }

        return view('dashboard.users.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,resort_owner,enterprise_owner,tourist',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;
        $validated['is_approved'] = true;
        $validated['approved_at'] = now();

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function show(User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isAdmin()) {
            abort(403);
        }

        $user->load(['resorts', 'vendor', 'bookings.room.resort']);
        
        return view('dashboard.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isAdmin()) {
            abort(403);
        }

        return view('dashboard.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,resort_owner,enterprise_owner,tourist',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isAdmin()) {
            abort(403);
        }

        // Prevent toggling your own status
        if ($user->id === $authUser->id) {
            return redirect()->route('users.index')->with('error', 'You cannot change your own account status!');
        }

        // Toggle is_active status
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('users.index')->with('success', "User {$status} successfully!");
    }

    public function approve(User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isAdmin()) {
            abort(403);
        }

        $user->is_approved = true;
        $user->approved_at = now();
        $user->save();

        return redirect()->back()->with('success', "User '{$user->name}' has been approved!");
    }

    public function reject(User $user)
    {
        $authUser = Auth::user();
        
        if (!$authUser->isAdmin()) {
            abort(403);
        }

        // Delete the user since they were rejected
        $user->delete();

        return redirect()->back()->with('success', "User registration has been rejected and removed.");
    }
}
