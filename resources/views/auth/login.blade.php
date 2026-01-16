<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ARTIKA POS</title>
    <!-- Using inline SVG icons for reliability and theme control (removed external CDN) -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #85695a 0%, #5c4a3f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background pattern */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(191, 160, 148, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(161, 128, 114, 0.15) 0%, transparent 50%);
            animation: float 15s ease-in-out infinite;
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
            background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);
            color: white;
            border-bottom: none;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
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
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
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
            background: white;
        }

        .form-label {
            font-weight: 600;
            color: #6f5849;
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
        }

        .input-wrapper .form-control {
            box-sizing: border-box;
            width: 100%;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px 18px;
            border: 2px solid #e0cec7;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: #fdf8f6;
        }

        .form-control:focus {
            border-color: #85695a;
            box-shadow: 0 0 0 4px rgba(133, 105, 90, 0.1);
            background: white;
            outline: none;
        }

        .form-control::placeholder {
            color: #bfa094;
        }

        .btn-login {
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);
            border: none;
            color: white;
            font-size: 1.1rem;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(133, 105, 90, 0.3);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-login:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(133, 105, 90, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
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
            background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
            color: #991b1b;
        }

        .card-footer {
            background: linear-gradient(135deg, #f5f5f4 0%, #e7e5e4 100%);
            border-top: 1px solid #e0cec7;
            padding: 1.25rem;
            text-align: center;
        }

        .footer-text {
            color: #78716c;
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
            color: #a18072;
            font-size: 1.1rem;
            z-index: 10;
        }

        .icon-svg {
            width: 20px;
            height: 20px;
            display: block;
            color: #a18072;
        }

        .decorative-svg {
            width: 140px;
            height: 140px;
            color: rgba(255, 255, 255, 0.9);
            opacity: 0.06;
        }

        .password-field {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-control {
            border-radius: 12px;
            padding: 14px 18px;
            border: 2px solid #e0cec7;
            transition: all 0.3s ease;
            font-size: 1rem;
            background: #fdf8f6;
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
            background-color: rgba(133, 105, 90, 0.06);
            /* subtle tint matching theme */
            box-shadow: 0 4px 12px rgba(133, 105, 90, 0.08);
        }

        .toggle-password-btn:focus-visible {
            outline: 2px solid rgba(133, 105, 90, 0.18);
            outline-offset: 2px;
        }

        /* Make the eye icon match the form color */
        .toggle-password-btn .icon-svg {
            color: #6f5849;
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
            color: #a18072;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
            z-index: 11;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #85695a;
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
                padding: 1.5rem;
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
                <h1 class="brand-logo mb-2">ARTIKA</h1>
                <p class="brand-subtitle mb-0">{{ $title ?? 'Smart Point of Sale System' }}</p>
            </div>
            <div class="card-body">
                @if(isset($role) && $role !== 'cashier')
                    <div class="alert alert-light border text-center mb-4">
                        <small class="text-muted text-uppercase fw-bold">Login Sebagai</small>
                        <h5 class="fw-bold mb-0 text-primary">{{ ucfirst($role) }}</h5>
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
                        <label for="username" class="form-label">Username / NIS</label>
                        <div class="input-wrapper">
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
                        <div class="input-wrapper">
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
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login" id="loginBtn">
                            <span>LOGIN</span>
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <p class="footer-text mb-0">© {{ date('Y') }} RPL_Sentinel.ofc. All rights reserved.</p>
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
        });
    </script>

</body>

</html>