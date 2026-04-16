<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\Resort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $promotions = Promotion::with('resort')->latest()->paginate(15);
        } else {
            // Resort owner sees their own promotions
            $resortIds = Resort::where('owner_id', $user->id)->pluck('id');
            $promotions = Promotion::whereIn('resort_id', $resortIds)->with('resort')->latest()->paginate(15);
        }

        return view('dashboard.promotions.index', compact('promotions'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $resorts = Resort::all();
        } else {
            $resorts = Resort::where('owner_id', $user->id)->get();
        }

        return view('dashboard.promotions.create', compact('resorts'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'resort_id' => 'required|exists:resorts,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'promo_code' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        // Authorization check for non-admin
        if (!$user->isAdmin()) {
            $resort = Resort::findOrFail($validated['resort_id']);
            if ($resort->owner_id !== $user->id) {
                abort(403);
            }
        }

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('promotions', 'public');
        }

        Promotion::create($validated);

        return redirect()->route('promotions.index')->with('success', 'Promotion created successfully!');
    }

    public function edit(Promotion $promotion)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            $resort = $promotion->resort;
            if ($resort->owner_id !== $user->id) {
                abort(403);
            }
        }

        if ($user->isAdmin()) {
            $resorts = Resort::all();
        } else {
            $resorts = Resort::where('owner_id', $user->id)->get();
        }

        return view('dashboard.promotions.edit', compact('promotion', 'resorts'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            if ($promotion->resort->owner_id !== $user->id) {
                abort(403);
            }
        }

        $validated = $request->validate([
            'resort_id' => 'required|exists:resorts,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'promo_code' => 'nullable|string|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            if ($promotion->image) {
                Storage::disk('public')->delete($promotion->image);
            }
            $validated['image'] = $request->file('image')->store('promotions', 'public');
        }

        $promotion->update($validated);

        return redirect()->route('promotions.index')->with('success', 'Promotion updated successfully!');
    }

    public function destroy(Promotion $promotion)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            if ($promotion->resort->owner_id !== $user->id) {
                abort(403);
            }
        }

        if ($promotion->image) {
            Storage::disk('public')->delete($promotion->image);
        }

        $promotion->delete();

        return redirect()->route('promotions.index')->with('success', 'Promotion deleted successfully!');
    }
}
