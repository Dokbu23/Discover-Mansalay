<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Discover Mansalay') }} — @yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #fff7fb 0%, #ffeaf4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .auth-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(219,39,119,0.18);
            width: 100%;
            max-width: 440px;
            padding: 2.5rem;
            border: 1px solid #ffd3e6;
        }

        .auth-card h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #9d174d;
            margin-bottom: 0.25rem;
        }

        .auth-card p.subtitle {
            color: #718096;
            margin-bottom: 1.75rem;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            color: #4a5568;
            margin-bottom: 0.4rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-group input:focus {
            border-color: #ec4899;
            box-shadow: 0 0 0 3px rgba(236,72,153,0.16);
        }

        .form-group input.is-invalid {
            border-color: #e53e3e;
        }

        .error-text {
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        .form-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.875rem;
            color: #4a5568;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #db2777;
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
            font-family: inherit;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-primary:hover {
            opacity: 0.92;
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #718096;
        }

        .auth-footer a {
            color: #be185d;
            text-decoration: none;
            font-weight: 600;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .alert-success {
            background: #f0fff4;
            color: #276749;
            border: 1px solid #c6f6d5;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1.25rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        @yield('content')
    </div>
</body>
</html>
