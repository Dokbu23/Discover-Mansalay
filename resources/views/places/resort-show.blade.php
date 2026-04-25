<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $resort->name }} | Discover Mansalay</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            color: #333;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #db2777;
            text-decoration: none;
        }

        .navbar-nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .navbar-nav a {
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar-nav a:hover {
            color: #db2777;
        }

        .hero {
            position: relative;
            height: 400px;
            overflow: hidden;
            background: linear-gradient(135deg, #db2777 0%, #be185d 100%);
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0.7;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.8) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem 1rem 2rem;
            color: white;
        }

        .hero-overlay h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .hero-overlay p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 3rem 1rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #db2777;
        }

        .info-card h3 {
            color: #666;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }

        .info-card p {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }

        .section {
            background: white;
            padding: 2rem;
            margin-bottom: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .section h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .section p {
            line-height: 1.8;
            color: #666;
            margin-bottom: 1rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .detail-item dt {
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 0.3rem;
        }

        .detail-item dd {
            color: #333;
            margin-bottom: 1.5rem;
        }

        .amenities {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #333;
        }

        .amenity-item::before {
            content: '✓';
            color: #db2777;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 3rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: #db2777;
            color: white;
        }

        .btn-primary:hover {
            background: #be185d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(219, 39, 119, 0.3);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        @media (max-width: 768px) {
            .hero-overlay h1 {
                font-size: 1.8rem;
            }

            .container {
                padding: 2rem 1rem;
            }

            .actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-brand">Discover Mansalay</a>
            <div class="navbar-nav">
                <a href="{{ route('home') }}">Home</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #333; cursor: pointer; font-weight: 500; text-decoration: none;">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        @php
            $imagePath = $resort->cover_image 
                ? asset('storage/' . $resort->cover_image)
                : (isset($galleryImages) && count($galleryImages) > 0 
                    ? asset($galleryImages[($resort->id + 1) % count($galleryImages)])
                    : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200');
        @endphp
        <img src="{{ $imagePath }}" alt="{{ $resort->name }}">
        <div class="hero-overlay">
            <h1>{{ $resort->name }}</h1>
            <p>{{ $resort->address ?? 'Address not specified' }}</p>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <!-- Info Cards -->
        <div class="info-grid">
            <div class="info-card">
                <h3>Contact</h3>
                <p style="font-size: 1rem;">{{ $resort->contact_number ?? 'N/A' }}</p>
            </div>
            <div class="info-card">
                <h3>Status</h3>
                <p style="color: {{ $resort->is_active ? '#10b981' : '#ef4444' }};">
                    {{ $resort->is_active ? 'Open' : 'Closed' }}
                </p>
            </div>
        </div>

        <!-- Description -->
        @if($resort->description)
            <div class="section">
                <h2>About This Resort</h2>
                <p>{{ $resort->description }}</p>
            </div>
        @endif

        <!-- Amenities -->
        @if($resort->amenities && count($resort->amenities) > 0)
            <div class="section">
                <h2>Amenities</h2>
                <div class="amenities">
                    @foreach($resort->amenities as $amenity)
                        <div class="amenity-item">{{ $amenity }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Details -->
        <div class="section">
            <h2>Details</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <dt>Address</dt>
                    <dd>{{ $resort->address ?? 'Not specified' }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Email</dt>
                    <dd>{{ $resort->email ?? 'Not provided' }}</dd>
                </div>
                <div class="detail-item">
                    <dt>Last Updated</dt>
                    <dd>{{ $resort->updated_at->format('M d, Y') }}</dd>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions">
            <a href="{{ route('home') }}#resorts" class="btn btn-secondary">Back to Resorts</a>
            @auth
                <a href="javascript:void(0)" class="btn btn-primary" onclick="alert('Booking feature coming soon!')">Book a Room</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Login to Book</a>
            @endauth
        </div>
    </div>
</body>
</html>
