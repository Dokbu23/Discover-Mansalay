<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $bookings = Booking::with(['user', 'room.resort'])->latest()->paginate(10);
        } else {
            $bookings = Booking::where('user_id', $user->id)
                ->with(['room.resort'])
                ->latest()
                ->paginate(10);
        }

        return view('dashboard.bookings.index', compact('bookings'));
    }

    public function create()
    {
        $rooms = Room::with('resort')->where('is_available', true)->get();
        return view('dashboard.bookings.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        
        // Calculate total price
        $checkIn = \Carbon\Carbon::parse($validated['check_in']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out']);
        $nights = $checkIn->diffInDays($checkOut);
        $validated['total_price'] = $room->price_per_night * $nights;
        
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        Booking::create($validated);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    public function show(Booking $booking)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && $booking->user_id !== $user->id) {
            abort(403);
        }

        $booking->load(['user', 'room.resort']);
        return view('dashboard.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $booking->update($validated);

        return redirect()->back()->with('success', 'Booking status updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin() && $booking->user_id !== $user->id) {
            abort(403);
        }

        if ($booking->status === 'confirmed') {
            return redirect()->back()->with('error', 'Cannot delete confirmed booking.');
        }

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking cancelled successfully!');
    }
}
