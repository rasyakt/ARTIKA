<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Activity Logs - ARTIKA POS</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #85695a;
            --primary-dark: #6f5849;
            --brown-50: #fdf8f6;
            --gray-100: #f5f5f4;
            --gray-200: #e7e5e4;
        }

        body {
            background-color: var(--gray-100);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            box-shadow: 0 2px 8px rgba(133, 105, 90, 0.15);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
    </style>
</head>

<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid px-4">
            <span class="navbar-brand fw-bold mb-0 h1"><i
                    class="fa-solid fa-clipboard-list me-2"></i>{{ __('pos.my_activity_logs') }}</span>
            <div>
                <a href="{{ route('pos.index') }}" class="btn"
                    style="border-radius: 10px; padding: 0.5rem 1.25rem; background: rgba(255, 255, 255, 0.15); border: none; color: white; font-weight: 600;">
                    <i class="fa-solid fa-arrow-left me-2"></i>{{ __('pos.back_to_pos') }}
                </a>
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
                    {{ $logs->links('vendor.pagination.no-prevnext') }}
                </div>
            @endif
        </div>
    </div>
</body>

</html>