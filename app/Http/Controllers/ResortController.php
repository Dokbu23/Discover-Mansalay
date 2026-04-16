<?php

namespace App\Http\Controllers;

use App\Models\Resort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResortController extends Controller
{
    private function ensureCanManageResorts()
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user || (!$user->isAdmin() && !$user->isResortOwner())) {
            abort(403, 'Only admin and resort owners can manage resorts.');
        }
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $resorts = Resort::with('owner')->latest()->paginate(10);
        } else {
            $resorts = Resort::where('owner_id', $user->id)->latest()->paginate(10);
        }

        return view('dashboard.resorts.index', compact('resorts'));
    }

    public function create()
    {
        $this->ensureCanManageResorts();
        return view('dashboard.resorts.create');
    }

    public function store(Request $request)
    {
        $this->ensureCanManageResorts();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'amenities' => 'nullable|array',
            'cover_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['owner_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('resorts', 'public');
        }

        Resort::create($validated);

        return redirect()->route('resorts.index')->with('success', 'Resort created successfully!');
    }

    public function edit(Resort $resort)
    {
        $this->authorize('update', $resort);
        return view('dashboard.resorts.edit', compact('resort'));
    }

    public function update(Request $request, Resort $resort)
    {
        $this->authorize('update', $resort);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'amenities' => 'nullable|array',
            'cover_image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('cover_image')) {
            if ($resort->cover_image) {
                Storage::disk('public')->delete($resort->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('resorts', 'public');
        }

        $resort->update($validated);

        return redirect()->route('resorts.index')->with('success', 'Resort updated successfully!');
    }

    public function destroy(Resort $resort)
    {
        $this->authorize('delete', $resort);

        if ($resort->cover_image) {
            Storage::disk('public')->delete($resort->cover_image);
        }

        $resort->delete();

        return redirect()->route('resorts.index')->with('success', 'Resort deleted successfully!');
    }
}
