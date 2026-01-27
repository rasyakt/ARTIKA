<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - ARTIKA POS</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity=""
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        /* SweetAlert2 Custom Theme ARTIKA */
        .artika-swal-popup {
            border-radius: 16px !important;
            padding: 1.5rem !important;
            border: 1px solid #f2e8e5 !important;
            font-family: 'Inter', system-ui, sans-serif !important;
        }

        .artika-swal-title {
            color: #4b382f !important;
            font-weight: 700 !important;
            font-size: 1.25rem !important;
        }

        .artika-swal-confirm-btn {
            background: #6f5849 !important;
            border-radius: 10px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 6px -1px rgba(111, 88, 73, 0.2) !important;
        }

        .artika-swal-cancel-btn {
            background: #fdf8f6 !important;
            color: #6f5849 !important;
            border: 1px solid #f2e8e5 !important;
            border-radius: 10px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
        }

        .artika-swal-toast {
            border-radius: 12px !important;
            background: #ffffff !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        body {
            background: #faf9f8;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            color: #4b382f;
        }

        .main-navbar {
            background: linear-gradient(135deg, #8a6b57 0%, #6f5849 100%);
            box-shadow: 0 4px 18px rgba(107, 83, 70, 0.08);
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        }

        .sidebar {
            background: #fffefc;
            min-height: calc(100vh - 70px);
            border-right: 1px solid #f2e8e5;
            padding: 1.25rem 0;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: #6f5849;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-link:hover {
            background: #fdf8f6;
            color: #85695a;
            border-left-color: #85695a;
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, #fdf8f6 0%, #f2e8e5 100%);
            color: #85695a;
            border-left-color: #85695a;
            font-weight: 600;
        }

        .sidebar-link i {
            margin-right: 0.75rem;
            font-size: 1.05rem;
            width: 1.2rem;
            text-align: center;
            color: #8a6b57;
        }

        .sidebar-link.text-danger:hover {
            background: #fee2e2 !important;
            border-left-color: #ef4444 !important;
        }

        .main-content {
            padding: 0;
            background: #faf9f8;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .user-profile-link {
            background: rgba(255, 255, 255, 0.06);
            border-radius: 10px;
            padding: 0.4rem 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            transition: all 0.2s;
            color: white !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .user-profile-link:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #b2917f, #8a6b57);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(107, 83, 70, 0.18);
            font-size: 1rem;
        }

        /* Pagination Styling */
        .pagination {
            margin: 1rem 0;
        }

        .pagination .page-link {
            font-size: 0.875rem !important;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            margin: 0 0.25rem;
            border: 1px solid #e0cec7;
            color: #6f5849;
        }

        .pagination .page-link svg {
            width: 0.875rem !important;
            height: 0.875rem !important;
            max-width: 0.875rem !important;
            max-height: 0.875rem !important;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);
            border-color: #85695a;
        }

        .pagination .page-link:hover {
            background: #fdf8f6;
            border-color: #85695a;
            color: #85695a;
        }

        /* Hamburger Menu Button */
        .hamburger-btn {
            display: none;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            transition: all 0.3s;
            color: white;
            font-size: 1.25rem;
            line-height: 1;
        }

        .hamburger-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hamburger-btn {
                display: block;
            }

            /* Show only avatar on mobile */
            .user-profile-link .user-name {
                display: none;
            }

            .user-profile-link {
                padding: 0.15rem;
            }

            .profile-avatar {
                width: 40px;
                height: 40px;
                font-size: 0.95rem;
            }

            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                width: 280px;
                height: 100vh;
                z-index: 1050;
                transition: left 0.3s ease-in-out;
                box-shadow: 2px 0 12px rgba(0, 0, 0, 0.1);
                overflow-y: auto;
                padding-top: 1rem;
            }

            .sidebar.active {
                left: 0;
            }

            .sidebar-header {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 1.5rem;
                border-bottom: 2px solid #f2e8e5;
                margin-bottom: 1rem;
            }

            .sidebar-title {
                font-weight: 700;
                color: #6f5849;
                font-size: 1.125rem;
            }

            .sidebar-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                color: #6f5849;
                cursor: pointer;
                padding: 0;
                line-height: 1;
                transition: all 0.3s;
            }

            .sidebar-close:hover {
                color: #85695a;
                transform: rotate(90deg);
            }

            .col-md-2.sidebar {
                display: block !important;
            }

            .col-md-10.main-content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        @media (min-width: 769px) {
            .sidebar-header {
                display: none;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark main-navbar">
        <div class="container-fluid px-4">
            <!-- Hamburger Menu (Mobile) -->
            @if(Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'warehouse')
                <button class="hamburger-btn me-3" id="hamburgerBtn" type="button">
                    ☰
                </button>
            @endif

            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('img/logo2.png') }}" alt="ARTIKA Logo" style="height: 35px; width: auto;">
            </a>

            <div class="ms-auto d-flex align-items-center">
                <!-- Language Switcher -->
                <div class="dropdown me-3">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-globe me-1"></i> {{ strtoupper(app()->getLocale()) }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                href="{{ route('language.change', 'en') }}">
                                English
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ app()->getLocale() == 'id' ? 'active' : '' }}"
                                href="{{ route('language.change', 'id') }}">
                                Bahasa Indonesia
                            </a>
                        </li>
                    </ul>
                </div>

                <a class="nav-link user-profile-link" href="#" title="Profile Settings (Coming Soon)"
                    style="cursor: default;">
                    <span class="profile-avatar me-2">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="badge bg-light text-dark ms-2"
                        style="font-size: 0.7rem;">{{ ucfirst(Auth::user()->role->name) }}</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (only for admin and warehouse) -->
            @if(Auth::user()->role->name === 'admin' || Auth::user()->role->name === 'warehouse')
                <div class="col-md-2 sidebar px-0" id="sidebar">
                    <!-- Mobile Sidebar Header -->
                    <div class="sidebar-header">
                        <span class="sidebar-title">{{ __('menu.menu') }}</span>
                        <button class="sidebar-close" id="sidebarClose">×</button>
                    </div>

                    @if(Auth::user()->role->name === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> {{ __('menu.dashboard') }}
                        </a>
                        <a href="{{ route('admin.products') }}"
                            class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                            <i class="fa-solid fa-box"></i> {{ __('menu.products') }}
                        </a>
                        <a href="{{ route('admin.categories') }}"
                            class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                            <i class="fa-solid fa-folder"></i> {{ __('menu.categories') }}
                        </a>
                        <a href="{{ route('admin.users') }}"
                            class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users"></i> {{ __('menu.users') }}
                        </a>
                        <a href="{{ route('admin.suppliers') }}"
                            class="sidebar-link {{ request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
                            <i class="fa-solid fa-truck"></i> {{ __('menu.suppliers') }}
                        </a>
                        <a href="{{ route('admin.expenses.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.expenses.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-wallet"></i> {{ __('menu.operational_expenses') }}
                        </a>
                        <a href="{{ route('admin.expense-categories.index') }}"
                            class="sidebar-link {{ request()->routeIs('admin.expense-categories.index') ? 'active' : '' }}"
                            style="padding-left: 2.5rem; font-size: 0.9rem; opacity: 0.8;">
                            <i class="fa-solid fa-tags" style="font-size: 0.8rem;"></i> {{ __('menu.expense_categories') }}
                        </a>
                        <hr style="margin: 0.5rem 0; opacity: 0.1;">
                        <a href="{{ route('admin.reports') }}"
                            class="sidebar-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line"></i> {{ __('menu.reports') }}
                        </a>
                        <div class="sidebar-submenu {{ request()->routeIs('admin.reports*') || request()->routeIs('admin.audit*') ? 'show' : '' }}"
                            style="padding-left: 1.5rem;">
                            <a href="{{ route('admin.reports.warehouse') }}"
                                class="sidebar-link py-2 {{ request()->routeIs('admin.reports.warehouse*') ? 'active' : '' }}"
                                style="font-size: 0.85rem; border-left: none;">
                                <i class="fa-solid fa-warehouse" style="font-size: 0.9rem;"></i>
                                {{ __('admin.warehouse_report') }}
                            </a>
                            <a href="{{ route('admin.reports.cashier') }}"
                                class="sidebar-link py-2 {{ request()->routeIs('admin.reports.cashier*') ? 'active' : '' }}"
                                style="font-size: 0.85rem; border-left: none;">
                                <i class="fa-solid fa-cash-register" style="font-size: 0.9rem;"></i>
                                {{ __('admin.cashier_report') }}
                            </a>
                            <a href="{{ route('admin.reports.finance') }}"
                                class="sidebar-link py-2 {{ request()->routeIs('admin.reports.finance*') ? 'active' : '' }}"
                                style="font-size: 0.85rem; border-left: none;">
                                <i class="fa-solid fa-file-invoice-dollar" style="font-size: 0.9rem;"></i>
                                {{ __('admin.finance_report') }}
                            </a>
                            <a href="{{ route('admin.audit.index') }}"
                                class="sidebar-link py-2 {{ request()->routeIs('admin.audit.index*') ? 'active' : '' }}"
                                style="font-size: 0.85rem; border-left: none;">
                                <i class="fa-solid fa-clipboard-list" style="font-size: 0.9rem;"></i>
                                {{ __('admin.logs_report') }}
                            </a>
                        </div>
                        <hr style="margin: 1rem 0; border-color: #f2e8e5;">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="sidebar-link text-danger"
                                style="width: 100%; border: none; background: none; text-align: left;">
                                <i class="fa-solid fa-right-from-bracket"></i> {{ __('menu.logout') }}
                            </button>
                        </form>
                    @elseif(Auth::user()->role->name === 'warehouse')
                        <a href="{{ route('warehouse.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('warehouse.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> {{ __('menu.dashboard') }}
                        </a>
                        <a href="{{ route('warehouse.stock') }}"
                            class="sidebar-link {{ request()->routeIs('warehouse.stock') ? 'active' : '' }}">
                            <i class="fa-solid fa-warehouse"></i> {{ __('menu.stock_management') }}
                        </a>
                        <a href="{{ route('warehouse.low-stock') }}"
                            class="sidebar-link {{ request()->routeIs('warehouse.low-stock') ? 'active' : '' }}">
                            <i class="fa-solid fa-triangle-exclamation"></i> {{ __('menu.low_stock_alerts') }}
                        </a>
                        <a href="{{ route('warehouse.stock-movements') }}"
                            class="sidebar-link {{ request()->routeIs('warehouse.stock-movements') ? 'active' : '' }}">
                            <i class="fa-solid fa-arrows-rotate"></i> {{ __('menu.stock_movements') }}
                        </a>
                        <hr style="margin: 1rem 0; border-color: #f2e8e5;">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="sidebar-link text-danger"
                                style="width: 100%; border: none; background: none; text-align: left;">
                                <i class="fa-solid fa-right-from-bracket"></i> {{ __('menu.logout') }}
                            </button>
                        </form>
                    @endif
                </div>
                <div class="col-md-10 main-content">
            @else
                    <div class="col-12 main-content">
                @endif

                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <script>
            // Hamburger menu functionality
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const sidebarClose = document.getElementById('sidebarClose');

            if (hamburgerBtn) {
                // Open sidebar
                hamburgerBtn.addEventListener('click', function () {
                    sidebar.classList.add('active');
                    sidebarOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                });

                // Close sidebar - close button
                sidebarClose.addEventListener('click', closeSidebar);

                // Close sidebar - overlay click
                sidebarOverlay.addEventListener('click', closeSidebar);

                // Close sidebar function
                function closeSidebar() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }

                // Close sidebar when clicking a link (mobile)
                if (window.innerWidth <= 768) {
                    const sidebarLinks = sidebar.querySelectorAll('.sidebar-link');
                    sidebarLinks.forEach(link => {
                        link.addEventListener('click', closeSidebar);
                    });
                }
            }
        </script>

        @stack('scripts')

        <script>
            // Professional Notification Helpers
            const ArtikaToast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'artika-swal-toast'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            function showToast(icon, title) {
                ArtikaToast.fire({
                    icon: icon,
                    title: title
                });
            }

            function confirmAction(options = {}) {
                const defaults = {
                    title: 'Apakah Anda yakin?',
                    text: "Tindakan ini tidak dapat dibatalkan!",
                    icon: 'warning',
                    confirmButtonText: 'Ya, Lanjutkan!',
                    cancelButtonText: 'Batal'
                };

                const settings = { ...defaults, ...options };

                return Swal.fire({
                    title: settings.title,
                    text: settings.text,
                    icon: settings.icon,
                    showCancelButton: true,
                    confirmButtonColor: '#6f5849',
                    cancelButtonColor: '#f1f1f1',
                    confirmButtonText: settings.confirmButtonText,
                    cancelButtonText: settings.cancelButtonText,
                    customClass: {
                        popup: 'artika-swal-popup',
                        title: 'artika-swal-title',
                        confirmButton: 'artika-swal-confirm-btn',
                        cancelButton: 'artika-swal-cancel-btn'
                    },
                    buttonsStyling: false
                });
            }

            // Flash Message Handling
            document.addEventListener('DOMContentLoaded', function () {
                @if(session('success') || session('status'))
                    showToast('success', "{{ session('success') ?: session('status') }}");
                @endif

                @if(session('error'))
                    showToast('error', "{{ session('error') }}");
                @endif

                @if(session('warning'))
                    showToast('warning', "{{ session('warning') }}");
                @endif

                @if($errors->any())
                    showToast('error', "{{ $errors->first() }}");
                @endif
            });

            // Global Numeric Input Validation
            document.addEventListener('keydown', function (e) {
                if (e.target.tagName === 'INPUT' && e.target.type === 'number') {
                    // Block 'e', 'E', '-', '+', '.', ','
                    const blockedKeys = ['e', 'E', '-', '+', '.', ','];
                    if (blockedKeys.includes(e.key)) {
                        e.preventDefault();
                    }
                }
            });

            // Prevent paste of non-numeric characters
            document.addEventListener('paste', function (e) {
                if (e.target.tagName === 'INPUT' && e.target.type === 'number') {
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text');
                    if (!/^\d+$/.test(pasteData)) {
                        e.preventDefault();
                        showToast('warning', 'Hanya angka bulat yang diperbolehkan');
                    }
                }
            });
        </script>
</body>

</html>