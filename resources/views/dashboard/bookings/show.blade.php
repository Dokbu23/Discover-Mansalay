@extends('layouts.dashboard')

@section('title', 'Booking Details')
@section('page-title', 'Booking #' . $booking->id)

@section('content')
<div class="card">
    <div class="card-body">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
            <div>
                <h3 style="font-size: 1rem; color: #7a4d63; margin-bottom: 1rem;">Booking Information</h3>
                
                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Status</p>
                    <span class="status-badge status-{{ $booking->status }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Check-in Date</p>
                    <p style="font-weight: 600;">{{ \Carbon\Carbon::parse($booking->check_in)->format('F d, Y') }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Check-out Date</p>
                    <p style="font-weight: 600;">{{ \Carbon\Carbon::parse($booking->check_out)->format('F d, Y') }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Number of Guests</p>
                    <p style="font-weight: 600;">{{ $booking->guests }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Total Price</p>
                    <p style="font-weight: 700; font-size: 1.25rem; color: #be185d;">₱{{ number_format($booking->total_price, 2) }}</p>
                </div>

                @if($booking->special_requests)
                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Special Requests</p>
                    <p>{{ $booking->special_requests }}</p>
                </div>
                @endif
            </div>

            <div>
                <h3 style="font-size: 1rem; color: #7a4d63; margin-bottom: 1rem;">Guest & Room Details</h3>
                
                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Guest Name</p>
                    <p style="font-weight: 600;">{{ $booking->user->name ?? 'N/A' }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Guest Email</p>
                    <p>{{ $booking->user->email ?? 'N/A' }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Room</p>
                    <p style="font-weight: 600;">{{ $booking->room->name ?? 'N/A' }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Resort</p>
                    <p style="font-weight: 600;">{{ $booking->room->resort->name ?? 'N/A' }}</p>
                </div>

                <div style="margin-bottom: 1rem;">
                    <p style="font-size: 0.85rem; color: #7a4d63;">Room Type</p>
                    <p>{{ ucfirst($booking->room->room_type ?? 'N/A') }}</p>
                </div>
            </div>
        </div>

        <hr style="margin: 2rem 0; border: none; border-top: 1px solid #e5e7eb;">

        <div style="display: flex; gap: 1rem;">
            @if(Auth::user()->isAdmin())
                @if($booking->status === 'pending')
                <form action="{{ route('bookings.updateStatus', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="btn btn-primary">Confirm Booking</button>
                </form>
                <form action="{{ route('bookings.updateStatus', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn btn-danger">Cancel Booking</button>
                </form>
                @elseif($booking->status === 'confirmed')
                <form action="{{ route('bookings.updateStatus', $booking) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-primary">Mark as Completed</button>
                </form>
                @endif
            @endif
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Back to Bookings</a>
        </div>
    </div>
</div>
@endsection

