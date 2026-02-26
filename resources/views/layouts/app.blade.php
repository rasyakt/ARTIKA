@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    {{-- Apply theme ASAP to prevent flash of wrong theme --}}
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
    <title>@yield('title', 'Dashboard') - {{ App\Models\Setting::get('system_name', 'ARTIKA POS') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo2.png') }}">
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity=""
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    {!! \App\Helpers\ThemeHelper::getCssVariables(\App\Models\Setting::get('site_color_theme', 'brown')) !!}
    <style>
        /* SweetAlert2 Custom Theme ARTIKA */
        .artika-swal-popup {
            border-radius: 16px !important;
            padding: 1.5rem !important;
            border: 1px solid var(--brown-100) !important;
            font-family: 'Inter', system-ui, sans-serif !important;
        }

        .artika-swal-title {
            color: var(--brown-900) !important;
            font-weight: 700 !important;
            font-size: 1.25rem !important;
        }

        .artika-swal-confirm-btn {
            background: var(--color-primary-dark) !important;
            border-radius: 10px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 6px -1px rgba(111, 88, 73, 0.2) !important;
            margin: 0.25rem !important;
        }

        .artika-swal-cancel-btn {
            background: var(--brown-50) !important;
            color: var(--color-primary-dark) !important;
            border: 1px solid var(--brown-100) !important;
            border-radius: 10px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
            margin: 0.25rem !important;
        }

        .artika-swal-toast {
            border-radius: 12px !important;
            background: var(--color-white) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        html {
            zoom: 90%;
            background: var(--gray-50);
        }

        body {
            background: var(--color-bg);
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            color: var(--color-text);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Theme Toggle Dropdown */
        .theme-toggle-btn {
            background: var(--color-primary);
            border: 2px solid var(--color-primary-light);
            border-radius: 10px;
            padding: 0.45rem 0.85rem;
            color: white;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .theme-toggle-btn:hover {
            background: var(--color-primary-dark);
        }

        .theme-menu {
            min-width: 160px;
            padding: 0.5rem;
            border-radius: 12px;
            border: 1px solid var(--gray-200);
            background: var(--card-bg, #fff);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .theme-option {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.55rem 0.9rem;
            border-radius: 8px;
            font-size: 0.85rem;
            color: var(--color-text, #333);
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .theme-option:hover {
            background: var(--gray-100, #f5f5f5);
        }

        .theme-option.active {
            background: var(--color-primary, #85695a) !important;
            color: white !important;
            border-color: var(--color-primary-dark) !important;
        }

        .theme-selector-group .btn {
            border-radius: 8px;
            padding: 0.4rem;
        }

        /* Fix for Bootstrap Modals & SweetAlert2 with CSS Zoom */
        .modal-backdrop,
        .swal2-container,
        .modal {
            width: auto !important;
            height: auto !important;
            left: 0 !important;
            right: 0 !important;
            top: 0 !important;
            bottom: 0 !important;
        }

        .main-navbar {
            background: var(--navbar-bg) !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 64px;
        }

        .sidebar {
            background: var(--card-bg);
            position: fixed;
            top: 64px;
            left: 0;
            bottom: 0;
            width: 260px;
            border-right: 1px solid var(--gray-200);
            padding: 1.25rem 0;
            overflow-y: auto;
            z-index: 1000;
            transition: background-color 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: var(--color-primary);
            border-radius: 4px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--gray-600);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.25s ease;
            margin: 0.2rem 1rem;
            border-radius: 10px;
        }

        .sidebar-link:hover {
            background: var(--gray-100);
            color: var(--color-primary);
        }

        .sidebar-link.active {
            background: var(--color-secondary-light);
            color: var(--color-primary);
            font-weight: 700;
        }

        .sidebar-link i {
            margin-right: 0.75rem;
            font-size: 1.05rem;
            width: 1.2rem;
            text-align: center;
            color: var(--color-primary);
        }

        .sidebar-link.text-danger:hover {
            background: var(--color-danger-light) !important;
            border-left-color: var(--color-danger) !important;
        }

        /* Sidebar Dropdown Styles */
        .sidebar-dropdown {
            display: flex;
            flex-direction: column;
        }

        .sidebar-dropdown-toggle {
            cursor: pointer;
            position: relative;
            user-select: none;
        }

        .dropdown-arrow {
            margin-left: auto;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
            opacity: 0.6;
        }

        .sidebar-dropdown.active .dropdown-arrow {
            transform: rotate(90deg);
            opacity: 1;
        }

        .sidebar-submenu {
            display: none;
            background: var(--gray-50);
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-dropdown.active .sidebar-submenu {
            display: block;
        }

        .submenu-link {
            display: flex;
            align-items: center;
            padding: 0.65rem 1.5rem 0.65rem 3rem;
            color: var(--color-primary-dark);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .submenu-link:hover {
            background: var(--brown-50);
            color: var(--color-primary);
        }

        .submenu-link.active {
            color: var(--color-primary);
            font-weight: 700;
            background: var(--brown-50);
        }

        .submenu-link i {
            margin-right: 0.75rem;
            font-size: 0.9rem;
            width: 1.1rem;
            text-align: center;
            color: var(--color-primary);
            opacity: 0.8;
        }

        .sidebar-section-title {
            padding: 1.25rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-uppercase;
            color: var(--color-primary-light);
            letter-spacing: 0.05em;
        }

        .main-content {
            padding: 0;
            background: var(--gray-50);
            margin-left: 260px;
            margin-top: 64px;
            min-height: calc(100vh - 64px);
            width: calc(100% - 260px);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
        }

        .user-profile-link {
            background: none;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            border: none;
            transition: all 0.25s;
            color: white !important;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .user-profile-link:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .user-name {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .profile-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--color-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); */
            font-size: 1.1rem;
            line-height: normal;
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
            border: 1px solid var(--color-secondary-light);
            color: var(--color-primary-dark);
        }

        .pagination .page-link svg {
            width: 0.875rem !important;
            height: 0.875rem !important;
            max-width: 0.875rem !important;
            max-height: 0.875rem !important;
        }

        .pagination .page-item.active .page-link {
            background: var(--color-primary-dark);
            border-color: var(--color-primary);
        }

        .pagination .page-link:hover {
            background: var(--brown-50);
            border-color: var(--color-primary);
            color: var(--color-primary);
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
        @media (max-width: 1023px) {
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
                border-bottom: 2px solid var(--brown-100);
                margin-bottom: 1rem;
            }

            .sidebar-title {
                font-weight: 700;
                color: var(--color-primary-dark);
                font-size: 1.125rem;
            }

            .sidebar-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                color: var(--color-primary-dark);
                cursor: pointer;
                padding: 0;
                line-height: 1;
                transition: all 0.3s;
            }

            .sidebar-close:hover {
                color: var(--color-primary);
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

        @media (min-width: 1024px) {
            .sidebar-header {
                display: none;
            }
        }

        .main-navbar .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
        }
    </style>
</head>

<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark main-navbar">
        <div class="container-fluid px-4">
            <!-- Hamburger Menu (Mobile) -->
            @if($user?->role?->name === 'admin' || $user?->role?->name === 'warehouse')
                <button class="hamburger-btn me-3" id="hamburgerBtn" type="button">
                    ☰
                </button>
            @endif

            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('img/logo2.png') }}"
                    alt="{{ App\Models\Setting::get('system_name', 'ARTIKA Logo') }}"
                    style="height: 35px; width: auto;">
                <span
                    class="ms-2 fw-bold d-none d-sm-inline">{{ App\Models\Setting::get('system_name', 'ARTIKA') }}</span>
            </a>

            <div class="ms-auto d-flex align-items-center">
                <div class="dropdown">
                    <button class="nav-link user-profile-link dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="profile-avatar me-3">{{ strtoupper(substr($user?->name ?? '', 0, 1)) }}</div>
                        <div class="d-flex flex-column text-start">
                            <span class="user-name line-height-1 mb-1">{{ $user?->name }}</span>
                            <span class="fw-700 text-uppercase opacity-75"
                                style="font-size: 0.75rem; letter-spacing: 0.05em;">{{ $user?->role?->name }}</span>
                        </div>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0 overflow-hidden"
                        style="min-width: 280px; border-radius: 16px;">
                        {{-- Header Profil --}}
                        <li class="p-3 bg-light border-bottom border-secondary-subtle">
                            <div class="d-flex align-items-center">
                                <div class="profile-avatar bg-primary text-white me-3 d-flex align-items-center justify-content-center"
                                    style="width: 45px; height: 45px; border-radius: 50%;">
                                    {{ strtoupper(substr($user?->name ?? '', 0, 1)) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="mb-0 fw-800 text-truncate">{{ $user?->name }}</h6>
                                    <div class="small text-muted text-truncate">{{ $user?->role?->name }}</div>
                                </div>
                            </div>
                        </li>

                        {{-- Section: Settings --}}
                        <div class="p-2">
                            <div class="dropdown-header text-uppercase fw-bold"
                                style="font-size: 0.7rem; letter-spacing: 0.05em;">
                                {{ __('common.settings') ?? 'Pengaturan' }}
                            </div>

                            {{-- Theme Selection --}}
                            <div class="px-3 py-2">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-600 text-muted"><i
                                            class="fa-solid fa-circle-half-stroke me-2"></i>Tema</span>
                                </div>
                                <div class="btn-group w-100 theme-selector-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-option"
                                        data-theme="light" title="Light">
                                        <i class="fa-solid fa-sun"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-option"
                                        data-theme="dark" title="Dark">
                                        <i class="fa-solid fa-moon"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary theme-option"
                                        data-theme="system" title="System">
                                        <i class="fa-solid fa-desktop"></i>
                                    </button>
                                </div>
                            </div>

                            <hr class="dropdown-divider mx-2">

                            {{-- Language Selection --}}
                            <div class="px-3 py-2">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-600 text-muted"><i
                                            class="fa-solid fa-globe me-2"></i>Bahasa</span>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('language.change', 'en') }}"
                                        class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-secondary' }} grow">English</a>
                                    <a href="{{ route('language.change', 'id') }}"
                                        class="btn btn-sm {{ app()->getLocale() == 'id' ? 'btn-primary' : 'btn-outline-secondary' }} grow">Indonesia</a>
                                </div>
                            </div>
                        </div>

                        <hr class="dropdown-divider m-0">

                        {{-- Logout --}}
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item py-3 px-3 d-flex align-items-center text-danger fw-bold">
                                    <i class="fa-solid fa-right-from-bracket me-3"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (only for admin, manager and warehouse) -->
            @if(in_array($user?->role?->name, ['superadmin', 'admin', 'manager', 'warehouse']))
                <div class="col-md-2 sidebar px-0" id="sidebar">
                    <!-- Mobile Sidebar Header -->
                    <div class="sidebar-header">
                        <span class="sidebar-title">{{ __('menu.menu') }}</span>
                        <button class="sidebar-close" id="sidebarClose">×</button>
                    </div>

                    @if(in_array($user?->role?->name, ['superadmin', 'admin']))
                        <a href="{{ route('admin.dashboard') }}"
                            class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> {{ __('menu.dashboard') }}
                        </a>

                        <!-- Inventory Group -->
                        <div
                            class="sidebar-dropdown {{ request()->routeIs('admin.products*') || request()->routeIs('admin.categories*') || request()->routeIs('admin.promos*') ? 'active' : '' }}">
                            <div class="sidebar-link sidebar-dropdown-toggle">
                                <i class="fa-solid fa-box-archive"></i> {{ __('admin.inventory') ?? 'Inventory' }}
                                <i class="fa-solid fa-chevron-right dropdown-arrow"></i>
                            </div>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route('admin.products') }}"
                                        class="submenu-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-box"></i> {{ __('menu.products') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.categories') }}"
                                        class="submenu-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-folder"></i> {{ __('menu.categories') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.units.index') }}"
                                        class="submenu-link {{ request()->routeIs('admin.units*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-scale-balanced"></i> {{ __('admin.units') ?? 'Unit Categories' }}
                                    </a>
                                </li>
                                @if(App\Models\Setting::get('admin_enable_promos', true))
                                    <li>
                                        <a href="{{ route('admin.promos.index') }}"
                                            class="submenu-link {{ request()->routeIs('admin.promos.index*') ? 'active' : '' }}">
                                            <i class="fa-solid fa-tags"></i> {{ __('admin.promos') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Warehouse Group -->
                        <div class="sidebar-dropdown {{ request()->routeIs('warehouse.*') ? 'active' : '' }}">
                            <div class="sidebar-link sidebar-dropdown-toggle">
                                <i class="fa-solid fa-warehouse"></i> {{ __('admin.warehouse_management') ?? 'Warehouse' }}
                                <i class="fa-solid fa-chevron-right dropdown-arrow"></i>
                            </div>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route('warehouse.stock') }}"
                                        class="submenu-link {{ request()->routeIs('warehouse.stock*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-boxes-stacked"></i> {{ __('menu.stock_management') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('warehouse.low-stock') }}"
                                        class="submenu-link {{ request()->routeIs('warehouse.low-stock*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-triangle-exclamation"></i> {{ __('menu.low_stock_alerts') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('warehouse.stock-movements') }}"
                                        class="submenu-link {{ request()->routeIs('warehouse.stock-movements*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-arrows-rotate"></i> {{ __('menu.stock_movements') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Finance Group -->
                        <div
                            class="sidebar-dropdown {{ request()->routeIs('admin.expenses*') || request()->routeIs('admin.expense-categories*') ? 'active' : '' }}">
                            <div class="sidebar-link sidebar-dropdown-toggle">
                                <i class="fa-solid fa-wallet"></i> {{ __('menu.finance') ?? 'Finance' }}
                                <i class="fa-solid fa-chevron-right dropdown-arrow"></i>
                            </div>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route('admin.expenses.index') }}"
                                        class="submenu-link {{ request()->routeIs('admin.expenses.index') ? 'active' : '' }}">
                                        <i class="fa-solid fa-file-invoice-dollar"></i> {{ __('menu.operational_expenses') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.expense-categories.index') }}"
                                        class="submenu-link {{ request()->routeIs('admin.expense-categories.index') ? 'active' : '' }}">
                                        <i class="fa-solid fa-tags"></i> {{ __('menu.expense_categories') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.returns.index') }}"
                                        class="submenu-link {{ request()->routeIs('admin.returns.index') ? 'active' : '' }}">
                                        <i class="fa-solid fa-rotate-left"></i> {{ __('admin.returns_management') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- People Group -->
                        <div
                            class="sidebar-dropdown {{ request()->routeIs('admin.users*') || request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
                            <div class="sidebar-link sidebar-dropdown-toggle">
                                <i class="fa-solid fa-user-group"></i> {{ __('admin.people') ?? 'People' }}
                                <i class="fa-solid fa-chevron-right dropdown-arrow"></i>
                            </div>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route('admin.users') }}"
                                        class="submenu-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-users"></i> {{ __('menu.users') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.suppliers') }}"
                                        class="submenu-link {{ request()->routeIs('admin.suppliers*') && !request()->routeIs('admin.suppliers.pre_orders*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-truck"></i> {{ __('menu.suppliers') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.suppliers.pre_orders.index') }}"
                                        class="submenu-link {{ request()->routeIs('admin.suppliers.pre_orders*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-receipt"></i> {{ __('admin.pre_orders') ?? 'Pre-Orders' }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Reports Group -->
                        @if(App\Models\Setting::get('admin_enable_reports', true))
                            <div
                                class="sidebar-dropdown {{ request()->routeIs('admin.reports*') || request()->routeIs('admin.audit*') ? 'active' : '' }}">
                                <div class="sidebar-link sidebar-dropdown-toggle">
                                    <i class="fa-solid fa-chart-line"></i> {{ __('menu.reports') }}
                                    <i class="fa-solid fa-chevron-right dropdown-arrow"></i>
                                </div>
                                <ul class="sidebar-submenu">
                                    <li>
                                        <a href="{{ route('admin.reports') }}"
                                            class="submenu-link {{ request()->is('admin/reports') ? 'active' : '' }}">
                                            <i class="fa-solid fa-th-large"></i> {{ __('admin.reports_hub') ?? 'Reports Hub' }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.reports.warehouse') }}"
                                            class="submenu-link {{ request()->routeIs('admin.reports.warehouse*') ? 'active' : '' }}">
                                            <i class="fa-solid fa-warehouse"></i> {{ __('admin.warehouse_report') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.reports.cashier') }}"
                                            class="submenu-link {{ request()->routeIs('admin.reports.cashier*') ? 'active' : '' }}">
                                            <i class="fa-solid fa-cash-register"></i> {{ __('admin.cashier_report') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.reports.finance') }}"
                                            class="submenu-link {{ request()->routeIs('admin.reports.finance*') ? 'active' : '' }}">
                                            <i class="fa-solid fa-file-invoice-dollar"></i> {{ __('admin.finance_report') }}
                                        </a>
                                    </li>
                                    @if(App\Models\Setting::get('admin_enable_audit_logs', true))
                                        <li>
                                            <a href="{{ route('admin.audit.index') }}"
                                                class="submenu-link {{ request()->routeIs('admin.audit.index*') ? 'active' : '' }}">
                                                <i class="fa-solid fa-clipboard-list"></i> {{ __('admin.logs_report') }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        @endif

                        <a href="{{ route('admin.settings') }}"
                            class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                            <i class="fa-solid fa-gear"></i> {{ __('menu.settings') ?? 'Settings' }}
                        </a>

                        @if($user?->role?->name === 'superadmin')
                            <div class="sidebar-group mt-3">
                                <span class="text-muted small px-3 text-uppercase fw-bold"
                                    style="font-size: 0.7rem; opacity: 0.6;">System Admin</span>
                                <a href="{{ route('superadmin.dashboard') }}"
                                    class="sidebar-link {{ request()->is('superadmin/dashboard') || request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                                    <i class="fa-solid fa-code"></i> Developer Tools
                                </a>
                                <a href="{{ route('superadmin.settings') }}"
                                    class="sidebar-link {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-gears"></i> Advanced Settings
                                </a>
                                <a href="{{ route('superadmin.faq') }}"
                                    class="sidebar-link {{ request()->routeIs('superadmin.faq*') ? 'active' : '' }}">
                                    <i class="fa-solid fa-circle-question"></i> Kelola FAQ
                                </a>
                            </div>
                        @endif

                    @elseif($user?->role?->name === 'manager')
                        <a href="{{ route($routePrefix . 'dashboard') }}"
                            class="sidebar-link {{ request()->routeIs($routePrefix . 'dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> {{ __('menu.dashboard') }}
                        </a>

                        <!-- Reports Group (Read-only for Manager) -->
                        <div class="sidebar-dropdown {{ request()->routeIs($routePrefix . 'reports*') ? 'active' : '' }}">
                            <div class="sidebar-link sidebar-dropdown-toggle">
                                <i class="fa-solid fa-chart-line"></i> {{ __('menu.reports') }}
                                <i class="fa-solid fa-chevron-right dropdown-arrow"></i>
                            </div>
                            <ul class="sidebar-submenu">
                                <li>
                                    <a href="{{ route($routePrefix . 'reports') }}"
                                        class="submenu-link {{ request()->is($routePrefix . 'reports') ? 'active' : '' }}">
                                        <i class="fa-solid fa-th-large"></i> {{ __('admin.reports_hub') ?? 'Reports Hub' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route($routePrefix . 'reports.warehouse') }}"
                                        class="submenu-link {{ request()->routeIs($routePrefix . 'reports.warehouse*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-warehouse"></i> {{ __('admin.warehouse_report') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route($routePrefix . 'reports.cashier') }}"
                                        class="submenu-link {{ request()->routeIs($routePrefix . 'reports.cashier*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-cash-register"></i> {{ __('admin.cashier_report') }} /
                                        {{ __('admin.transaction_correction') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route($routePrefix . 'reports.finance') }}"
                                        class="submenu-link {{ request()->routeIs($routePrefix . 'reports.finance*') ? 'active' : '' }}">
                                        <i class="fa-solid fa-file-invoice-dollar"></i> {{ __('admin.finance_report') }}
                                    </a>
                                </li>
                                @if(App\Models\Setting::get('admin_enable_audit_logs', true))
                                    <li>
                                        <a href="{{ route('manager.audit.index') }}"
                                            class="submenu-link {{ request()->routeIs('manager.audit.index*') ? 'active' : '' }}">
                                            <i class="fa-solid fa-clipboard-list"></i> {{ __('admin.logs_report') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                    @elseif($user?->role?->name === 'warehouse')
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
                    @endif

                    <div class="mt-auto px-1 py-3">
                        <hr style="margin: 0.5rem 0; border-color: var(--brown-100); opacity: 0.1;">
                        <a href="{{ route('faq.index') }}"
                            class="sidebar-link {{ request()->routeIs('faq.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-circle-question"></i> Bantuan / FAQ
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit"
                                class="sidebar-link text-danger border-0 bg-transparent w-100 text-start py-2 px-3"
                                style="transition: all 0.3s;">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> {{ __('menu.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="col-md-10 main-content">
            @else
                    <div class="col-12 main-content">
                @endif

                    @yield('content')
                </div>
            </div>
        </div>

        <!-- Shared Scanner Modal -->
        @include('components.scanner-modal')

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

                // Close sidebar when clicking a link (mobile/tablet)
                if (window.innerWidth <= 1023) {
                    const sidebarLinks = sidebar.querySelectorAll('.sidebar-link:not(.sidebar-dropdown-toggle)');
                    sidebarLinks.forEach(link => {
                        link.addEventListener('click', closeSidebar);
                    });
                }
            }

            // Sidebar Dropdown Toggle Logic
            document.querySelectorAll('.sidebar-dropdown-toggle').forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    const parent = this.closest('.sidebar-dropdown');
                    const isActive = parent.classList.contains('active');

                    // Close other dropdowns (optional, but cleaner)
                    // document.querySelectorAll('.sidebar-dropdown').forEach(d => d.classList.remove('active'));

                    if (isActive) {
                        parent.classList.remove('active');
                    } else {
                        parent.classList.add('active');
                    }
                });
            });

            // Ensure active dropdowns are open on load
            document.addEventListener('DOMContentLoaded', function () {
                const activeSubmenuLink = document.querySelector('.submenu-link.active');
                if (activeSubmenuLink) {
                    const parentDropdown = activeSubmenuLink.closest('.sidebar-dropdown');
                    if (parentDropdown) {
                        parentDropdown.classList.add('active');
                    }
                }
            });
        </script>

        {{-- Theme Toggle Script --}}
        <script>
            (function () {
                const themeOptions = document.querySelectorAll('.theme-option');
                const themeIcon = document.getElementById('themeIcon');
                const themeLabel = document.getElementById('themeLabel');
                const htmlEl = document.documentElement;

                function getEffectiveTheme(pref) {
                    if (pref === 'system') {
                        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                    }
                    return pref;
                }

                function applyTheme(pref) {
                    const effective = getEffectiveTheme(pref);
                    htmlEl.setAttribute('data-bs-theme', effective);
                    localStorage.setItem('artika-theme', pref);

                    // Update button icon & label if they exist
                    const themeIcon = document.getElementById('themeIcon');
                    const themeLabel = document.getElementById('themeLabel');
                    const icons = { light: 'fa-sun', dark: 'fa-moon', system: 'fa-desktop' };
                    const labels = { light: 'Light', dark: 'Dark', system: 'System' };

                    if (themeIcon) {
                        themeIcon.className = 'fa-solid ' + (icons[pref] || 'fa-sun');
                    }
                    if (themeLabel) {
                        themeLabel.textContent = labels[pref] || 'Light';
                    }

                    // Update active indicator
                    themeOptions.forEach(opt => {
                        if (opt.dataset.theme === pref) {
                            opt.classList.add('active');
                            opt.querySelector('.theme-check')?.classList.remove('d-none');
                        } else {
                            opt.classList.remove('active');
                            opt.querySelector('.theme-check')?.classList.add('d-none');
                        }
                    });
                }

                // Init
                const saved = localStorage.getItem('artika-theme') || 'system';
                applyTheme(saved);

                // Click handlers
                themeOptions.forEach(opt => {
                    opt.addEventListener('click', () => applyTheme(opt.dataset.theme));
                });

                // Listen for system preference changes
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    if (localStorage.getItem('artika-theme') === 'system') {
                        applyTheme('system');
                    }
                });
            })();
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
                    confirmButtonColor: 'var(--color-primary-dark)',
                    cancelButtonColor: 'var(--gray-100)',
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