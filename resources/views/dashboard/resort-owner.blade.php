@extends('layouts.dashboard')

@section('title', 'Resort Owner Dashboard')
@section('page-title', 'My Resorts Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="card" style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; margin-bottom: 2rem;">
    <div class="card-body" style="padding: 2rem;">
        <h2 style="margin: 0 0 0.5rem 0; font-size: 1.5rem;">Welcome back, {{ Auth::user()->name }}!</h2>
        <p style="margin: 0; opacity: 0.9;">Manage your resorts, rooms, and bookings here.</p>
    </div>
</div>

@php
    $vendorPaymentFee = config('payments.vendor_approval_fee', 0);
    $gcashName = config('payments.gcash_name', '');
    $gcashNumber = config('payments.gcash_number', '');
@endphp

@if(!Auth::user()->is_approved)
<!-- Vendor Approval Fee -->
<div class="card" style="margin-bottom: 2rem; border-left: 4px solid #0ea5e9;">
    <div class="card-body">
        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; justify-content: space-between;">
            <div style="flex: 1; min-width: 260px;">
                <h3 style="margin: 0 0 0.5rem 0;">Vendor Approval Fee</h3>
                <p style="margin: 0 0 0.75rem 0; color: #6b7280;">Please pay the approval fee and upload your GCash receipt to proceed.</p>
                <div style="display: grid; gap: 0.35rem; font-size: 0.9rem;">
                    <div><strong>Fee:</strong> ₱{{ number_format($vendorPaymentFee, 2) }}</div>
                    <div><strong>GCash Name:</strong> {{ $gcashName }}</div>
                    <div><strong>GCash Number:</strong> {{ $gcashNumber }}</div>
                </div>
            </div>
            <div style="min-width: 260px;">
                @if(Auth::user()->hasVerifiedVendorPayment())
                    <span style="padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; background: #dcfce7; color: #15803d;">Payment Verified</span>
                    <p style="margin: 0.5rem 0 0 0; color: #6b7280; font-size: 0.85rem;">Wait for admin approval.</p>
                @elseif(Auth::user()->hasSubmittedVendorPayment())
                    <span style="padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; background: #dbeafe; color: #2563eb;">Receipt Submitted</span>
                    <p style="margin: 0.5rem 0 0 0; color: #6b7280; font-size: 0.85rem;">Admin will verify your payment.</p>
                @else
                    <form action="{{ route('vendor.payment.submit') }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 0.5rem;">
                        @csrf
                        <input type="file" name="receipt" class="form-input" accept=".jpg,.jpeg,.png,.pdf" required>
                        <button type="submit" class="btn btn-primary" style="background: #0ea5e9;">Done Payment</button>
                    </form>
                    <p style="margin: 0.5rem 0 0 0; color: #9ca3af; font-size: 0.8rem;">Accepted: JPG, PNG, PDF (max 5MB)</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['resorts'] }}</h3>
            <p>My Resorts</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['rooms'] }}</h3>
            <p>Total Rooms</p>
        </div>
    </div>

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
        <div class="stat-icon gold">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>₱{{ number_format($stats['total_revenue'], 0) }}</h3>
            <p>Total Revenue</p>
        </div>
    </div>
</div>

<!-- Second Stats Row -->
<div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon" style="background: #ffe3f1; color: #db2777;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['bookings_confirmed'] }}</h3>
            <p>Confirmed Bookings</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon purple">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['bookings_total'] }}</h3>
            <p>Total Bookings</p>
        </div>
    </div>
</div>

<!-- My Resorts -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h3>My Resorts</h3>
        <a href="{{ route('resorts.create') }}" class="btn btn-primary btn-sm">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Resort
        </a>
    </div>
    <div class="card-body">
        @if($resorts->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                @foreach($resorts as $resort)
                <div class="card" style="border: 1px solid #e5e7eb; box-shadow: none;">
                    @if($resort->cover_image)
                        <img src="{{ asset('storage/' . $resort->cover_image) }}" alt="{{ $resort->name }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px 8px 0 0;">
                    @else
                        <div style="width: 100%; height: 150px; background: linear-gradient(135deg, #be185d, #db2777); border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center;">
                            <svg width="48" height="48" fill="none" stroke="white" viewBox="0 0 24 24" style="opacity: 0.5;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    <div style="padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                            <h4 style="margin: 0; font-size: 1.1rem;">{{ $resort->name }}</h4>
                            @if($resort->is_active)
                                <span class="status-badge status-confirmed" style="font-size: 0.7rem;">Active</span>
                            @else
                                <span class="status-badge status-cancelled" style="font-size: 0.7rem;">Inactive</span>
                            @endif
                        </div>
                        <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.75rem;">{{ Str::limit($resort->description, 60) }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #6b7280; margin-bottom: 1rem;">
                            <span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                {{ $resort->rooms->count() }} Rooms
                            </span>
                            <span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; vertical-align: middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                {{ number_format($resort->rating, 1) }}
                            </span>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('resorts.edit', $resort) }}" class="btn btn-secondary btn-sm" style="flex: 1;">Edit</a>
                            <a href="{{ route('rooms.index', ['resort_id' => $resort->id]) }}" class="btn btn-primary btn-sm" style="flex: 1;">Manage Rooms</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p>You don't have any resorts yet</p>
                <a href="{{ route('resorts.create') }}" class="btn btn-primary" style="margin-top: 1rem;">Add Your First Resort</a>
            </div>
        @endif
    </div>
</div>

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
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $booking->user->name ?? 'N/A' }}</strong>
                                <div style="font-size: 0.75rem; color: #6b7280;">{{ $booking->user->email ?? '' }}</div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $booking->room->name ?? 'N/A' }}</strong>
                                <div style="font-size: 0.75rem; color: #6b7280;">{{ $booking->room->resort->name ?? '' }}</div>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('M d, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}</td>
                        <td style="font-weight: 600;">₱{{ number_format($booking->total_price, 2) }}</td>
                        <td>
                            <span class="status-badge status-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-secondary btn-sm">View</a>
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

@section('styles')
<style>
@media (max-width: 768px) {
    div[style*="grid-template-columns: repeat(auto-fill"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection
@endsection


