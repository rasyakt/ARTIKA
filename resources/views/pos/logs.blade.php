@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    {{-- Apply theme ASAP to prevent flash --}}
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
    <title>My Activity Logs - ARTIKA POS</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: var(--color-primary);
            --primary-dark: var(--color-primary-dark);
            --brown-50: var(--brown-50);
            --gray-100: var(--color-bg);
            --gray-200: var(--gray-200);
        }

        body {
            background-color: var(--gray-100);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--color-text, #333);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 2px 8px rgba(133, 105, 90, 0.15);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            background: var(--card-bg, white);
        }

        .table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary-dark);
            background-color: var(--brown-50);
            border-bottom: 2px solid var(--gray-200);
        }

        .pagination .page-link {
            color: var(--primary);
            border: none;
            margin: 0 3px;
            border-radius: 5px;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            color: white;
        }

        /* Theme Toggle */
        .pos-theme-toggle {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 0.35rem 0.65rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.85rem;
        }

        .pos-theme-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .pos-theme-menu {
            min-width: 140px;
            padding: 0.4rem;
            border-radius: 10px;
            border: 1px solid var(--gray-200);
            background: var(--card-bg, #fff);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .pos-theme-opt {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.75rem;
            border-radius: 6px;
            font-size: 0.82rem;
            color: var(--color-text, #333);
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            transition: all 0.2s;
        }

        .pos-theme-opt:hover {
            background: var(--gray-100, #f5f5f5);
        }

        .pos-theme-opt.active {
            background: var(--brown-100, #f0e7e0);
            color: var(--color-primary, #85695a);
            font-weight: 600;
        }

        .pos-theme-opt i {
            width: 1rem;
            text-align: center;
        }

        .pos-theme-check {
            margin-left: auto;
            font-size: 0.7rem;
            color: var(--color-primary);
        }

        /* Dark Mode Overrides */
        [data-bs-theme="dark"] .card {
            background: var(--card-bg);
        }

        [data-bs-theme="dark"] .bg-white,
        [data-bs-theme="dark"] .card-footer.bg-white {
            background-color: var(--card-bg) !important;
        }

        [data-bs-theme="dark"] .bg-light {
            background-color: var(--gray-100) !important;
        }

        [data-bs-theme="dark"] .text-dark {
            color: var(--color-text) !important;
        }

        [data-bs-theme="dark"] .form-control {
            background-color: var(--gray-100);
            border-color: var(--gray-200);
            color: var(--color-text);
        }

        [data-bs-theme="dark"] .btn-outline-primary {
            color: var(--color-primary-light);
            border-color: var(--color-primary-light);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center mb-0 h1" href="{{ route('pos.index') }}">
                <img src="{{ asset('img/logo2.png') }}" alt="ARTIKA Logo" style="height: 38px; width: auto;">
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('pos.index') }}" class="btn me-2"
                    style="border-radius: 10px; padding: 0.45rem 1rem; background: var(--color-primary, #85695a); border: 2px solid var(--color-primary-light, #a08474); color: white; font-weight: 600; font-size: 0.85rem;">
                    <i class="fa-solid fa-arrow-left me-2"></i>{{ __('pos.back_to_pos') }}
                </a>

                <div class="dropdown">
                    <button class="btn p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="background: none;">
                        <div
                            style="width: 38px; height: 38px; background: var(--color-primary, #85695a); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; border: 2px solid var(--color-primary-light, #a08474);">
                            <i class="fas fa-user"></i>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0 overflow-hidden"
                        style="min-width: 260px; border-radius: 16px;">
                        <li class="p-3 bg-light border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px; font-size: 1.2rem;">
                                    {{ substr($user?->name ?? '', 0, 1) }}
                                </div>
                                <div class="overflow-hidden text-start">
                                    <h6 class="mb-0 fw-800 text-truncate text-dark">{{ $user?->name }}</h6>
                                    <div class="small text-muted text-truncate">@ {{ $user?->username }}</div>
                                </div>
                            </div>
                        </li>

                        {{-- Section: Settings/Theme --}}
                        <div class="p-2 border-bottom">
                            <div class="px-3 py-1 mb-1 small fw-bold text-uppercase text-muted"
                                style="font-size: 0.65rem;">
                                {{ __('common.settings') ?? 'Pengaturan' }}
                            </div>
                            <div class="px-3 py-2">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="small fw-600 text-muted"><i
                                            class="fa-solid fa-circle-half-stroke me-2"></i>Tema</span>
                                </div>
                                <div class="btn-group w-100" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt"
                                        data-theme="light" title="Light">
                                        <i class="fa-solid fa-sun"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt"
                                        data-theme="dark" title="Dark">
                                        <i class="fa-solid fa-moon"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt"
                                        data-theme="system" title="System">
                                        <i class="fa-solid fa-desktop"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <li>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center"
                                href="{{ route('pos.history') }}">
                                <i class="fa-solid fa-clock-rotate-left me-3 text-primary opacity-75"></i>
                                <span class="fw-600">{{ __('pos.transaction_history') }}</span>
                            </a>
                        </li>
                        <li class="border-top mt-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item py-3 px-3 d-flex align-items-center text-danger">
                                    <i class="fas fa-sign-out-alt me-3"></i>
                                    <span class="fw-700">{{ __('pos.exit_system') }}</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Filters -->
        <div class="mb-4">
            <div class="btn-group" role="group">
                <a href="{{ route('pos.logs') }}" class="btn btn-{{ !request('type') ? 'primary' : 'outline-primary' }}"
                    style="border-radius: 10px 0 0 10px; padding: 0.6rem 1.25rem;">
                    {{ __('pos.all') }}
                </a>
                <a href="{{ route('pos.logs', ['type' => 'login']) }}"
                    class="btn btn-{{ request('type') == 'login' ? 'primary' : 'outline-primary' }}"
                    style="padding: 0.6rem 1.25rem;">
                    <i class="fa-solid fa-sign-in-alt me-1"></i> {{ __('pos.login') }}
                </a>
                <a href="{{ route('pos.logs', ['type' => 'transaction']) }}"
                    class="btn btn-{{ request('type') == 'transaction' ? 'primary' : 'outline-primary' }}"
                    style="border-radius: 0 10px 10px 0; padding: 0.6rem 1.25rem;">
                    <i class="fa-solid fa-receipt me-1"></i> {{ __('pos.transactions') }}
                </a>
            </div>
            @if(request('type'))
                <a href="{{ route('pos.logs') }}" class="btn btn-link text-muted text-decoration-none ms-2">
                    <i class="fa-solid fa-times me-1"></i> {{ __('pos.clear_filter') }}
                </a>
            @endif
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">{{ __('admin.date_time') }}</th>
                                <th class="px-4 py-3">{{ __('pos.action') }}</th>
                                <th class="px-4 py-3">{{ __('pos.entity') }}</th>
                                <th class="px-4 py-3">{{ __('pos.details') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="px-4 py-3 text-muted" style="width: 200px;">
                                        {{ $log->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($log->action == 'login')
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                                <i class="fa-solid fa-sign-in-alt me-1"></i> LOGIN
                                            </span>
                                        @elseif($log->action == 'transaction_created')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                                <i class="fa-solid fa-receipt me-1"></i> TRANSACTION
                                            </span>
                                        @else
                                            <span class="badge bg-light text-dark border">
                                                {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="fw-semibold text-secondary">{{ $log->model_type }}</span>
                                        <small class="text-muted ms-1">#{{ $log->model_id }}</small>
                                    </td>
                                    <td class="px-4 py-3 text-secondary">
                                        {{ $log->notes }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">{{ __('pos.no_logs_found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($logs->hasPages())
                <div class="card-footer bg-white py-3 d-flex justify-content-end">
                    {{ $logs->links('vendor.pagination.custom-brown') }}
                </div>
            @endif
        </div>
    </div>

    {{-- Theme Toggle Script --}}
    <script>
        (function () {
            const opts = document.querySelectorAll('.pos-theme-opt');
            const icon = document.getElementById('posThemeIcon');
            const htmlEl = document.documentElement;
            function getEffective(p) { return p === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : p; }
            function apply(p) {
                htmlEl.setAttribute('data-bs-theme', getEffective(p));
                localStorage.setItem('artika-theme', p);
                const icons = { light: 'fa-sun', dark: 'fa-moon', system: 'fa-desktop' };
                icon.className = 'fa-solid ' + (icons[p] || 'fa-sun');
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