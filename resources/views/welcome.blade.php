<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Discover Mansalay - Tourism Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --pink-50: #fff5fb;
            --pink-100: #ffe8f3;
            --pink-200: #ffcfe5;
            --pink-500: #ec4899;
            --pink-600: #db2777;
            --pink-700: #be185d;
            --text-main: #3d1f2f;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            color: #1a202c;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ==================== INTRO SPLASH ANIMATION ==================== */
        .splash-screen {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: linear-gradient(135deg, var(--pink-600) 0%, var(--pink-500) 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .splash-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .splash-logo {
            font-size: 3rem;
            font-weight: 800;
            color: white;
            animation: logoPopIn 0.8s ease forwards;
        }

        .splash-logo span {
            color: #fff;
        }

        .splash-tagline {
            color: rgba(255,255,255,0.8);
            font-size: 1.1rem;
            margin-top: 0.5rem;
            opacity: 0;
            animation: fadeInUp 0.6s ease 0.4s forwards;
        }

        .splash-loader {
            margin-top: 2rem;
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255,255,255,0.2);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes logoPopIn {
            0% { transform: scale(0.5); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ==================== PAGE ANIMATIONS ==================== */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }

        .animate-delay-1 { transition-delay: 0.1s; }
        .animate-delay-2 { transition-delay: 0.2s; }
        .animate-delay-3 { transition-delay: 0.3s; }

        /* Hero animations */
        .hero-content h1 {
            opacity: 0;
            animation: heroFadeIn 1s ease 0.3s forwards;
        }

        .hero-content p {
            opacity: 0;
            animation: heroFadeIn 1s ease 0.5s forwards;
        }

        .hero-buttons {
            opacity: 0;
            animation: heroFadeIn 1s ease 0.7s forwards;
        }

        @keyframes heroFadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ==================== BUTTON ANIMATIONS ==================== */
        .btn-animated {
            position: relative;
            overflow: hidden;
            transform: scale(1);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .btn-animated:hover {
            transform: scale(1.05) translateY(-2px);
        }

        .btn-animated:active {
            transform: scale(0.95);
        }

        /* Ripple effect */
        .btn-animated::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, transparent 70%);
            transform: scale(0);
            opacity: 0;
            transition: none;
        }

        .btn-animated.ripple::after {
            animation: ripple 0.6s ease-out;
        }

        @keyframes ripple {
            0% { transform: scale(0); opacity: 1; }
            100% { transform: scale(4); opacity: 0; }
        }

        /* Pulse effect for primary buttons */
        .btn-pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(255, 215, 0, 0.4); }
            50% { box-shadow: 0 0 0 15px rgba(255, 215, 0, 0); }
        }

        /* Nav slide down */
        nav {
            animation: slideDown 0.5s ease forwards;
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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

        .logo span {
            color: var(--pink-500);
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

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: linear-gradient(135deg, #f9d2e6 0%, #f6b8d7 50%, #ee8ec0 100%);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920') center/cover;
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            color: white;
            padding: 2rem;
            max-width: 900px;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero h1 span {
            color: #fff;
        }

        .hero p {
            font-size: 1.25rem;
            opacity: 0.9;
            margin-bottom: 2.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: #fff;
            color: var(--pink-700);
            padding: 1rem 2.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary:hover {
            background: var(--pink-100);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(219,39,119,0.2);
        }

        .btn-secondary {
            background: rgba(255,255,255,0.15);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.25);
            border-color: rgba(255,255,255,0.5);
        }

        /* Features Section */
        .features {
            padding: 5rem 2rem;
            background: #fff7fb;
        }

        .features-container {
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

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--pink-500) 0%, var(--pink-700) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .feature-icon svg {
            width: 35px;
            height: 35px;
            fill: #fff;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--pink-700);
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: #7a4d63;
            font-size: 0.95rem;
        }

        /* Destinations Section */
        .destinations {
            padding: 5rem 2rem;
            background: white;
        }

        .destinations-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .destinations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
        }

        .destination-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            height: 400px;
            cursor: pointer;
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

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .gallery-item {
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(219, 39, 119, 0.12);
            background: #fff;
        }

        .gallery-item img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
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
        }

        .auth-modal-btn-primary {
            background: var(--pink-600);
            color: #fff;
        }

        .auth-modal-btn-primary:hover {
            background: var(--pink-700);
            transform: translateY(-1px);
        }

        .auth-modal-btn-secondary {
            background: var(--pink-100);
            color: var(--pink-700);
        }

        .auth-modal-btn-secondary:hover {
            background: var(--pink-200);
            transform: translateY(-1px);
        }

        .auth-modal-close {
            border: none;
            background: transparent;
            color: #7a4d63;
            font-size: 1rem;
            cursor: pointer;
            padding: 0.2rem 1.25rem 1.2rem;
            display: inline-block;
            margin-left: auto;
            margin-right: auto;
            text-decoration: underline;
            text-underline-offset: 3px;
            text-decoration-color: rgba(122, 77, 99, 0.5);
        }

        @media (max-width: 520px) {
            .auth-modal {
                padding: 0.75rem;
            }

            .auth-modal-header h3 {
                font-size: 1.6rem;
            }

            .auth-modal-actions {
                flex-direction: column;
            }

            .auth-modal-btn {
                width: 100%;
            }
        }

        .destination-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 60%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2rem;
            color: white;
        }

        .destination-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .destination-card p {
            opacity: 0.9;
            font-size: 0.95rem;
        }

        /* CTA Section */
        .cta {
            padding: 5rem 2rem;
            background: linear-gradient(135deg, var(--pink-600) 0%, var(--pink-500) 100%);
            text-align: center;
            color: white;
        }

        .cta-container {
            max-width: 700px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .cta p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* Footer */
        footer {
            background: #fff;
            border-top: 1px solid #ffd8ea;
            color: white;
            padding: 3rem 2rem;
            text-align: center;
        }

        .footer-logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-logo span {
            color: var(--pink-500);
        }

        footer p {
            opacity: 1;
            color: #7a4d63;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            nav {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }

            .hero h1 {
                font-size: 2.25rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .destinations-grid {
                grid-template-columns: 1fr;
            }

            .cta h2 {
                font-size: 1.75rem;
            }
        }
    </style>
</head>
<body data-authenticated="{{ auth()->check() ? '1' : '0' }}">
    <!-- Splash Screen -->
    <div class="splash-screen" id="splash">
        <div class="splash-logo">Discover<span>Mansalay</span></div>
        <p class="splash-tagline">Your Gateway to Oriental Mindoro</p>
        <div class="splash-loader"></div>
    </div>

    <!-- Navigation -->
    <nav>
        <div class="logo">Discover<span>Mansalay</span></div>
        <div class="nav-links">
            <a href="#home">Home</a>
            <a href="#heritage" class="requires-auth">Heritage Sites</a>
            <a href="#resorts" class="requires-auth">Resorts</a>
            <a href="#events" class="requires-auth">Events</a>
            <a href="#products" class="requires-auth">Pasalubong</a>
        </div>
        <div class="nav-buttons">
            @auth
            <a href="{{ route('dashboard') }}" class="btn-signup btn-animated">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="btn-login btn-animated">Log In</a>
            <a href="{{ route('register') }}" class="btn-signup btn-animated">Sign Up</a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Discover the Hidden Paradise of <span>Mansalay</span></h1>
            <p>
                Explore breathtaking beaches, majestic mountains, rich Mangyan culture, 
                and the Ammonite Capital of the Philippines. Your adventure starts here.
            </p>
            <div class="hero-buttons">
                <a href="#heritage" class="btn-primary btn-animated btn-pulse">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                    </svg>
                    Explore Now
                </a>
                @guest
                <a href="{{ route('login') }}" class="btn-secondary btn-animated">Log In</a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <div class="section-header">
                <span class="section-tag">Why Visit Mansalay</span>
                <h2 class="section-title">Experience Oriental Mindoro's Best</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card animate-on-scroll animate-delay-1">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </div>
                    <h3>Pristine Beaches</h3>
                    <p>Discover crystal-clear waters and white sand beaches perfect for swimming, snorkeling, and relaxation.</p>
                </div>
                <div class="feature-card animate-on-scroll animate-delay-2">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M14 6l-3.75 5 2.85 3.8-1.6 1.2C9.81 13.75 7 10 7 10l-6 8h22L14 6z"/></svg>
                    </div>
                    <h3>Mountain Adventures</h3>
                    <p>Trek through lush forests and climb majestic peaks with stunning panoramic views of the island.</p>
                </div>
                <div class="feature-card animate-on-scroll animate-delay-3">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                    <h3>Mangyan Culture</h3>
                    <p>Experience the rich heritage of indigenous Mangyan people, their art, traditions, and warm hospitality.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Heritage Sites Section -->
    @php
        $galleryCount = count($galleryImages ?? []);
    @endphp

    <section class="destinations" id="heritage">
        <div class="destinations-container">
            <div class="section-header">
                <span class="section-tag">Heritage Sites</span>
                <h2 class="section-title">Explore Our Cultural Treasures</h2>
            </div>
            <div class="destinations-grid">
                @forelse($heritageSites as $site)
                @php
                    $siteImagePath = $site->image
                        ? 'storage/' . $site->image
                        : ($galleryCount ? $galleryImages[$loop->index % $galleryCount] : null);

                    $siteTitle = $site->name;
                    if ($siteImagePath) {
                        $siteTitle = ucwords(str_replace(['-', '_'], ' ', pathinfo($siteImagePath, PATHINFO_FILENAME)));
                    }
                @endphp
                <div class="destination-card animate-on-scroll requires-auth" role="button" tabindex="0">
                    <img src="{{ $siteImagePath ? asset($siteImagePath) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800' }}" alt="{{ $siteTitle }}">
                    <div class="destination-overlay">
                        <h3>{{ $siteTitle }}</h3>
                        <p>{{ Str::limit($site->description, 100) }}</p>
                        @if($site->entrance_fee)
                        <span style="background: #fff; color: #be185d; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-top: 0.5rem; display: inline-block;">₱{{ number_format($site->entrance_fee, 2) }}</span>
                        @else
                        <span style="background: #db2777; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-top: 0.5rem; display: inline-block;">Free Entrance</span>
                        @endif
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #7a4d63;">
                    <p>No heritage sites available yet. Check back soon!</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Resorts Section -->
    <section class="features" id="resorts">
        <div class="features-container">
            <div class="section-header">
                <span class="section-tag">Accommodations</span>
                <h2 class="section-title">Where to Stay</h2>
            </div>
            <div class="features-grid">
                @forelse($resorts as $resort)
                @php
                    $resortImagePath = $resort->cover_image
                        ? 'storage/' . $resort->cover_image
                        : ($galleryCount ? $galleryImages[($loop->index + 2) % $galleryCount] : null);

                    $resortTitle = $resort->name;
                    if ($resortImagePath) {
                        $resortTitle = ucwords(str_replace(['-', '_'], ' ', pathinfo($resortImagePath, PATHINFO_FILENAME)));
                    }
                @endphp
                <div class="feature-card animate-on-scroll requires-auth" role="button" tabindex="0">
                    <img src="{{ $resortImagePath ? asset($resortImagePath) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800' }}" alt="{{ $resortTitle }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem;">
                    <h3>{{ $resortTitle }}</h3>
                    <p>{{ Str::limit($resort->description, 80) ?? 'Experience comfort and relaxation at this resort.' }}</p>
                    @if($resort->rooms->count() > 0)
                    <p style="margin-top: 0.5rem; color: #db2777; font-weight: 600;">From ₱{{ number_format($resort->rooms->min('price_per_night'), 2) }}/night</p>
                    @endif
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #7a4d63;">
                    <p>No resorts available yet. Check back soon!</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Events Section -->
    <section class="destinations" id="events">
        <div class="destinations-container">
            <div class="section-header">
                <span class="section-tag">Upcoming Events</span>
                <h2 class="section-title">Join the Festivities</h2>
            </div>
            <div class="features-grid">
                @forelse($events as $event)
                <div class="feature-card animate-on-scroll requires-auth" role="button" tabindex="0" style="text-align: left;">
                    @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem;">
                    @elseif($galleryCount)
                    <img src="{{ asset($galleryImages[($loop->index + 4) % $galleryCount]) }}" alt="{{ $event->name }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 12px; margin-bottom: 1rem;">
                    @endif
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="background: #be185d; color: white; padding: 0.75rem; border-radius: 10px; text-align: center; min-width: 60px;">
                            <span style="font-size: 1.5rem; font-weight: 700; display: block;">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</span>
                            <span style="font-size: 0.75rem; text-transform: uppercase;">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</span>
                        </div>
                        <div>
                            <h3 style="margin-bottom: 0.25rem;">{{ $event->name }}</h3>
                            <p style="font-size: 0.85rem; color: #7a4d63;">{{ $event->location ?? 'Location TBA' }}</p>
                            <p style="font-size: 0.9rem; margin-top: 0.5rem;">{{ Str::limit($event->description, 80) }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #7a4d63;">
                    <p>No upcoming events. Stay tuned for announcements!</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Products/Pasalubong Section -->
    <section class="features" id="products" style="background: white;">
        <div class="features-container">
            <div class="section-header">
                <span class="section-tag">Local Products</span>
                <h2 class="section-title">Pasalubong & Delicacies</h2>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem;">
                @forelse($products as $product)
                @php
                    $productImageUrl = null;

                    if (!empty($product->image)) {
                        $productImageUrl = \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://'])
                            ? $product->image
                            : asset('storage/' . $product->image);
                    }

                    if (!$productImageUrl) {
                        $fallbackProductImages = [
                            'dried-pusit' => 'https://images.unsplash.com/photo-1587735243615-c03f25aaff15?w=800',
                            'banana-chips' => 'https://images.unsplash.com/photo-1587132137056-bfbf0166836e?w=800',
                            'mangyan-woven-bag' => 'https://images.unsplash.com/photo-1544441893-675973e31985?w=800',
                            'nito-basket' => 'https://images.unsplash.com/photo-1596870230751-ebdfce98ec42?w=800',
                            'beaded-bracelet' => 'https://images.unsplash.com/photo-1617038220319-276d3cfab638?w=800',
                        ];

                        $productImageUrl = $fallbackProductImages[\Illuminate\Support\Str::slug($product->name)]
                            ?? ($galleryCount ? asset($galleryImages[($loop->index + 6) % $galleryCount]) : 'https://picsum.photos/seed/' . $product->id . '/300/200');
                    }
                @endphp
                <div class="feature-card animate-on-scroll requires-auth" role="button" tabindex="0" style="padding: 1rem;">
                    <img src="{{ $productImageUrl }}" alt="{{ $product->name }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px; margin-bottom: 1rem;">
                    <h4 style="font-size: 1rem; margin-bottom: 0.25rem;">{{ $product->name }}</h4>
                    <p style="font-size: 0.8rem; color: #7a4d63;">{{ $product->vendor->name ?? 'Local Vendor' }}</p>
                    <p style="font-size: 1.1rem; font-weight: 700; color: #be185d; margin-top: 0.5rem;">₱{{ number_format($product->price, 2) }}</p>
                </div>
                @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #7a4d63;">
                    <p>No products available yet. Check back soon!</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="contact">
        <div class="cta-container">
            <h2>Ready to Explore Mansalay?</h2>
            <p>Create an account to book resorts, get event updates, and discover local products.</p>
            <div class="cta-buttons">
                @auth
                <a href="{{ route('dashboard') }}" class="btn-primary btn-animated btn-pulse">Go to Dashboard</a>
                @else
                <a href="{{ route('register') }}" class="btn-primary btn-animated btn-pulse">Create Free Account</a>
                <a href="{{ route('login') }}" class="btn-secondary btn-animated">Log In</a>
                @endauth
            </div>
        </div>
    </section>

    @guest
    <div class="auth-modal" id="authModal" aria-hidden="true">
        <div class="auth-modal-card" role="dialog" aria-modal="true" aria-labelledby="authModalTitle">
            <div class="auth-modal-head">
                <div class="auth-modal-badge" aria-hidden="true">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </div>
                <button type="button" class="auth-modal-dismiss" id="authModalDismiss" aria-label="Close">x</button>
            </div>
            <div class="auth-modal-header">
                <h3 id="authModalTitle">Sign in to continue</h3>
                <p>Please log in or create an account to access products, pasalubong, and bookings.</p>
            </div>
            <div class="auth-modal-actions">
                <a href="{{ route('login') }}" class="auth-modal-btn auth-modal-btn-primary">Log In</a>
                <a href="{{ route('register') }}" class="auth-modal-btn auth-modal-btn-secondary">Sign Up</a>
            </div>
            <button type="button" class="auth-modal-close" id="authModalClose">Maybe later</button>
        </div>
    </div>
    @endguest

    <!-- Footer -->
    <footer>
        <div class="footer-logo">Discover<span>Mansalay</span></div>
        <p>&copy; {{ date('Y') }} Municipal Tourism Office of Mansalay, Oriental Mindoro. All rights reserved.</p>
    </footer>

    <script>
        // Hide splash screen after page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('splash').classList.add('hidden');
            }, 1500);
        });

        // Button click ripple effect
        document.querySelectorAll('.btn-animated').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                this.classList.remove('ripple');
                void this.offsetWidth; // Trigger reflow
                this.classList.add('ripple');
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(function(el) {
            observer.observe(el);
        });

        // Smooth scroll for nav links
        const isGuestUser = document.body.dataset.authenticated !== '1';
        const authModal = document.getElementById('authModal');
        const authModalClose = document.getElementById('authModalClose');
        const authModalDismiss = document.getElementById('authModalDismiss');

        function openAuthModal() {
            if (!authModal) {
                return;
            }

            authModal.classList.add('open');
            authModal.setAttribute('aria-hidden', 'false');
        }

        function closeAuthModal() {
            if (!authModal) {
                return;
            }

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

        if (authModalClose) {
            authModalClose.addEventListener('click', closeAuthModal);
        }

        if (authModalDismiss) {
            authModalDismiss.addEventListener('click', closeAuthModal);
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAuthModal();
            }
        });

        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                if (isGuestUser && this.classList.contains('requires-auth')) {
                    e.preventDefault();
                    openAuthModal();
                    return;
                }

                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        document.querySelectorAll('.requires-auth[role="button"]').forEach(function(card) {
            card.addEventListener('click', function() {
                if (isGuestUser) {
                    openAuthModal();
                }
            });

            card.addEventListener('keydown', function(e) {
                if (!isGuestUser) {
                    return;
                }

                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openAuthModal();
                }
            });
        });
    </script>
</body>
</html>


