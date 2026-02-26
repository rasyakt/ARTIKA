@php 
                                        /** @var \App\Models\User $user */
    $user = Auth::user();
    $role = strtolower($user->role->name ?? '');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('common.profile') }} - ARTIKA POS</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    {!! \App\Helpers\ThemeHelper::getCssVariables(\App\Models\Setting::get('site_color_theme', 'brown')) !!}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary: var(--color-primary);
            --primary-dark: var(--color-primary-dark);
            --primary-light: var(--color-primary-light);
            --brown-50: var(--brown-50);
            --gray-100: var(--color-bg);
            --gray-200: var(--gray-200);
            --gray-800: var(--gray-700);
        }

        body {
            background-color: var(--gray-100);
            font-family: 'Inter', sans-serif;
            color: var(--color-text, var(--gray-800));
        }

        /* NAVBAR */
        .pos-navbar {
            background: var(--primary-dark);
            color: white;
            padding: 0.75rem 1.5rem;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(133, 105, 90, 0.15);
            z-index: 1050;
            position: sticky;
            top: 0;
        }

        .navbar-brand {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .profile-trigger {
            transition: all 0.25s;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            background: none;
            border: none;
            display: flex;
            align-items: center;
        }

        .profile-trigger:hover {
            background: var(--color-primary) !important;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background: var(--color-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            border: 2px solid var(--color-primary-light);
            transition: all 0.2s;
        }

        .dropdown-menu {
            border: 1px solid rgba(133, 105, 90, 0.1);
            animation: slideIn 0.2s ease-out;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item:hover {
            background-color: var(--brown-50);
            color: var(--primary-dark);
        }

        /* Back Button styled for new navbar */
        .btn-back-pos {
            border-radius: 10px;
            padding: 0.4rem 0.8rem;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-back-pos:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>
    <div class="pos-navbar">
        <div class="navbar-brand d-flex align-items-center">
            <a href="{{ route('pos.index') }}">
                <img src="{{ asset('img/logo2.png') }}" alt="ARTIKA Logo" style="height: 38px; width: auto;">
            </a>
        </div>
        <div class="navbar-right">
            <a href="{{ route('pos.index') }}" class="btn btn-back-pos d-flex align-items-center">
                <i class="fa-solid fa-arrow-left me-md-2"></i>
                <span class="d-none d-md-inline">Back to POS</span>
            </a>

            <div class="dropdown">
                <button class="btn p-0 border-0 profile-trigger d-flex align-items-center" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-avatar me-3">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="d-none d-lg-flex flex-column align-items-start me-2">
                        <span class="text-white fw-700 mb-0"
                            style="font-size: 1rem; line-height: 1.1;">{{ $user?->name }}</span>
                        <span class="text-white-50 fw-700 text-uppercase"
                            style="font-size: 0.75rem; letter-spacing: 0.05em;">{{ $user?->role?->name }}</span>
                    </div>
                </button>
                <x-pos-profile-dropdown />
            </div>
        </div>
    </div>
    <div class="container mt-4 pb-5">
        @include('profile.partials')
    </div>
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        // Logout confirmation handler
        document.getElementById('btnLogout')?.addEventListener('click', function () {
            Swal.fire({
                title: '{{ __('pos.exit_confirm') ?? 'Keluar Sistem?' }}',
                text: "{{ __('pos.exit_confirm_message') ?? 'Apakah Anda yakin ingin keluar?' }}",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'var(--color-primary-dark)',
                cancelButtonColor: 'var(--gray-100)',
                confirmButtonText: '{{ __('pos.yes_exit') ?? 'Ya, Keluar' }}',
                cancelButtonText: '{{ __('pos.cancel') ?? 'Batal' }}',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary px-4 py-2 me-3',
                    cancelButton: 'btn btn-light px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });

        { { --Theme Toggle Script-- } }
        (function () {
            const opts = document.querySelectorAll('.pos-theme-opt');
            const icon = document.getElementById('posThemeIcon');
            const htmlEl = document.documentElement;
            function getEffective(p) { return p === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : p; }
            function apply(p) {
                htmlEl.setAttribute('data-bs-theme', getEffective(p));
                localStorage.setItem('artika-theme', p);
                const icons = { light: 'fa-sun', dark: 'fa-moon', system: 'fa-desktop' };
                if (icon) icon.className = 'fa-solid ' + (icons[p] || 'fa-sun');
                opts.forEach(o => {
                    const chk = o.querySelector('.pos-theme-check');
                    if (o.dataset.theme === p) { o.classList.add('active'); chk?.classList.remove('d-none'); }
                    else { o.classList.remove('active'); chk?.classList.add('d-none'); }
                });
            }
            apply(localStorage.getItem('artika-theme') || 'system');
            opts.forEach(o => o.addEventListener('click', () => apply(o.dataset.theme)));
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (localStorage.getItem('artika-theme') === 'system') apply('system');
            });
        })();
    </script>
</body>

</html>