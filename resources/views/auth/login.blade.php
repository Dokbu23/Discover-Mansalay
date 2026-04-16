<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Log In | Discover Mansalay</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #be185d 0%, #db2777 50%, #ec4899 100%);
            position: relative;
            overflow-x: hidden;
        }

        /* Left side - Image/Branding */
        .brand-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            color: white;
            position: relative;
            background: url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1200') center/cover;
        }

        .brand-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(190, 24, 93, 0.86) 0%, rgba(236, 72, 153, 0.72) 100%);
        }

        .brand-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 400px;
        }

        .brand-logo {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .brand-logo span {
            color: #fff;
        }

        .brand-tagline {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .brand-features {
            margin-top: 2.5rem;
            text-align: left;
        }

        .brand-feature {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
        }

        .brand-feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-feature-icon svg {
            width: 20px;
            height: 20px;
            fill: #fff;
        }

        /* Right side - Login Form */
        .form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #fff7fb;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #be185d;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #7a4d63;
            font-size: 0.95rem;
        }

        .alert-success {
            background: #fde7f3;
            color: #9d174d;
            padding: 0.875rem 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border-left: 4px solid #db2777;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            color: #5f2f46;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            fill: none;
            stroke: #9ca3af;
            stroke-width: 2;
        }

        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: all 0.2s ease;
            background: #fafafa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #db2777;
            background: white;
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.16);
        }

        .form-group input.is-invalid {
            border-color: #dc3545;
        }

        .error-text {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 0.4rem;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-size: 0.875rem;
            color: #4b5563;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #db2777;
            cursor: pointer;
        }

        .btn-submit {
            width: 100%;
            padding: 0.95rem;
            background: linear-gradient(135deg, #be185d 0%, #db2777 100%);
            color: white;
            font-size: 1rem;
            font-weight: 600;
            font-family: inherit;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(190, 24, 93, 0.26);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(190, 24, 93, 0.32);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.9rem;
            color: #7a4d63;
        }

        .login-footer a {
            color: #db2777;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }
            .brand-side {
                padding: 2rem;
                min-height: 300px;
            }
            .brand-features {
                display: none;
            }
            .form-side {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Brand Side -->
    <div class="brand-side">
        <div class="brand-content">
            <div class="brand-logo">Discover<span>Mansalay</span></div>
            <p class="brand-tagline">
                Explore the hidden paradise of Oriental Mindoro. Pristine beaches, 
                majestic mountains, and rich cultural heritage await you.
            </p>
            <div class="brand-features">
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </div>
                    <span>Discover breathtaking destinations</span>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 16H6c-.55 0-1-.45-1-1V6c0-.55.45-1 1-1h12c.55 0 1 .45 1 1v12c0 .55-.45 1-1 1zm-4.44-6.19l-2.35 3.02-1.56-1.88c-.2-.25-.58-.24-.78.01l-1.74 2.23c-.26.33-.02.81.39.81h8.98c.41 0 .65-.47.4-.8l-2.55-3.39c-.19-.26-.59-.26-.79 0z"/></svg>
                    </div>
                    <span>Plan your perfect getaway</span>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                    <span>Connect with local tourism</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="form-side">
        <div class="login-card">
            <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Sign in to access your tourism portal</p>
            </div>

            @if (session('status'))
                <div class="alert-success">{{ session('status') }}</div>
            @endif

            @if (session('success'))
                <div class="alert-success" style="background: #fde7f3; color: #9d174d; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; text-align: center;">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                            placeholder="you@example.com"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="••••••••"
                            required
                        >
                    </div>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="login-footer">
                Don't have an account? <a href="{{ route('register') }}">Create one</a>
            </div>
        </div>
    </div>
</body>
</html>


