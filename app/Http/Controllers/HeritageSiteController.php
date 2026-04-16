<?php

namespace App\Http\Controllers;

use App\Models\HeritageSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeritageSiteController extends Controller
{
    private function ensureCanManageHeritageSites()
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Only admin can manage heritage sites.');
        }
    }

    public function index()
    {
        $sites = HeritageSite::latest()->paginate(10);
        return view('dashboard.heritage.index', compact('sites'));
    }

    public function create()
    {
        $this->ensureCanManageHeritageSites();
        return view('dashboard.heritage.create');
    }

    public function store(Request $request)
    {
        $this->ensureCanManageHeritageSites();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'entrance_fee' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('heritage', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        HeritageSite::create($validated);

        return redirect()->route('heritage.index')->with('success', 'Heritage site created successfully!');
    }

    public function edit(HeritageSite $heritage)
    {
        $this->ensureCanManageHeritageSites();
        return view('dashboard.heritage.edit', compact('heritage'));
    }

    public function update(Request $request, HeritageSite $heritage)
    {
        $this->ensureCanManageHeritageSites();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'entrance_fee' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($heritage->image) {
                Storage::disk('public')->delete($heritage->image);
            }
            $validated['image'] = $request->file('image')->store('heritage', 'public');
        }

        $validated['is_active'] = $request->has('is_active');

        $heritage->update($validated);

        return redirect()->route('heritage.index')->with('success', 'Heritage site updated successfully!');
    }

    public function destroy(HeritageSite $heritage)
    {
        $this->ensureCanManageHeritageSites();

        if ($heritage->image) {
            Storage::disk('public')->delete($heritage->image);
        }

        $heritage->delete();

        return redirect()->route('heritage.index')->with('success', 'Heritage site deleted successfully!');
    }
}
