<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Attractions | Discover Mansalay</title>
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
            max-width: 1400px;
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
            background: linear-gradient(135deg, #db2777 0%, #be185d 100%);
            color: white;
            padding: 4rem 1rem;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .hero p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 3rem 1rem;
        }

        .section {
            margin-bottom: 4rem;
        }

        .section-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .section-header h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .section-header p {
            color: #666;
            font-size: 1rem;
        }

        .attractions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .attraction-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
            cursor: pointer;
        }

        .attraction-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }

        .attraction-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #e0e0e0;
        }

        .attraction-card-content {
            padding: 1.5rem;
        }

        .attraction-card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .attraction-card-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: #666;
        }

        .attraction-card-description {
            color: #666;
            line-height: 1.5;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .attraction-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-price {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-free {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-featured {
            background: #fcd34d;
            color: #78350f;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 3rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
        }

        .pagination a:hover {
            background: #db2777;
            color: white;
            border-color: #db2777;
        }

        .pagination .active {
            background: #db2777;
            color: white;
            border-color: #db2777;
        }

        .auth-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }

        .auth-modal.open {
            display: flex;
        }

        .auth-modal-content {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .auth-modal-content h2 {
            margin-bottom: 1rem;
        }

        .auth-modal-content p {
            color: #666;
            margin-bottom: 1.5rem;
        }

        .auth-modal-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-primary {
            background: #db2777;
            color: white;
        }

        .btn-primary:hover {
            background: #be185d;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 1.8rem;
            }

            .attractions-grid {
                grid-template-columns: 1fr;
            }

            .navbar-nav {
                gap: 0.5rem;
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
                <a href="{{ route('attractions') }}" style="color: #db2777; font-weight: 600;">Attractions</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #333; cursor: pointer; font-weight: 500;">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Discover Attractions</h1>
        <p>Explore all heritage sites, resorts, and events in Mansalay</p>
    </div>

    <!-- Content -->
    <div class="container">
        <!-- Heritage Sites -->
        <div class="section">
            <div class="section-header">
                <h2>Heritage Sites</h2>
                <p>Explore our cultural treasures and historical landmarks</p>
            </div>
            <div class="attractions-grid">
                @forelse($heritageSites as $site)
                    @php
                        $imagePath = $site->image 
                            ? asset('storage/' . $site->image)
                            : (isset($galleryImages) && count($galleryImages) > 0 
                                ? asset($galleryImages[($site->id - 1) % count($galleryImages)])
                                : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400');
                    @endphp
                    <div class="attraction-card requires-auth" role="button" tabindex="0" data-href="{{ route('heritage.show', $site->id) }}">
                        <img src="{{ $imagePath }}" alt="{{ $site->name }}" class="attraction-card-image">
                        <div class="attraction-card-content">
                            <h3 class="attraction-card-title">{{ $site->name }}</h3>
                            <div class="attraction-card-meta">
                                <span>📍 {{ $site->location ?? 'Location TBA' }}</span>
                            </div>
                            <p class="attraction-card-description">{{ Str::limit($site->description, 80) }}</p>
                            <div class="attraction-card-footer">
                                @if($site->entrance_fee)
                                    <span class="badge badge-price">₱{{ number_format($site->entrance_fee, 2) }}</span>
                                @else
                                    <span class="badge badge-free">Free Entry</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="grid-column: 1/-1; text-align: center; color: #666;">No heritage sites available yet.</p>
                @endforelse
            </div>
            @if($heritageSites->hasPages())
                <div class="pagination">
                    {{ $heritageSites->links() }}
                </div>
            @endif
        </div>

        <!-- Resorts -->
        <div class="section">
            <div class="section-header">
                <h2>Resorts & Hotels</h2>
                <p>Find the perfect place to stay</p>
            </div>
            <div class="attractions-grid">
                @forelse($resorts as $resort)
                    @php
                        $imagePath = $resort->cover_image 
                            ? asset('storage/' . $resort->cover_image)
                            : (isset($galleryImages) && count($galleryImages) > 0 
                                ? asset($galleryImages[($resort->id + 1) % count($galleryImages)])
                                : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400');
                    @endphp
                    <div class="attraction-card requires-auth" role="button" tabindex="0" data-href="{{ route('resorts.show', $resort->id) }}">
                        <img src="{{ $imagePath }}" alt="{{ $resort->name }}" class="attraction-card-image">
                        <div class="attraction-card-content">
                            <h3 class="attraction-card-title">{{ $resort->name }}</h3>
                            <div class="attraction-card-meta">
                                <span>📍 {{ $resort->address ?? 'Address TBA' }}</span>
                                @if($resort->rooms->count() > 0)
                                    <span>🛏️ {{ $resort->rooms->count() }} rooms</span>
                                @endif
                            </div>
                            <p class="attraction-card-description">{{ Str::limit($resort->description, 80) }}</p>
                            <div class="attraction-card-footer">
                                @if($resort->rooms->count() > 0)
                                    <span class="badge badge-price">From ₱{{ number_format($resort->rooms->min('price_per_night'), 2) }}/night</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="grid-column: 1/-1; text-align: center; color: #666;">No resorts available yet.</p>
                @endforelse
            </div>
            @if($resorts->hasPages())
                <div class="pagination">
                    {{ $resorts->links() }}
                </div>
            @endif
        </div>

        <!-- Events -->
        <div class="section">
            <div class="section-header">
                <h2>Events & Festivals</h2>
                <p>Don't miss out on upcoming events</p>
            </div>
            <div class="attractions-grid">
                @forelse($events as $event)
                    @php
                        $imagePath = $event->image 
                            ? asset('storage/' . $event->image)
                            : (isset($galleryImages) && count($galleryImages) > 0 
                                ? asset($galleryImages[($event->id + 3) % count($galleryImages)])
                                : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400');
                    @endphp
                    <div class="attraction-card requires-auth" role="button" tabindex="0" data-href="{{ route('events.show', $event->id) }}">
                        <img src="{{ $imagePath }}" alt="{{ $event->name }}" class="attraction-card-image">
                        <div class="attraction-card-content">
                            <h3 class="attraction-card-title">{{ $event->name }}</h3>
                            <div class="attraction-card-meta">
                                <span>📅 {{ $event->start_date->format('M d, Y') }}</span>
                                <span>📍 {{ $event->location ?? 'Location TBA' }}</span>
                            </div>
                            <p class="attraction-card-description">{{ Str::limit($event->description, 80) }}</p>
                            <div class="attraction-card-footer">
                                @if($event->is_featured)
                                    <span class="badge badge-featured">Featured</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p style="grid-column: 1/-1; text-align: center; color: #666;">No events available yet.</p>
                @endforelse
            </div>
            @if($events->hasPages())
                <div class="pagination">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Auth Modal (for guests) -->
    <div class="auth-modal" id="authModal" aria-hidden="true">
        <div class="auth-modal-content">
            <h2>Login Required</h2>
            <p>Please log in to view attraction details and make bookings.</p>
            <div class="auth-modal-buttons">
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
            </div>
        </div>
    </div>

    <script>
        const isGuestUser = !{{ auth()->check() ? 'true' : 'false' }};
        const authModal = document.getElementById('authModal');

        function openAuthModal() {
            authModal.classList.add('open');
            authModal.setAttribute('aria-hidden', 'false');
        }

        function closeAuthModal() {
            authModal.classList.remove('open');
            authModal.setAttribute('aria-hidden', 'true');
        }

        if (authModal) {
            authModal.addEventListener('click', function(e) {
                if (e.target === authModal) {
                    closeAuthModal();
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAuthModal();
            }
        });

        // Handle card clicks
        document.querySelectorAll('.attraction-card[role="button"]').forEach(function(card) {
            card.addEventListener('click', function() {
                if (isGuestUser) {
                    openAuthModal();
                } else {
                    const href = this.getAttribute('data-href');
                    if (href) {
                        window.location.href = href;
                    }
                }
            });

            card.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    if (isGuestUser) {
                        openAuthModal();
                    } else {
                        const href = this.getAttribute('data-href');
                        if (href) {
                            window.location.href = href;
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
