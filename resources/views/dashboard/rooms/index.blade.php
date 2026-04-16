@extends('layouts.dashboard')

@section('title', 'Rooms')
@section('page-title', 'Rooms')

@section('header-actions')
<a href="{{ route('rooms.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add New
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($rooms->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Resort</th>
                        <th>Type</th>
                        <th>Capacity</th>
                        <th>Price/Night</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td><strong>{{ $room->name }}</strong></td>
                        <td>{{ $room->resort->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($room->room_type) }}</td>
                        <td>{{ $room->capacity }} guests</td>
                        <td>₱{{ number_format($room->price_per_night, 2) }}</td>
                        <td>
                            <span class="status-badge {{ $room->is_available ? 'status-active' : 'status-inactive' }}">
                                {{ $room->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('rooms.edit', $room) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding: 1rem;">{{ $rooms->links() }}</div>
        @else
        <div class="empty-state">
            <p>No rooms yet</p>
            <a href="{{ route('rooms.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Add First Room</a>
        </div>
        @endif
    </div>
</div>
@endsection