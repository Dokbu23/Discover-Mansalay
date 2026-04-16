@extends('layouts.dashboard')

@section('title', 'New Booking')
@section('page-title', 'New Booking')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Room *</label>
                <select name="room_id" class="form-input form-select" required>
                    <option value="">Select Room</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                        {{ $room->name }} - {{ $room->resort->name }} (₱{{ number_format($room->price_per_night, 2) }}/night)
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Check-in Date *</label>
                    <input type="date" name="check_in" class="form-input" value="{{ old('check_in') }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Check-out Date *</label>
                    <input type="date" name="check_out" class="form-input" value="{{ old('check_out') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Number of Guests *</label>
                <input type="number" name="guests" class="form-input" value="{{ old('guests', 1) }}" min="1" required>
            </div>

            <div class="form-group">
                <label class="form-label">Special Requests</label>
                <textarea name="special_requests" class="form-input form-textarea" placeholder="Any special requests or notes...">{{ old('special_requests') }}</textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Create Booking</button>
                <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection