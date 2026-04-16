@extends('layouts.dashboard')

@section('title', 'Vendors')
@section('page-title', 'Vendors')

@section('header-actions')
<a href="{{ route('vendors.create') }}" class="btn btn-primary">
    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add New
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-body" style="padding: 0;">
        @if($vendors->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                    <tr>
                        <td><strong>{{ $vendor->name }}</strong></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $vendor->type)) }}</td>
                        <td>{{ $vendor->address ?? 'N/A' }}</td>
                        <td>{{ $vendor->contact_number ?? 'N/A' }}</td>
                        <td>
                            <span class="status-badge {{ $vendor->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('vendors.edit', $vendor) }}" class="btn btn-secondary btn-sm">Edit</a>
                                <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" onsubmit="return confirm('Are you sure?')">
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
        <div style="padding: 1rem;">{{ $vendors->links() }}</div>
        @else
        <div class="empty-state">
            <p>No vendors yet</p>
            <a href="{{ route('vendors.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Add First Vendor</a>
        </div>
        @endif
    </div>
</div>
@endsection