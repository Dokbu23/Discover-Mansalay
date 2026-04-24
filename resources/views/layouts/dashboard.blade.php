<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#be185d">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>@yield('title', 'Dashboard') | Discover Mansalay</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/images/discover-mansalay-logo.jpg">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f0f4f3;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 260px;
            background: linear-gradient(180deg, #be185d 0%, #db2777 100%);
            color: white;
            padding: 0;
            z-index: 100;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-logo-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            background: transparent;
        }

        .sidebar-logo-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
            flex: 1;
        }

        .sidebar-logo {
            font-size: 1.1rem;
            font-weight: 700;
            line-height: 1.2;
            overflow-wrap: normal;
            word-break: normal;
        }

        .sidebar-logo span {
            color: #fff;
            display: block;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            opacity: 0.7;
            margin-top: 0.25rem;
        }

        .user-info {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 45px;
            height: 45px;
            background: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.25rem;
            color: #be185d;
        }

        .user-details h4 {
            font-size: 0.95rem;
            font-weight: 600;
        }

        .user-details p {
            font-size: 0.75rem;
            opacity: 0.7;
        }

        .sidebar-scrollable {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-scrollable::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar-scrollable::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }

        .sidebar-scrollable::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.2);
            border-radius: 3px;
        }

        .sidebar-scrollable::-webkit-scrollbar-thumb:hover {
            background: rgba(255,255,255,0.3);
        }

        .nav-menu {
            padding: 1rem 0;
        }

        .nav-section {
            padding: 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.5;
            margin-top: 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.15);
            border-left-color: #fff;
            color: white;
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .logout-btn {
            display: block;
            padding: 0.85rem 1.5rem;
            color: rgba(255,255,255,0.85);
            background: none;
            border: none;
            width: 100%;
            font-family: inherit;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            border-left: 3px solid transparent;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #be185d;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
        }

        /* Alert Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #fde7f3;
            color: #9d174d;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #be185d;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
        }

        .btn-primary {
            background: #be185d;
            color: white;
        }

        .btn-primary:hover {
            background: #db2777;
        }

        .btn-secondary {
            background: #f0f4f3;
            color: #be185d;
        }

        .btn-secondary:hover {
            background: #e0e7e5;
        }

        .btn-danger {
            background: #ff6b6b;
            color: white;
        }

        .btn-danger:hover {
            background: #e05555;
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .table th {
            background: #fff7fb;
            font-weight: 600;
            font-size: 0.85rem;
            color: #7a4d63;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            font-size: 0.9rem;
            color: #374151;
        }

        .table tr:hover {
            background: #fff7fb;
        }

        .table-actions {
            display: flex;
            gap: 0.5rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-active {
            background: #fde7f3;
            color: #9d174d;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #fde7f3;
            color: #9d174d;
        }

        /* Forms */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #be185d;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.16);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7c74'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1rem;
            padding-right: 3rem;
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-checkbox input {
            width: 18px;
            height: 18px;
            accent-color: #be185d;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .pagination a {
            background: white;
            color: #be185d;
            border: 1px solid #e5e7eb;
        }

        .pagination a:hover {
            background: #f0f4f3;
        }

        .pagination .active span {
            background: #be185d;
            color: white;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block !important;
            }
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
        }

        @media (max-width: 768px) {
            .top-header {
                padding: 0.75rem 1rem;
                flex-wrap: wrap;
                gap: 0.5rem;
            }

            .page-title {
                font-size: 1.25rem;
            }

            .content-area {
                padding: 1.25rem;
            }
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon svg {
            width: 26px;
            height: 26px;
            color: white;
        }

        .stat-icon.green { background: linear-gradient(135deg, #be185d 0%, #db2777 100%); }
        .stat-icon.gold { background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); }
        .stat-icon.blue { background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); }
        .stat-icon.purple { background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 100%); }

        .stat-info h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #be185d;
        }

        .stat-info p {
            font-size: 0.85rem;
            color: #7a4d63;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #7a4d63;
        }

        .empty-state svg {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* White + pink theme override */
        :root {
            --pink-50: #fff5fb;
            --pink-100: #ffe4f1;
            --pink-200: #ffc7df;
            --pink-400: #f472b6;
            --pink-500: #ec4899;
            --pink-600: #db2777;
            --pink-700: #be185d;
            --text-main: #3b1f2e;
            --text-soft: #7a4d63;
            --line-soft: #f4c5dc;
        }

        body {
            background: linear-gradient(180deg, #fff9fc 0%, #fff1f7 100%);
            color: var(--text-main);
        }

        .sidebar {
            background: linear-gradient(180deg, var(--pink-600) 0%, var(--pink-500) 100%);
        }

        .sidebar-logo span {
            color: #fff;
            background: transparent;
        }

        .user-avatar {
            color: var(--pink-700);
            background: #fff;
        }

        .nav-item.active {
            border-left-color: #fff;
            background: rgba(255, 255, 255, 0.2);
        }

        .main-content,
        .top-header,
        .card,
        .stat-card {
            background: #fff;
        }

        .page-title,
        .card-header h3,
        .stat-info h3 {
            color: var(--pink-700);
        }

        .btn-primary {
            background: var(--pink-600);
        }

        .btn-primary:hover {
            background: var(--pink-700);
        }

        .btn-secondary {
            background: var(--pink-100);
            color: var(--pink-700);
        }

        .btn-secondary:hover {
            background: var(--pink-200);
        }

        .table th {
            background: var(--pink-50);
            color: var(--text-soft);
        }

        .table tr:hover {
            background: #fff7fb;
        }

        .form-input:focus {
            border-color: var(--pink-500);
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.15);
        }

        .form-checkbox input {
            accent-color: var(--pink-600);
        }

        .pagination a {
            color: var(--pink-700);
            border-color: var(--line-soft);
        }

        .pagination a:hover {
            background: var(--pink-50);
        }

        .pagination .active span {
            background: var(--pink-600);
        }

        .stat-icon.green,
        .stat-icon.gold,
        .stat-icon.blue,
        .stat-icon.purple {
            background: linear-gradient(135deg, var(--pink-500) 0%, var(--pink-700) 100%);
        }

        .empty-state,
        .stat-info p,
        .table td,
        .form-label {
            color: var(--text-soft);
        }

        /* Vendor payment modal */
        .vendor-payment-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            backdrop-filter: blur(2px);
        }

        .vendor-payment-modal {
            width: min(560px, 92vw);
            background: #fff;
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: 0 30px 80px rgba(17, 24, 39, 0.3);
        }

        .vendor-payment-modal h2 {
            font-size: 1.35rem;
            margin-bottom: 0.5rem;
            color: var(--pink-700);
        }

        .vendor-payment-modal p {
            color: var(--text-soft);
            margin-bottom: 1rem;
        }

        .vendor-payment-details {
            background: var(--pink-50);
            border: 1px solid var(--line-soft);
            border-radius: 12px;
            padding: 1rem;
            display: grid;
            gap: 0.35rem;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .vendor-payment-note {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        body.vendor-payment-locked {
            overflow: hidden;
        }
    </style>
    <?php
    $pageStyles = trim($__env->yieldContent('styles'));
    if ($pageStyles !== '') {
        echo '<style>' . $pageStyles . '</style>';
    }
    ?>
</head>
<body data-payment-success="{{ session('payment_success') }}">
    @php
        $showVendorPaymentModal = Auth::check() && Auth::user()->isVendorRole() && !Auth::user()->hasVerifiedVendorPayment();
        $vendorPaymentFee = config('payments.vendor_approval_fee', 0);
        $gcashName = config('payments.gcash_name', '');
        $gcashNumber = config('payments.gcash_number', '');
        $vendorRoleLabel = Auth::check() && Auth::user()->isResortOwner() ? 'Resort Owner' : 'Vendor';
    @endphp

    @if($showVendorPaymentModal)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.body.classList.add('vendor-payment-locked');
            });
        </script>
    @endif
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/discover-mansalay-logo.jpg') }}" alt="Discover Mansalay" class="sidebar-logo-img" onerror="this.style.display='none'">
            <div class="sidebar-logo-text">
                <div class="sidebar-logo">Discover<span>Mansalay</span></div>
                <p class="sidebar-subtitle">Tourism Management Portal</p>
            </div>
        </div>

        <div class="sidebar-scrollable">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
            <div class="user-details">
                <h4>{{ Auth::user()->name }}</h4>
                <p>{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'tourist')) }}</p>
            </div>
        </div>

        <nav class="nav-menu">
            <p class="nav-section">Main Menu</p>
            
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="/" class="nav-item">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                </svg>
                <span>View Website</span>
            </a>

            @if(Auth::user()->isAdmin())
            <p class="nav-section">Admin Management</p>

            <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Users</span>
            </a>

            <a href="{{ route('heritage.index') }}" class="nav-item {{ request()->routeIs('heritage.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Heritage Sites</span>
            </a>

            <a href="{{ route('resorts.index') }}" class="nav-item {{ request()->routeIs('resorts.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Resorts</span>
            </a>

            <a href="{{ route('rooms.index') }}" class="nav-item {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Rooms</span>
            </a>

            <a href="{{ route('events.index') }}" class="nav-item {{ request()->routeIs('events.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Events</span>
            </a>

            <a href="{{ route('vendors.index') }}" class="nav-item {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Vendors</span>
            </a>

            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Products</span>
            </a>

            <a href="{{ route('bookings.index') }}" class="nav-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>Bookings</span>
            </a>
            @endif

            @if(Auth::user()->isResortOwner())
            <p class="nav-section">Resort Management</p>

            <a href="{{ route('resorts.index') }}" class="nav-item {{ request()->routeIs('resorts.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>My Resorts</span>
            </a>

            <a href="{{ route('rooms.index') }}" class="nav-item {{ request()->routeIs('rooms.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Rooms</span>
            </a>

            <a href="{{ route('bookings.index') }}" class="nav-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>Bookings</span>
            </a>

            <a href="{{ route('promotions.index') }}" class="nav-item {{ request()->routeIs('promotions.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span>Promotions</span>
            </a>
            @endif

            @if(Auth::user()->isEnterpriseOwner())
            <p class="nav-section">Business Management</p>

            <a href="{{ route('vendors.index') }}" class="nav-item {{ request()->routeIs('vendors.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>My Business</span>
            </a>

            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Products</span>
            </a>

            <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>Orders</span>
            </a>

            <a href="{{ route('inquiries.index') }}" class="nav-item {{ request()->routeIs('inquiries.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>Inquiries</span>
            </a>
            @endif

            @if(Auth::user()->isTourist())
            <p class="nav-section">My Travel</p>

            <a href="{{ route('bookings.index') }}" class="nav-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>My Bookings</span>
            </a>

            <a href="{{ route('products.index') }}" class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Shop</span>
            </a>

            <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>My Orders</span>
            </a>

            <a href="{{ route('inquiries.index') }}" class="nav-item {{ request()->routeIs('inquiries.*') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <span>My Inquiries</span>
            </a>
            @endif

            <p class="nav-section">Account</p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    Log Out
                </button>
            </form>
        </nav>
        </div><!-- End sidebar-scrollable -->
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <header class="top-header">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <button class="menu-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            </div>
            <div class="header-actions">
                @yield('header-actions')
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="list-style: none;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Support Bot Widget -->
    <div class="support-bot-widget">
        <!-- Floating Button -->
        <button class="support-bot-btn" onclick="toggleSupportBot()" id="supportBotBtn">
            <svg class="bot-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <svg class="close-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Chat Panel -->
        <div class="support-chat-panel" id="supportChatPanel">
            <div class="chat-header">
                <div class="chat-header-info">
                    <div class="bot-avatar">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4>Support Bot</h4>
                        <span class="status-online">Online</span>
                    </div>
                </div>
                <button class="chat-close-btn" onclick="toggleSupportBot()">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="chat-messages" id="chatMessages">
                <div class="bot-message">
                    <div class="message-bubble">
                        <p>Hello! I'm your support assistant. How can I help you today?</p>
                        <p style="margin-top: 0.5rem; font-size: 0.8rem; opacity: 0.8;">Fill out the form below to contact our admin team.</p>
                    </div>
                </div>
            </div>

            <div class="chat-form-container" id="chatFormContainer">
                <form id="supportForm" onsubmit="submitSupportRequest(event)">
                    @csrf
                    <div class="chat-form-group">
                        <label>Category</label>
                        <select name="category" id="supportCategory" required>
                            <option value="technical">Technical Issue</option>
                            <option value="booking">Booking Problem</option>
                            <option value="payment">Payment Issue</option>
                            <option value="account">Account Issue</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="chat-form-group">
                        <label>Priority</label>
                        <select name="priority" id="supportPriority" required>
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                    <div class="chat-form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" id="supportSubject" placeholder="Brief description of your issue" required>
                    </div>
                    <div class="chat-form-group">
                        <label>Message</label>
                        <textarea name="message" id="supportMessage" placeholder="Describe your problem in detail..." rows="3" required></textarea>
                    </div>
                    <button type="submit" class="chat-submit-btn" id="submitBtn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Send to Admin
                    </button>
                </form>
            </div>

            <div class="chat-success" id="chatSuccess" style="display: none;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h4>Request Submitted!</h4>
                <p id="successMessage">Your support ticket has been created. We'll get back to you soon.</p>
                <button onclick="resetSupportForm()" class="chat-new-btn">New Request</button>
            </div>
        </div>
    </div>

    @if($showVendorPaymentModal)
        <div class="vendor-payment-backdrop" role="dialog" aria-modal="true" aria-label="Vendor approval payment">
            <div class="vendor-payment-modal">
                <h2>{{ $vendorRoleLabel }} Approval Payment Required</h2>
                <p>Please pay the fee and upload your GCash receipt to continue.</p>
                <div class="vendor-payment-details">
                    <div><strong>Fee:</strong> Php {{ number_format($vendorPaymentFee, 2) }}</div>
                    <div><strong>GCash Name:</strong> {{ $gcashName }}</div>
                    <div><strong>GCash Number:</strong> {{ $gcashNumber }}</div>
                </div>

                @if(Auth::user()->hasVerifiedVendorPayment())
                    <span class="status-badge status-confirmed">Payment Verified</span>
                    <p class="vendor-payment-note">Please wait for admin approval to access the vendor dashboard.</p>
                    <form method="POST" action="{{ route('logout') }}" style="margin-top: 0.75rem;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">Log Out</button>
                    </form>
                @elseif(Auth::user()->hasSubmittedVendorPayment())
                    <span class="status-badge status-pending">Receipt Submitted</span>
                    <p class="vendor-payment-note">Admin will verify your payment. You will gain access once approved.</p>

                    <form action="{{ route('vendor.payment.submit') }}" method="POST" enctype="multipart/form-data" style="margin-top: 1rem; display: grid; gap: 0.5rem;">
                        @csrf
                        <input type="file" name="receipt" class="form-input" accept=".jpg,.jpeg,.png,.pdf" required>
                        <button type="submit" class="btn btn-primary">Re-upload Receipt</button>
                        <span class="vendor-payment-note">Accepted: JPG, PNG, PDF (max 5MB)</span>
                    </form>
                    <form method="POST" action="{{ route('logout') }}" style="margin-top: 0.75rem;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">Log Out</button>
                    </form>
                @else
                    <form action="{{ route('vendor.payment.submit') }}" method="POST" enctype="multipart/form-data" style="display: grid; gap: 0.5rem;">
                        @csrf
                        <input type="file" name="receipt" class="form-input" accept=".jpg,.jpeg,.png,.pdf" required>
                        <button type="submit" class="btn btn-primary">Upload Receipt</button>
                        <span class="vendor-payment-note">Accepted: JPG, PNG, PDF (max 5MB)</span>
                    </form>
                    <form method="POST" action="{{ route('logout') }}" style="margin-top: 0.75rem;">
                        @csrf
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">Log Out</button>
                    </form>
                @endif
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var paymentSuccess = document.body.dataset.paymentSuccess;

            if (paymentSuccess && typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Upload Successfully',
                    text: paymentSuccess,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#be185d'
                });
            }
        });
    </script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('/service-worker.js');
            });
        }
    </script>

    <style>
        /* Support Bot Widget Styles */
        .support-bot-widget {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }

        .support-bot-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(190, 24, 93, 0.32);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .support-bot-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(190, 24, 93, 0.4);
        }

        .support-bot-btn svg {
            width: 28px;
            height: 28px;
        }

        .support-bot-btn.active .bot-icon { display: none; }
        .support-bot-btn.active .close-icon { display: block !important; }

        .support-chat-panel {
            position: absolute;
            bottom: 75px;
            right: 0;
            width: 380px;
            max-height: 550px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.3s ease;
        }

        .support-chat-panel.active {
            display: flex;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-header {
            background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
            color: white;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-header-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .bot-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bot-avatar svg {
            width: 22px;
            height: 22px;
        }

        .chat-header h4 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }

        .status-online {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .status-online::before {
            content: '';
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #4ade80;
            border-radius: 50%;
            margin-right: 0.35rem;
        }

        .chat-close-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            padding: 0.25rem;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .chat-close-btn:hover {
            opacity: 1;
        }

        .chat-close-btn svg {
            width: 20px;
            height: 20px;
        }

        .chat-messages {
            padding: 1rem;
            max-height: 120px;
            overflow-y: auto;
            background: #fff7fb;
        }

        .bot-message .message-bubble {
            background: white;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border-top-left-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }

        .bot-message .message-bubble p {
            margin: 0;
            font-size: 0.9rem;
            color: #374151;
        }

        .chat-form-container {
            padding: 1rem;
            border-top: 1px solid #e5e7eb;
            max-height: 350px;
            overflow-y: auto;
        }

        .chat-form-group {
            margin-bottom: 0.75rem;
        }

        .chat-form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: #7a4d63;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chat-form-group input,
        .chat-form-group select,
        .chat-form-group textarea {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .chat-form-group input:focus,
        .chat-form-group select:focus,
        .chat-form-group textarea:focus {
            outline: none;
            border-color: #be185d;
            box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.16);
        }

        .chat-form-group textarea {
            resize: none;
        }

        .chat-submit-btn {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .chat-submit-btn:hover {
            background: linear-gradient(135deg, #db2777 0%, #228b6a 100%);
        }

        .chat-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .chat-submit-btn svg {
            width: 18px;
            height: 18px;
        }

        .chat-success {
            padding: 2rem;
            text-align: center;
        }

        .chat-success svg {
            width: 60px;
            height: 60px;
            color: #db2777;
            margin-bottom: 1rem;
        }

        .chat-success h4 {
            font-size: 1.1rem;
            color: #be185d;
            margin: 0 0 0.5rem 0;
        }

        .chat-success p {
            font-size: 0.875rem;
            color: #7a4d63;
            margin: 0 0 1rem 0;
        }

        .chat-new-btn {
            padding: 0.6rem 1.5rem;
            background: #f0f4f3;
            color: #be185d;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .chat-new-btn:hover {
            background: #e0e7e5;
        }

        @media (max-width: 480px) {
            .support-chat-panel {
                width: calc(100vw - 2rem);
                right: -1rem;
            }
        }
    </style>

    <script>
        function toggleSupportBot() {
            const btn = document.getElementById('supportBotBtn');
            const panel = document.getElementById('supportChatPanel');
            btn.classList.toggle('active');
            panel.classList.toggle('active');
        }

        function submitSupportRequest(event) {
            event.preventDefault();
            
            const form = document.getElementById('supportForm');
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(form);
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin" width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';
            
            fetch('{{ route("support.store") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('chatFormContainer').style.display = 'none';
                    document.getElementById('chatMessages').innerHTML += `
                        <div class="bot-message" style="margin-top: 0.5rem;">
                            <div class="message-bubble" style="background: #fde7f3; border-left: 3px solid #db2777;">
                                <p style="color: #9d174d;"><strong>Ticket #${data.ticket_id} Created!</strong></p>
                                <p style="color: #9d174d; font-size: 0.85rem;">${data.message}</p>
                            </div>
                        </div>
                    `;
                    document.getElementById('chatSuccess').style.display = 'block';
                    document.getElementById('successMessage').textContent = data.message;
                } else {
                    alert('Error: ' + (data.message || 'Failed to submit request'));
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Send to Admin';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Send to Admin';
            });
        }

        function resetSupportForm() {
            document.getElementById('supportForm').reset();
            document.getElementById('chatFormContainer').style.display = 'block';
            document.getElementById('chatSuccess').style.display = 'none';
            document.getElementById('submitBtn').disabled = false;
            document.getElementById('submitBtn').innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Send to Admin';
            document.getElementById('chatMessages').innerHTML = `
                <div class="bot-message">
                    <div class="message-bubble">
                        <p>Hello! I'm your support assistant. How can I help you today?</p>
                        <p style="margin-top: 0.5rem; font-size: 0.8rem; opacity: 0.8;">Fill out the form below to contact our admin team.</p>
                    </div>
                </div>
            `;
        }
    </script>

    @yield('scripts')
</body>
</html>

