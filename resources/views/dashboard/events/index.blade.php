@extends('layouts.dashboard')

@section('title', 'Events')
@section('page-title', 'Events')

@section('header-actions')
<a href="{{ route('events.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add New
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($events->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                    <tr>
                        <td><strong>{{ $event->name }}</strong></td>
                        <td>{{ $event->location ?? 'TBA' }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</td>
                        <td>{{ $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('M d, Y') : 'N/A' }}</td>
                        <td>
                            <span class="status-badge {{ $event->is_featured ? 'status-active' : 'status-inactive' }}">
                                {{ $event->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
        <div style="padding: 1rem;">{{ $events->links() }}</div>
        @else
        <div class="empty-state">
            <p>No events yet</p>
            <a href="{{ route('events.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Add First Event</a>
        </div>
        @endif
    </div>
</div>
@endsection