@extends('layouts.dashboard')

@section('title', 'Resorts')
@section('page-title', 'Resorts')

@section('header-actions')
<a href="{{ route('resorts.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add New
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($resorts->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resorts as $resort)
                    <tr>
                        <td><strong>{{ $resort->name }}</strong></td>
                        <td>{{ $resort->address ?? 'N/A' }}</td>
                        <td>{{ $resort->contact_number ?? 'N/A' }}</td>
                        <td>{{ $resort->rating ? $resort->rating . '/5' : 'N/A' }}</td>
                        <td>
                            <span class="status-badge {{ $resort->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $resort->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('resorts.edit', $resort) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('resorts.destroy', $resort) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
        <div style="padding: 1rem;">{{ $resorts->links() }}</div>
        @else
        <div class="empty-state">
            <p>No resorts yet</p>
            <a href="{{ route('resorts.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Add First Resort</a>
        </div>
        @endif
    </div>
</div>
@endsection