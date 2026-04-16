@extends('layouts.dashboard')

@section('title', 'Heritage Sites')
@section('page-title', 'Heritage Sites')

@section('header-actions')
<a href="{{ route('heritage.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add New
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($sites->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Entrance Fee</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sites as $site)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                @if($site->image)
                                <img src="{{ asset('storage/' . $site->image) }}" alt="{{ $site->name }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 8px;">
                                @else
                                <div style="width: 45px; height: 45px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <svg width="20" height="20" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                                <strong>{{ $site->name }}</strong>
                            </div>
                        </td>
                        <td>{{ $site->location ?? 'N/A' }}</td>
                        <td>{{ $site->entrance_fee ? '₱' . number_format($site->entrance_fee, 2) : 'Free' }}</td>
                        <td>
                            <span class="status-badge {{ $site->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $site->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('heritage.edit', $site) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('heritage.destroy', $site) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this?')">
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
        
        <div style="padding: 1rem;">
            {{ $sites->links() }}
        </div>
        @else
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            </svg>
            <p>No heritage sites yet</p>
            <a href="{{ route('heritage.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Add First Heritage Site</a>
        </div>
        @endif
    </div>
</div>
@endsection