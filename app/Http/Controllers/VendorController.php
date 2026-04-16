<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $vendors = Vendor::with('user')->latest()->paginate(10);
        } else {
            $vendors = Vendor::where('user_id', $user->id)->latest()->paginate(10);
        }

        return view('dashboard.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('dashboard.vendors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'type' => 'required|in:awati,pasalubong_center,other',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('vendors', 'public');
        }

        Vendor::create($validated);

        return redirect()->route('vendors.index')->with('success', 'Vendor created successfully!');
    }

    public function edit(Vendor $vendor)
    {
        $this->authorize('update', $vendor);
        return view('dashboard.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $this->authorize('update', $vendor);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'type' => 'required|in:awati,pasalubong_center,other',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('logo')) {
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }
            $validated['logo'] = $request->file('logo')->store('vendors', 'public');
        }

        $vendor->update($validated);

        return redirect()->route('vendors.index')->with('success', 'Vendor updated successfully!');
    }

    public function destroy(Vendor $vendor)
    {
        $this->authorize('delete', $vendor);

        if ($vendor->logo) {
            Storage::disk('public')->delete($vendor->logo);
        }

        $vendor->delete();

        return redirect()->route('vendors.index')->with('success', 'Vendor deleted successfully!');
    }
}
