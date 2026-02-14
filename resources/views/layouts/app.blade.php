@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp
<!DOCTYPE html>
<html lang="en">

<head>
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
            margin: 0.25rem !important;
        }

        .artika-swal-cancel-btn {
            background: #fdf8f6 !important;
            color: #6f5849 !important;
            border: 1px solid #f2e8e5 !important;
            border-radius: 10px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
            margin: 0.25rem !important;
        }

        .artika-swal-toast {
            border-radius: 12px !important;
            background: #ffffff !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        html {
            zoom: 90%;
            background: #faf9f8;
        }

        body {
            background: #faf9f8;
            min-height: 100vh;
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            color: #4b382f;
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
            /* background: linear-gradient(135deg, #8a6b57 0%, #6f5849 100%); */
            background: var(--primary-dark);
            box-shadow: 0 4px 18px rgba(107, 83, 70, 0.08);
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            height: 70px;
        }

        .sidebar {
            background: #fffefc;
            position: fixed;
            top: 70px;
            left: 0;
            bottom: 0;
            width: 260px;
            /* Matched to col-md-2 */
            border-right: 1px solid #f2e8e5;
            padding: 1.25rem 0;
            overflow-y: auto;
            z-index: 1000;
            scrollbar-width: thin;
            scrollbar-color: #8a6b57 transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #8a6b57;
            border-radius: 4px;
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
            background: #fafaf9;
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
            color: #6f5849;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .submenu-link:hover {
            background: #fdf8f6;
            color: #85695a;
        }

        .submenu-link.active {
            color: #85695a;
            font-weight: 700;
            background: #f5f2f0;
        }

        .submenu-link i {
            margin-right: 0.75rem;
            font-size: 0.9rem;
            width: 1.1rem;
            text-align: center;
            color: #8a6b57;
            opacity: 0.8;
        }

        .sidebar-section-title {
            padding: 1.25rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-uppercase;
            color: #a18072;
            letter-spacing: 0.05em;
        }

        .main-content {
            padding: 0;
            background: #faf9f8;
            margin-left: 260px;
            /* Offset by fixed sidebar width */
            margin-top: 70px;
            /* Offset by fixed navbar height */
            min-height: calc(100vh - 70px);
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
            border-radius: 3812px;
            background: #7c6257ff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            /* box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15); */
            font-size: 1.1rem;
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
                    class="ms-2 text-white fw-bold d-none d-sm-inline">{{ App\Models\Setting::get('system_name', 'ARTIKA') }}</span>
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
                    <div class="profile-avatar me-3">{{ strtoupper(substr($user?->name ?? '', 0, 1)) }}</div>
                    <div class="d-flex flex-column">
                        <span class="user-name line-height-1 mb-1">{{ $user?->name }}</span>
                        <span class="text-white-50 fw-700 text-uppercase"
                            style="font-size: 0.75rem; letter-spacing: 0.05em;">{{ $user?->role?->name }}</span>
                    </div>
                </a>
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
                        <hr style="margin: 0.5rem 0; border-color: #f2e8e5; opacity: 0.1;">
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