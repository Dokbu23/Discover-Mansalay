@extends('layouts.dashboard')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('header-actions')
<a href="{{ route('bookings.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    New Booking
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($bookings->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest</th>
                        <th>Room</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->room->name ?? 'N/A' }} <small style="color: #7a4d63;">({{ $booking->room->resort->name ?? '' }})</small></td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                        <td>₱{{ number_format($booking->total_price, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary btn-sm">View</a>
                                @if(Auth::user()->isAdmin() && $booking->status === 'pending')
                                <form action="{{ route('bookings.updateStatus', $booking) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-primary btn-sm">Confirm</button>
                                </form>
                                @endif
                                @if($booking->status !== 'confirmed')
                                <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Cancel</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem;">{{ $bookings->links() }}</div>
        @else
        <div class="empty-state">
            <p>No bookings yet</p>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Create First Booking</a>
        </div>
        @endif
    </div>
</div>
@endsection
