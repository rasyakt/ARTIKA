<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ARTIKA POS</title>
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
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #a18072;
            font-size: 1.1rem;
            z-index: 10;
        }

        .form-control.with-icon {
            padding-left: 50px;
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

    <div class="login-container">
        <div class="card login-card">
            <div class="card-header">
                <h1 class="brand-logo mb-2">ARTIKA</h1>
                <p class="brand-subtitle mb-0">Smart Point of Sale System</p>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <strong>⚠️ Login Failed!</strong>
                        <ul class="mb-0 mt-2" style="padding-left: 20px;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="loginForm">
                    @csrf
                    <div class="mb-4">
                        <label for="username" class="form-label">Username / NIS</label>
                        <div class="input-group">
                            <span class="input-icon">👤</span>
                            <input type="text" name="username" class="form-control with-icon" id="username"
                                placeholder="Enter your username or NIS" required autofocus
                                value="{{ old('username') }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="password" class="form-control with-icon" id="password"
                                placeholder="Enter your password" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-login" id="loginBtn">
                            <span>LOGIN</span>
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <small class="text-muted">
                        <strong>Default Credentials:</strong><br>
                        Admin: <code>admin</code> | Cashier: <code>kasir1</code> or <code>12345</code>
                    </small>
                </div>
            </div>
            <div class="card-footer">
                <p class="footer-text mb-0">© {{ date('Y') }} ARTIKA POS System. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        // Add loading state on form submit
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.querySelector('span').textContent = 'LOGGING IN...';
        });

        // Auto-focus on username field
        document.getElementById('username').focus();
    </script>

</body>

</html>