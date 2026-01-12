<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - ARTIKA POS</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
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
            border-bottom: 1px solid rgba(255,255,255,0.04);
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

        .user-profile-link:hover { background: rgba(255,255,255,0.12); }

        .profile-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg,#b2917f,#8a6b57);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(107,83,70,0.18);
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
            .hamburger-btn { display: block; }

            /* Show only avatar on mobile */
            .user-profile-link .user-name { display: none; }
            .user-profile-link { padding: 0.15rem; }
            .profile-avatar { width: 40px; height: 40px; font-size: 0.95rem; }

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

            <a class="navbar-brand" href="{{ route('dashboard') }}">ARTIKA POS</a>

            <div class="ms-auto d-flex align-items-center">
                <a class="nav-link user-profile-link" href="#" title="Profile Settings (Coming Soon)" style="cursor: default;">
                    <span class="profile-avatar me-2">{{ strtoupper(substr(Auth::user()->name,0,1)) }}</span>
                    <span class="user-name">{{ Auth::user()->name }}</span>
                    <span class="badge bg-light text-dark ms-2" style="font-size: 0.7rem;">{{ ucfirst(Auth::user()->role->name) }}</span>
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
                        <span class="sidebar-title">Menu</span>
                        <button class="sidebar-close" id="sidebarClose">×</button>
                    </div>

                    @if(Auth::user()->role->name === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> Dashboard
                        </a>
                        <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                            <i class="fa-solid fa-box"></i> Products
                        </a>
                        <a href="{{ route('admin.categories') }}" class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                            <i class="fa-solid fa-folder"></i> Categories
                        </a>
                        <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="fa-solid fa-users"></i> Users
                        </a>
                        <a href="{{ route('admin.suppliers') }}" class="sidebar-link {{ request()->routeIs('admin.suppliers*') ? 'active' : '' }}">
                            <i class="fa-solid fa-truck"></i> Suppliers
                        </a>
                        <a href="{{ route('admin.reports') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line"></i> Reports
                        </a>
                        <hr style="margin: 1rem 0; border-color: #f2e8e5;">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="sidebar-link text-danger" style="width: 100%; border: none; background: none; text-align: left;">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    @elseif(Auth::user()->role->name === 'warehouse')
                        <a href="{{ route('warehouse.dashboard') }}" class="sidebar-link {{ request()->routeIs('warehouse.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> Dashboard
                        </a>
                        <a href="{{ route('warehouse.stock') }}" class="sidebar-link {{ request()->routeIs('warehouse.stock') ? 'active' : '' }}">
                            <i class="fa-solid fa-warehouse"></i> Stock Management
                        </a>
                        <a href="{{ route('warehouse.low-stock') }}" class="sidebar-link {{ request()->routeIs('warehouse.low-stock') ? 'active' : '' }}">
                            <i class="fa-solid fa-triangle-exclamation"></i> Low Stock Alerts
                        </a>
                        <a href="{{ route('warehouse.stock-movements') }}" class="sidebar-link {{ request()->routeIs('warehouse.stock-movements') ? 'active' : '' }}">
                            <i class="fa-solid fa-arrows-rotate"></i> Stock Movements
                        </a>
                        <hr style="margin: 1rem 0; border-color: #f2e8e5;">
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="sidebar-link text-danger" style="width: 100%; border: none; background: none; text-align: left;">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    @endif
                </div>
                <div class="col-md-10 main-content">
            @else
                    <div class="col-12 main-content">
                @endif
                    @if(session('status'))
                        <div class="alert alert-success alert-dismissible fade show m-4 shadow-sm" style="border-radius: 12px; border: none;">
                            <i class="fa-solid fa-circle-check me-2"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
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
            hamburgerBtn.addEventListener('click', function() {
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
</body>

</html>