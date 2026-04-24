@extends('layouts.dashboard')

@section('title', 'View User')
@section('page-title', 'User Details')

@section('content')
<style>
    .user-profile {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: linear-gradient(135deg, #be185d, #db2777);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        font-weight: bold;
    }
    .user-info h2 {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }
    .user-meta {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }
    .user-meta span {
        color: #7a4d63;
        font-size: 0.9rem;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    .detail-card {
        background: #f9fafb;
        padding: 1.5rem;
        border-radius: 10px;
    }
    .detail-card h4 {
        font-size: 0.85rem;
        color: #7a4d63;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .detail-card p {
        font-size: 1.1rem;
        color: #1f2937;
    }
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }
    .related-section {
        margin-top: 2rem;
    }
    .related-section h3 {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        color: #374151;
    }
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #9ca3af;
    }
</style>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h3>User Profile</h3>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit User</a>
            @if($user->id !== Auth::id())
            @php
                $toggleMessage = 'Are you sure you want to ' . ($user->is_active ? 'deactivate' : 'activate') . ' this user?';
            @endphp
            <form method="POST" action="{{ route('users.destroy', $user) }}" data-confirm="{{ $toggleMessage }}" onsubmit="return confirm(this.dataset.confirm);">
                @csrf
                @method('DELETE')
                @if($user->is_active)
                    <button type="submit" class="btn btn-danger">Deactivate</button>
                @else
                    <button type="submit" class="btn btn-primary">Activate</button>
                @endif
            </form>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="user-profile">
            <div class="user-avatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="user-info">
                <h2>
                    {{ $user->name }}
                    @if($user->is_active)
                        <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #ffe3f1; color: #db2777; margin-left: 0.5rem;">Active</span>
                    @else
                        <span style="padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 500; background: #fee2e2; color: #dc2626; margin-left: 0.5rem;">Inactive</span>
                    @endif
                </h2>
                <div class="user-meta">
                    <span>📧 {{ $user->email }}</span>
                    <span>🏷️ 
                        @if($user->role == 'admin')
                            <span class="badge badge-danger">Admin</span>
                        @elseif($user->role == 'resort_owner')
                            <span class="badge badge-primary">Resort Owner</span>
                        @elseif($user->role == 'enterprise_owner')
                            <span class="badge badge-warning">Enterprise Owner</span>
                        @else
                            <span class="badge badge-secondary">Tourist</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-grid">
            <div class="detail-card">
                <h4>User ID</h4>
                <p>#{{ $user->id }}</p>
            </div>
            <div class="detail-card">
                <h4>Account Status</h4>
                <p>
                    @if($user->is_active)
                        ✅ Active
                    @else
                        ❌ Inactive
                    @endif
                </p>
            </div>
            <div class="detail-card">
                <h4>Account Created</h4>
                <p>{{ $user->created_at->format('F d, Y') }}</p>
                <small style="color: #9ca3af;">{{ $user->created_at->diffForHumans() }}</small>
            </div>
            <div class="detail-card">
                <h4>Last Updated</h4>
                <p>{{ $user->updated_at->format('F d, Y') }}</p>
                <small style="color: #9ca3af;">{{ $user->updated_at->diffForHumans() }}</small>
            </div>
            <div class="detail-card">
                <h4>Email Verified</h4>
                <p>
                    @if($user->email_verified_at)
                        ✅ Verified on {{ $user->email_verified_at->format('M d, Y') }}
                    @else
                        ❌ Not Verified
                    @endif
                </p>
            </div>
        </div>

        @if($user->role == 'resort_owner' && $user->resorts->count() > 0)
        <div class="related-section">
            <h3>🏨 Owned Resorts ({{ $user->resorts->count() }})</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Resort Name</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->resorts as $resort)
                    <tr>
                        <td>{{ $resort->name }}</td>
                        <td>{{ $resort->location }}</td>
                        <td>
                            <a href="{{ route('resorts.show', $resort) }}" class="btn btn-view" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        @if($user->role == 'enterprise_owner' && $user->vendor)
        <div class="related-section">
            <h3>🏪 Vendor Shop</h3>
            <div class="detail-grid">
                <div class="detail-card">
                    <h4>Shop Name</h4>
                    <p>{{ $user->vendor->name }}</p>
                </div>
                <div class="detail-card">
                    <h4>Location</h4>
                    <p>{{ $user->vendor->location }}</p>
                </div>
                <div class="detail-card">
                    <h4>Products</h4>
                    <p>{{ $user->vendor->products->count() }} products</p>
                </div>
            </div>
            <div style="margin-top: 1rem;">
                <a href="{{ route('vendors.edit', $user->vendor) }}" class="btn btn-primary">View Vendor Details</a>
            </div>
        </div>
        @endif

        @if($user->bookings && $user->bookings->count() > 0)
        <div class="related-section">
            <h3>📅 Bookings ({{ $user->bookings->count() }})</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->bookings->take(5) as $booking)
                    <tr>
                        <td>#{{ $booking->id }}</td>
                        <td>{{ $booking->room->type ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                        <td>
                            @if($booking->status == 'confirmed')
                                <span class="badge badge-success">Confirmed</span>
                            @elseif($booking->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($booking->status == 'cancelled')
                                <span class="badge badge-danger">Cancelled</span>
                            @else
                                <span class="badge badge-secondary">{{ $booking->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if($user->bookings->count() > 5)
                <p style="margin-top: 1rem; color: #7a4d63; font-size: 0.9rem;">Showing 5 of {{ $user->bookings->count() }} bookings</p>
            @endif
        </div>
        @endif

        <div class="action-buttons">
            <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back to Users</a>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit User</a>
        </div>
    </div>
</div>
@endsection


