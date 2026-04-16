@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
@php
    $pendingApprovals = \App\Models\User::where('is_approved', false)->count();
@endphp

@if($pendingApprovals > 0)
<!-- Pending Approvals Alert -->
<div class="card" style="background: #fef3c7; border-left: 4px solid #f59e0b; margin-bottom: 1.5rem;">
    <div class="card-body" style="padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <svg width="24" height="24" fill="none" stroke="#92400e" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <strong style="color: #92400e;">{{ $pendingApprovals }} user(s) waiting for approval</strong>
                <p style="margin: 0; font-size: 0.85rem; color: #78350f;">Review and approve or reject their registrations.</p>
            </div>
        </div>
        <a href="{{ route('users.index', ['approval' => 'pending']) }}" class="btn btn-primary" style="background: #f59e0b; border-color: #f59e0b;">
            Review Now
        </a>
    </div>
</div>
@endif

<!-- Welcome Banner -->
<div class="card" style="background: linear-gradient(135deg, #be185d 0%, #db2777 100%); color: white; margin-bottom: 2rem;">
    <div class="card-body" style="padding: 2rem;">
        <h2 style="margin: 0 0 0.5rem 0; font-size: 1.5rem;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="margin: 0; opacity: 0.9;">Here's an overview of Discover Mansalay platform.</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['users'] }}</h3>
            <p>Total Users</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['resorts'] }}</h3>
            <p>Active Resorts</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon gold">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['products'] }}</h3>
            <p>Products</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['bookings_total'] }}</h3>
            <p>Total Bookings</p>
        </div>
    </div>
</div>

<!-- Second Stats Row -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['bookings_pending'] }}</h3>
            <p>Pending Bookings</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #ffe3f1; color: #db2777;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['heritage_sites'] }}</h3>
            <p>Heritage Sites</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['events'] }}</h3>
            <p>Upcoming Events</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #e0e7ff; color: #4f46e5;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['vendors'] }}</h3>
            <p>Vendors</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <!-- Recent Bookings -->
    <div class="card">
        <div class="card-header">
            <h3>Recent Bookings</h3>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($recentBookings->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentBookings as $booking)
                        <tr>
                            <td>{{ $booking->user->name ?? 'N/A' }}</td>
                            <td>{{ $booking->room->name ?? 'N/A' }}</td>
                            <td>
                                <span class="status-badge status-{{ $booking->status }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p>No bookings yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="card">
        <div class="card-header">
            <h3>Upcoming Events</h3>
            <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($upcomingEvents->count() > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Date</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($upcomingEvents as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</td>
                            <td>{{ $event->location ?? 'TBA' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p>No upcoming events</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3>Quick Actions</h3>
    </div>
    <div class="card-body">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            <a href="{{ route('heritage.create') }}" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Heritage Site
            </a>
            <a href="{{ route('resorts.create') }}" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Resort
            </a>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Event
            </a>
            <a href="{{ route('vendors.create') }}" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Vendor
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Product
            </a>
        </div>
    </div>
</div>

@include('dashboard.partials.image-gallery')

@section('styles')
<style>
@media (max-width: 900px) {
    div[style*="grid-template-columns: 1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
@endsection


