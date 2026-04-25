<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Resorts & Hotels | Discover Mansalay</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --pink-50: #fff5fb;
            --pink-100: #ffe8f3;
            --pink-200: #ffcfe5;
            --pink-500: #ec4899;
            --pink-600: #db2777;
            --pink-700: #be185d;
            --text-main: #3d1f2f;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            color: #1a202c;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 100;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(219,39,119,0.12);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--pink-700);
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: #7a4d63;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: var(--pink-600);
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .btn-login {
            color: var(--pink-700);
            text-decoration: none;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-login:hover {
            background: var(--pink-100);
        }

        .btn-signup {
            background: var(--pink-600);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-signup:hover {
            background: var(--pink-700);
            transform: translateY(-2px);
        }

        /* Padding for fixed nav */
        .container {
            padding-top: 100px;
        }

        .destinations {
            padding: 5rem 2rem;
            background: white;
        }

        .destinations-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-tag {
            color: var(--pink-600);
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--pink-700);
            margin-top: 0.5rem;
        }

        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .destination-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            height: 400px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(219, 39, 119, 0.12);
            transition: all 0.3s;
        }

        .destination-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(219, 39, 119, 0.2);
        }

        .destination-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .destination-card:hover img {
            transform: scale(1.1);
        }

        .destination-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(61, 14, 38, 0.8), rgba(61, 14, 38, 0.2));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2rem;
            color: white;
        }

        .destination-title {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .destination-info {
            display: flex;
            gap: 1.5rem;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .destination-price {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.75rem;
            margin-top: 3rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.65rem 0.95rem;
            border: 1.5px solid #e0e0e0;
            border-radius: 8px;
            text-decoration: none;
            color: #7a4d63;
            font-weight: 500;
            transition: all 0.2s;
        }

        .pagination a:hover {
            background: var(--pink-600);
            color: white;
            border-color: var(--pink-600);
        }

        .pagination .active {
            background: var(--pink-600);
            color: white;
            border-color: var(--pink-600);
        }

        .requires-auth {
            cursor: pointer;
        }

        .auth-modal {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            background: rgba(39, 10, 26, 0.55);
        }

        .auth-modal.open {
            display: flex;
        }

        .auth-modal-card {
            width: min(460px, 100%);
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 22px 70px rgba(61, 14, 38, 0.3);
            overflow: hidden;
            border: 1px solid rgba(219, 39, 119, 0.12);
        }

        .auth-modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 1rem 1rem 0;
        }

        .auth-modal-badge {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--pink-600) 0%, var(--pink-500) 100%);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 20px rgba(219, 39, 119, 0.3);
        }

        .auth-modal-dismiss {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            background: #fff5fb;
            color: #9a5a79;
            font-size: 1.2rem;
            cursor: pointer;
            line-height: 1;
            transition: all 0.2s ease;
        }

        .auth-modal-dismiss:hover {
            background: #ffe8f3;
            color: #7a304f;
        }

        .auth-modal-header {
            padding: 0.75rem 1.25rem 0.5rem;
        }

        .auth-modal-header h3 {
            color: var(--pink-700);
            font-size: 1.9rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
            line-height: 1.15;
        }

        .auth-modal-header p {
            color: #7a4d63;
            font-size: 1rem;
            line-height: 1.55;
        }

        .auth-modal-actions {
            display: flex;
            gap: 0.85rem;
            padding: 1.15rem 1.25rem 0.6rem;
        }

        .auth-modal-btn {
            flex: 1;
            min-width: 120px;
            text-align: center;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1.05rem;
            padding: 0.82rem 1rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .auth-modal-btn-primary {
            background: var(--pink-600);
            color: #fff;
        }

        .auth-modal-btn-primary:hover {
            background: var(--pink-700);
        }

        .auth-modal-btn-secondary {
            background: #fff5fb;
            color: var(--pink-700);
            border: 1.5px solid #ffe8f3;
        }

        .auth-modal-btn-secondary:hover {
            background: #ffe8f3;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #7a4d63;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .empty-state-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--pink-700);
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .nav-links {
                gap: 1rem;
                display: none;
            }

            .destinations-grid {
                grid-template-columns: 1fr;
            }

            .destination-card {
                height: 300px;
            }

            .section-title {
                font-size: 2rem;
            }

            .container {
                padding-top: 80px;
            }
        }
    </style>
</head>
<body data-is-guest="{{ auth()->check() ? '0' : '1' }}">
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <a href="{{ route('home') }}" style="text-decoration: none; color: inherit;">Discover<span style="color: var(--pink-500);">Mansalay</span></a>
        </div>
        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('resorts.public') }}" style="color: var(--pink-600); font-weight: 600;">Resorts</a>
            <a href="{{ route('heritage.public') }}">Heritage Sites</a>
            <a href="{{ route('events.public') }}">Events</a>
        </div>
        <div class="nav-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-signup">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-login">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login">Log In</a>
                <a href="{{ route('register') }}" class="btn-signup">Sign Up</a>
            @endauth
        </div>
    </nav>

    <!-- Content -->
    <section class="destinations">
        <div class="destinations-container">
            <!-- Section Header -->
            <div class="section-header">
                <span class="section-tag">Resorts & Hotels</span>
                <h2 class="section-title">Find Your Perfect Stay</h2>
            </div>

            <!-- Resorts Grid -->
            <div class="destinations-grid">
                @forelse($resorts as $resort)
                    @php
                        $imagePath = $resort->cover_image 
                            ? asset('storage/' . $resort->cover_image)
                            : (isset($galleryImages) && count($galleryImages) > 0 
                                ? asset($galleryImages[($resort->id + 1) % count($galleryImages)])
                                : 'https://images.unsplash.com/photo-1564501049351-005e2ab6874d?w=800');
                        $minPrice = $resort->rooms->count() > 0 ? $resort->rooms->min('price_per_night') : null;
                    @endphp
                    <div class="destination-card requires-auth" role="button" tabindex="0" data-href="{{ route('resorts.show', $resort->id) }}">
                        <img src="{{ $imagePath }}" alt="{{ $resort->name }}" />
                        <div class="destination-overlay">
                            <div class="destination-title">{{ $resort->name }}</div>
                            <div class="destination-info">
                                <span>📍 {{ $resort->address ?? 'Address TBA' }}</span>
                                @if($resort->rooms->count() > 0)
                                    <span>🛏️ {{ $resort->rooms->count() }} rooms</span>
                                @endif
                                @if($minPrice)
                                    <span class="destination-price">From ₱{{ number_format($minPrice, 2) }}/night</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="grid-column: 1/-1;">
                        <div class="empty-state-icon">🏨</div>
                        <div class="empty-state-title">No Resorts Available</div>
                        <p>Check back soon for great accommodations!</p>
                    </div>
                @endforelse
            </div>

            @if($resorts->hasPages())
                <div class="pagination">
                    {{ $resorts->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Auth Modal -->
    <div class="auth-modal" id="authModal">
        <div class="auth-modal-card">
            <div class="auth-modal-head">
                <div class="auth-modal-badge">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <button class="auth-modal-dismiss" onclick="closeAuthModal()">&times;</button>
            </div>
            <div class="auth-modal-header">
                <h3>Login Required</h3>
                <p>Sign in to explore resorts and make your reservation today.</p>
            </div>
            <div class="auth-modal-actions">
                <a href="{{ route('login') }}" class="auth-modal-btn auth-modal-btn-primary">Log In</a>
                <a href="{{ route('register') }}" class="auth-modal-btn auth-modal-btn-secondary">Sign Up</a>
            </div>
        </div>
    </div>

    <script>
        const isGuestUser = document.body.dataset.isGuest === '1';
        const authModal = document.getElementById('authModal');

        function openAuthModal() {
            authModal.classList.add('open');
        }

        function closeAuthModal() {
            authModal.classList.remove('open');
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
        document.querySelectorAll('.destination-card[role="button"]').forEach(function(card) {
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
