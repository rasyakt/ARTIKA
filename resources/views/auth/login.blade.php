<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Login' }} - ARTIKA POS</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo2.png') }}">
    <!-- Using inline SVG icons for reliability and theme control (removed external CDN) -->
    <!-- Theme Detection ASAP -->
    <script>
        (function () {
            const saved = localStorage.getItem('artika-theme') || 'system';
            if (saved === 'dark' || (saved === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    {!! \App\Helpers\ThemeHelper::getCssVariables(\App\Models\Setting::get('site_color_theme', 'brown')) !!}
    <style>
        body {
            background-color: var(--gray-50);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: env(safe-area-inset-top) 20px env(safe-area-inset-bottom);
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            -webkit-font-smoothing: antialiased;
            transition: background-color 0.3s ease;
        }

        /* Dark mode overrides for background */
        [data-bs-theme="dark"] body {
            background-color: #030712;
        }

        /* Animated background pattern */
        /* Subtle grayscale pattern for ultra-clean look */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, var(--gray-200) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, var(--gray-300) 0%, transparent 50%);
            opacity: 0.1;
            animation: float 15s ease-in-out infinite;
        }

        [data-bs-theme="dark"] body::before {
            background-image:
                radial-gradient(circle at 20% 50%, #1f2937 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, #111827 0%, transparent 50%);
            opacity: 0.4;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: var(--card-bg);
            color: var(--color-text);
            border-bottom: 1px solid var(--gray-100);
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Subtle animated texture in header while keeping it neutral */
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, var(--gray-50) 0%, transparent 70%);
            opacity: 0.5;
            animation: rotate 20s linear infinite;
        }

        [data-bs-theme="dark"] .card-header::before {
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 70%);
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .brand-logo {
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: center;
        }

        .brand-logo img {
            max-width: 260px;
            height: auto;
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        [data-bs-theme="dark"] .brand-logo img {
            filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.5));
        }

        .brand-logo img:hover {
            transform: scale(1.05);
        }

        .brand-subtitle {
            font-size: 0.95rem;
            opacity: 0.95;
            font-weight: 500;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 2.5rem 2rem;
            background: var(--card-bg);
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--color-primary-dark);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tighter spacing for clean layout */
        .input-group {
            margin-bottom: 1rem;
        }

        .input-wrapper {
            display: block;
            width: 100%;
            border: 2px solid var(--gray-200);
        }

        .input-wrapper .form-control {
            box-sizing: border-box;
            width: 100%;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px 18px;
            border: 2px solid var(--gray-200);
            transition: all 0.3s ease;
            font-size: 1rem;
            background: var(--gray-50);
            color: var(--color-text);
        }

        .form-control:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px var(--color-secondary-light);
            background: var(--card-bg);
            outline: none;
        }

        .form-control::placeholder {
            color: gray;
        }

        .login-btn-container {
            width: 100%;
            margin-top: 1rem;
        }

        .btn-login {
            display: block;
            width: 100%;
            border-radius: 14px;
            padding: 16px;
            font-weight: 700;
            background: var(--color-primary-light);
            border: none;
            color: white;
            font-size: 1.1rem;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px var(--brown-200);
            text-align: center;
            cursor: pointer;
            -webkit-appearance: none;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px var(--brown-300);
            filter: brightness(1.1);
        }

        .btn-login:active {
            transform: translateY(-1px);
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            animation: shake 0.5s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-10px);
            }

            75% {
                transform: translateX(10px);
            }
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--color-danger-light) 0%, #fca5a5 100%);
            color: var(--color-danger-dark);
        }

        .card-footer {
            background: var(--brown-50);
            border-top: 1px solid var(--brown-100);
            padding: 1.5rem;
            text-align: center;
        }

        .footer-text {
            color: var(--gray-500);
            font-size: 0.85rem;
            font-weight: 500;
        }

        /* Input icons */
        .input-group {
            position: static;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-primary-light);
            font-size: 1.1rem;
            z-index: 10;
        }

        .icon-svg {
            width: 20px;
            height: 20px;
            display: block;
            color: var(--color-primary-light);
        }

        .decorative-svg {
            width: 140px;
            height: 140px;
            color: var(--color-primary);
            opacity: 0.1;
        }

        [data-bs-theme="dark"] .decorative-svg {
            opacity: 0.2;
        }

        .password-field {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px 18px;
            border: 2px solid var(--color-secondary-light);
            transition: all 0.3s ease;
            font-size: 1rem;
            background: var(--brown-50);
        }

        .password-field .form-control {
            padding-right: 50px;
        }

        .toggle-password-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            /* removed background to match input */
            border: none;
            border-radius: 8px;
            padding: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: none;
            transition: transform 0.12s ease, box-shadow 0.12s ease, background-color 0.12s ease;
        }

        .toggle-password-btn:hover {
            transform: translateY(-50%) scale(1.02);
            background-color: var(--brown-100);
            /* subtle tint matching theme */
            box-shadow: 0 4px 12px var(--brown-200);
        }

        .toggle-password-btn:focus-visible {
            outline: 2px solid var(--brown-300);
            outline-offset: 2px;
        }

        /* Make the eye icon match the form color */
        .toggle-password-btn .icon-svg {
            color: var(--color-primary-dark);
        }

        .toggle-password-btn:active {
            transform: translateY(-50%) scale(0.95);
        }

        .toggle-password {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--color-primary-light);
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
            z-index: 11;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: var(--color-primary);
        }

        .form-control.with-icon {
            padding-left: 48px;
        }

        .input-wrapper .form-control {
            padding-left: 48px;
        }

        .form-control.with-password-toggle {
            padding-right: 50px;
        }

        /* Role Badge Styling */
        .role-preview-container {
            background: var(--brown-50);
            border: 1px solid var(--brown-100);
            border-radius: 16px;
            padding: 12px;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .role-label {
            display: block;
            font-size: 0.65rem;
            text-transform: uppercase;
            font-weight: 800;
            color: var(--color-primary-light);
            letter-spacing: 1.5px;
            margin-bottom: 2px;
        }

        .role-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--color-primary-dark);
            margin: 0;
        }

        /* Decorative icons */
        .login-decorative {
            position: fixed;
            font-size: 80px;
            opacity: 0.05;
            pointer-events: none;
            z-index: 0;
        }

        .login-decorative.icon-1 {
            top: 10%;
            left: 5%;
            animation: float-slow 6s ease-in-out infinite;
        }

        .login-decorative.icon-2 {
            top: 70%;
            right: 5%;
            animation: float-slow 8s ease-in-out infinite 1s;
        }

        .login-decorative.icon-3 {
            bottom: 10%;
            left: 10%;
            animation: float-slow 7s ease-in-out infinite 2s;
        }

        @keyframes float-slow {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-30px);
            }
        }

        /* Hide decorative SVGs on small screens to avoid overlap */
        @media (max-width: 768px) {
            .login-decorative {
                display: none;
            }

            .login-container {
                padding: 0 12px;
            }

            .login-card {
                border-radius: 16px;
            }

            .card-body {
                padding: 2rem 1.5rem;
                gap: 1.25rem;
            }

            .form-control {
                padding: 12px 16px;
                font-size: 16px;
                /* Best for mobile zoom */
            }

            .btn-login {
                padding: 14px;
            }
        }

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid white;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .card-header {
                padding: 2rem 1.5rem;
            }

            .brand-logo {
                font-size: 2rem;
            }

            .card-body {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Decorative Background Icons -->
    <div class="login-decorative icon-1">
        <svg class="decorative-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
            <path d="M6 7V6a6 6 0 0112 0v1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"
                stroke-linejoin="round" />
            <path d="M3 7h18l-1.2 12.4a2 2 0 01-2 1.6H6.2a2 2 0 01-2-1.6L3 7z" stroke="currentColor" stroke-width="1.4"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <div class="login-decorative icon-2">
        <svg class="decorative-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
            <path d="M4 6h10l4 4v8a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" stroke="currentColor" stroke-width="1.4"
                stroke-linecap="round" stroke-linejoin="round" />
            <path d="M14 6v4h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"
                stroke-linejoin="round" />
        </svg>
    </div>
    <div class="login-decorative icon-3">
        <svg class="decorative-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden>
            <rect x="2" y="7" width="20" height="12" rx="2" stroke="currentColor" stroke-width="1.4"
                stroke-linejoin="round" />
            <path d="M7 7V5a5 5 0 0110 0v2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"
                stroke-linejoin="round" />
            <path d="M9 12h6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" />
        </svg>
    </div>

    <div class="login-container">
        <div class="card login-card">
            <div class="card-header">
                <div class="brand-logo">
                    <img src="{{ asset('img/logo2.png') }}" alt="ARTIKA Logo">
                </div>
                <p class="brand-subtitle mb-0">Smart Point of Sale System</p>
            </div>
            <div class="card-body">
                @if(isset($role))
                    <div class="role-preview-container">
                        <span class="role-label">Login Sebagai</span>
                        <h5 class="role-value">
                            {{ $role === 'cashier' ? 'CASHIER' : strtoupper($role) }}
                        </h5>
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                                style="vertical-align:middle;margin-right:6px;" aria-hidden>
                                <path
                                    d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                                    stroke="currentColor" stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 9v4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                                <path d="M12 17h.01" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" />
                            </svg>
                            Login Failed!
                        </strong>
                        <ul class="mb-0 mt-2" style="padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    @if(isset($role))
                        <input type="hidden" name="role" value="{{ $role }}">
                    @endif
                    <div class="mb-4 input-group">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-wrapper rounded-4">
                            <span class="input-icon" aria-hidden>
                                <svg class="icon-svg" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 12a4 4 0 100-8 4 4 0 000 8z" stroke="currentColor" stroke-width="1.4"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M4 20a8 8 0 0116 0" stroke="currentColor" stroke-width="1.4"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <input type="text" name="username" class="form-control with-icon" id="username"
                                placeholder="Enter your username or NIS" required autofocus aria-label="Username or NIS"
                                value="{{ old('username') }}">
                        </div>
                    </div>
                    <div class="mb-4 input-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper rounded-4">
                            <span class="input-icon" aria-hidden>
                                <svg class="icon-svg" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="11" width="18" height="10" rx="2" stroke="currentColor"
                                        stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7 11V8a5 5 0 0110 0v3" stroke="currentColor" stroke-width="1.4"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div class="password-field">
                                <input type="password" name="password"
                                    class="form-control with-icon with-password-toggle" id="password"
                                    placeholder="Enter your password" required aria-label="Password">
                                <button type="button" class="toggle-password-btn" id="togglePassword"
                                    title="Show or hide password" aria-pressed="false"
                                    aria-label="Toggle password visibility">
                                    <svg id="eyeIcon" class="icon-svg" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor"
                                            stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.4" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="login-btn-container">
                        <button type="submit" class="btn-login" id="loginBtn">
                            <span>LOGIN</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <p class="footer-text mb-0">© {{ date('Y') }} RPL_Sentinel. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            const eyeSvg = '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.4"/>';
            const eyeSlashSvg = '<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><line x1="2" y1="2" x2="22" y2="22" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>';

            if (togglePassword && passwordInput && eyeIcon) {
                togglePassword.addEventListener('click', function (e) {
                    e.preventDefault();
                    const isHidden = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
                    eyeIcon.innerHTML = isHidden ? eyeSlashSvg : eyeSvg;
                    this.setAttribute('aria-pressed', String(isHidden));
                });
            }

            // Add loading state on form submit
            const loginForm = document.getElementById('loginForm');
            if (loginForm) {
                loginForm.addEventListener('submit', function () {
                    const btn = document.getElementById('loginBtn');
                    if (btn) {
                        btn.classList.add('loading');
                        const span = btn.querySelector('span');
                        if (span) span.textContent = 'LOGGING IN...';
                    }
                });
            }

            // Auto-focus on username field
            const username = document.getElementById('username');
            if (username) username.focus();

            // Mobile keyboard focus fix: scroll element into view when focused
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function () {
                    if (window.innerWidth <= 768) {
                        setTimeout(() => {
                            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 300); // Delay to allow keyboard to appear
                    }
                });
            });
        });
    </script>

</body>

</html>