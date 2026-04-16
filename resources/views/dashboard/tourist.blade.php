@extends('layouts.dashboard')

@section('title', 'My Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
<!-- Welcome Banner -->
<div class="card" style="background: linear-gradient(135deg, #be185d 0%, #db2777 100%); color: white; margin-bottom: 2rem;">
    <div class="card-body" style="padding: 2rem;">
        <h2 style="margin: 0 0 0.5rem 0; font-size: 1.5rem;">Mabuhay, {{ Auth::user()->name }}!</h2>
        <p style="margin: 0; opacity: 0.9;">Ready to explore Mansalay? Check out resorts, events, and plan your trip.</p>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid" style="margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total_bookings'] }}</h3>
            <p>Total Bookings</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['pending_bookings'] }}</h3>
            <p>Pending</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['confirmed_bookings'] }}</h3>
            <p>Confirmed</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon gold">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['completed_trips'] }}</h3>
            <p>Completed Trips</p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Upcoming Bookings -->
    <div class="card">
        <div class="card-header">
            <h3>Upcoming Trips</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($upcomingBookings->count() > 0)
                @foreach($upcomingBookings as $booking)
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <div>
                            <h4 style="margin: 0; font-size: 1rem;">{{ $booking->room->resort->name ?? 'Resort' }}</h4>
                            <p style="margin: 0; font-size: 0.85rem; color: #6b7280;">{{ $booking->room->name ?? 'Room' }}</p>
                        </div>
                        <span class="status-badge status-{{ $booking->status }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    <div style="display: flex; gap: 1.5rem; font-size: 0.85rem; color: #6b7280;">
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}
                        </span>
                        <span>
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $booking->guests }} {{ Str::plural('guest', $booking->guests) }}
                        </span>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p>No upcoming trips</p>
                    <a href="/" class="btn btn-primary btn-sm" style="margin-top: 0.5rem;">Browse Resorts</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="card">
        <div class="card-header">
            <h3>Upcoming Events</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            @if($upcomingEvents->count() > 0)
                @foreach($upcomingEvents as $event)
                <div style="padding: 1rem; border-bottom: 1px solid #e5e7eb;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                        <h4 style="margin: 0; font-size: 1rem;">{{ $event->name }}</h4>
                        @if($event->is_featured)
                            <span class="status-badge status-confirmed" style="font-size: 0.65rem;">Featured</span>
                        @endif
                    </div>
                    <div style="font-size: 0.85rem; color: #6b7280;">
                        <p style="margin: 0 0 0.25rem 0;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                        </p>
                        <p style="margin: 0;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline; vertical-align: middle;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $event->location ?? 'TBA' }}
                        </p>
                    </div>
                </div>
                @endforeach
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

<!-- Featured Resorts -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h3>Featured Resorts</h3>
        <a href="/" class="btn btn-secondary btn-sm">View All</a>
    </div>
    <div class="card-body">
        @if($featuredResorts->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
                @foreach($featuredResorts as $resort)
                <div class="card" style="border: 1px solid #e5e7eb; box-shadow: none; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;"
                     onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
                     onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    @if($resort->cover_image)
                        <img src="{{ asset('storage/' . $resort->cover_image) }}" alt="{{ $resort->name }}" style="width: 100%; height: 140px; object-fit: cover; border-radius: 8px 8px 0 0;">
                    @else
                        <div style="width: 100%; height: 140px; background: linear-gradient(135deg, #be185d, #db2777); border-radius: 8px 8px 0 0; display: flex; align-items: center; justify-content: center;">
                            <svg width="40" height="40" fill="none" stroke="white" viewBox="0 0 24 24" style="opacity: 0.5;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    @endif
                    <div style="padding: 1rem;">
                        <h4 style="margin: 0 0 0.25rem 0; font-size: 1rem;">{{ $resort->name }}</h4>
                        <p style="font-size: 0.85rem; color: #6b7280; margin-bottom: 0.5rem;">{{ Str::limit($resort->description, 50) }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.85rem; color: #f59e0b;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($resort->rating))
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                                <span style="color: #6b7280; margin-left: 0.25rem;">{{ number_format($resort->rating, 1) }}</span>
                            </span>
                            <span style="font-size: 0.8rem; color: #6b7280;">{{ $resort->rooms_count ?? $resort->rooms->count() ?? 0 }} rooms</span>
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
                <p>No resorts available</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Links -->
<div class="card">
    <div class="card-header">
        <h3>Quick Links</h3>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem;">
            <a href="/" style="display: flex; flex-direction: column; align-items: center; padding: 1.5rem; background: #fff1f7; border-radius: 12px; text-decoration: none; color: #be185d; transition: background 0.2s;"
               onmouseover="this.style.background='#ffe3f1'" onmouseout="this.style.background='#fff1f7'">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span style="margin-top: 0.5rem; font-weight: 600;">Browse Resorts</span>
            </a>
            <a href="/#heritage" style="display: flex; flex-direction: column; align-items: center; padding: 1.5rem; background: #fef3c7; border-radius: 12px; text-decoration: none; color: #92400e; transition: background 0.2s;"
               onmouseover="this.style.background='#fde68a'" onmouseout="this.style.background='#fef3c7'">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                </svg>
                <span style="margin-top: 0.5rem; font-weight: 600;">Heritage Sites</span>
            </a>
            <a href="/#events" style="display: flex; flex-direction: column; align-items: center; padding: 1.5rem; background: #ede9fe; border-radius: 12px; text-decoration: none; color: #5b21b6; transition: background 0.2s;"
               onmouseover="this.style.background='#ddd6fe'" onmouseout="this.style.background='#ede9fe'">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span style="margin-top: 0.5rem; font-weight: 600;">Events</span>
            </a>
            <a href="/#pasalubong" style="display: flex; flex-direction: column; align-items: center; padding: 1.5rem; background: #fce7f3; border-radius: 12px; text-decoration: none; color: #9d174d; transition: background 0.2s;"
               onmouseover="this.style.background='#fbcfe8'" onmouseout="this.style.background='#fce7f3'">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span style="margin-top: 0.5rem; font-weight: 600;">Pasalubong</span>
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


