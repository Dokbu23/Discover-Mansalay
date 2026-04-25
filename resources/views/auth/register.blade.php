<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register | Discover Mansalay</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            background: url('/images/mansalay-cover-alt.png') center/cover no-repeat fixed;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: none;
            pointer-events: none;
            z-index: 0;
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
            background: linear-gradient(135deg, rgba(190, 24, 93, 0.7) 0%, rgba(236, 72, 153, 0.65) 100%);
            z-index: 1;
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
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .brand-logo span {
            color: #fff;
        }

        .brand-tagline {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
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
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
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

        /* Right side - Register Form */
        .form-side {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            position: relative;
            z-index: 1;
        }

        .register-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            border: 2px solid #db2777;
        }

        .register-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .register-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #be185d;
            margin-bottom: 0.5rem;
        }

        .register-header p {
            color: #7a4d63;
            font-size: 0.95rem;
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

        .form-group input {
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
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(190, 24, 93, 0.32);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .register-footer {
            text-align: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.9rem;
            color: #7a4d63;
        }

        .register-footer a {
            color: #db2777;
            text-decoration: none;
            font-weight: 600;
        }

        .register-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }
            .brand-side {
                padding: 2rem;
                min-height: 250px;
            }
            .brand-features {
                display: none;
            }
            .form-side {
                padding: 1.5rem;
            }
            .register-card {
                padding: 2rem;
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
                Join our community and start exploring the wonders of Mansalay. 
                Create your account to plan trips and connect with local tourism.
            </p>
            <div class="brand-features">
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                    </div>
                    <span>Access exclusive destinations</span>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-1 16H6c-.55 0-1-.45-1-1V6c0-.55.45-1 1-1h12c.55 0 1 .45 1 1v12c0 .55-.45 1-1 1zm-4.44-6.19l-2.35 3.02-1.56-1.88c-.2-.25-.58-.24-.78.01l-1.74 2.23c-.26.33-.02.81.39.81h8.98c.41 0 .65-.47.4-.8l-2.55-3.39c-.19-.26-.59-.26-.79 0z"/></svg>
                    </div>
                    <span>Save your favorite spots</span>
                </div>
                <div class="brand-feature">
                    <div class="brand-feature-icon">
                        <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    </div>
                    <span>Get personalized recommendations</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Side -->
    <div class="form-side">
        <div class="register-card">
            <div class="register-header">
                <h1>Create Account</h1>
                <p>Join Discover Mansalay today</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Full Name</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                            placeholder="Juan dela Cruz"
                            required
                            autofocus
                        >
                    </div>
                    @error('name')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

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
                            placeholder="Min. 8 characters"
                            required
                        >
                    </div>
                    @error('password')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 12l2 2 4-4"/>
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Re-enter your password"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="role">Register As</label>
                    <div class="input-wrapper">
                        <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <path d="M20 8v6M23 11h-6"/>
                        </svg>
                        <select
                            id="role"
                            name="role"
                            class="{{ $errors->has('role') ? 'is-invalid' : '' }}"
                            required
                            style="width: 100%; padding: 0.85rem 1rem 0.85rem 3rem; border: 2px solid #e2e8f0; border-radius: 12px; font-family: inherit; font-size: 1rem; background: white; cursor: pointer;"
                        >
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your role...</option>
                            <option value="tourist" {{ old('role') == 'tourist' ? 'selected' : '' }}>Tourist - Book resorts and explore Mansalay</option>
                            <option value="resort_owner" {{ old('role') == 'resort_owner' ? 'selected' : '' }}>Resort Owner - Manage your resort and rooms</option>
                            <option value="enterprise_owner" {{ old('role') == 'enterprise_owner' ? 'selected' : '' }}>Enterprise Owner - Sell local products</option>
                        </select>
                    </div>
                    @error('role')
                        <div class="error-text">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Create Account</button>
            </form>

            <div class="register-footer">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </div>
</body>
</html>


