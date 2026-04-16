<?php

namespace App\Http\Controllers;

use App\Models\Resort;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $rooms = Room::with('resort')->latest()->paginate(10);
        } else {
            $resortIds = Resort::where('owner_id', $user->id)->pluck('id');
            $rooms = Room::whereIn('resort_id', $resortIds)->latest()->paginate(10);
        }

        return view('dashboard.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $resorts = Resort::all();
        } else {
            $resorts = Resort::where('owner_id', $user->id)->get();
        }

        return view('dashboard.rooms.create', compact('resorts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resort_id' => 'required|exists:resorts,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'room_type' => 'required|in:standard,deluxe,suite,villa',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $validated['images'] = [$request->file('image')->store('rooms', 'public')];
        }

        Room::create($validated);

        return redirect()->route('rooms.index')->with('success', 'Room created successfully!');
    }

    public function edit(Room $room)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $resorts = Resort::all();
        } else {
            $resorts = Resort::where('owner_id', $user->id)->get();
        }

        return view('dashboard.rooms.edit', compact('room', 'resorts'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'resort_id' => 'required|exists:resorts,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'room_type' => 'required|in:standard,deluxe,suite,villa',
            'capacity' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'amenities' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'is_available' => 'boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            // Delete old images
            if ($room->images) {
                foreach ($room->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
            $validated['images'] = [$request->file('image')->store('rooms', 'public')];
        }

        $room->update($validated);

        return redirect()->route('rooms.index')->with('success', 'Room updated successfully!');
    }

    public function destroy(Room $room)
    {
        if ($room->images) {
            foreach ($room->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully!');
    }
}
