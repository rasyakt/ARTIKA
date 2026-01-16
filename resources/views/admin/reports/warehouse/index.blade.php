@extends('layouts.app')

@section('content')
    <style>
        .stats-card {
            border-radius: 16px;
            border: none;
            overflow: hidden;
            transition: all 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(133, 105, 90, 0.15) !important;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .table-hover tbody tr:hover {
            background-color: #fdf8f6;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="d-flex align-items-center mb-1">
                    <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary me-3"
                        style="border-radius: 10px;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0" style="color: #6f5849;">
                        <i class="fa-solid fa-warehouse me-2"></i>{{ __('admin.warehouse_reports_title') }}
                    </h2>
                </div>
                <p class="text-muted mb-0 ms-5 ps-4">{{ __('admin.warehouse_reports_subtitle') }}</p>
            </div>
            <div class="text-end">
                <a href="{{ route('admin.reports.warehouse.export', request()->all()) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-file-pdf me-1"></i> {{ __('admin.export_report') }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4"
            style="border-radius: 16px; border: none; background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);">
            <div class="card-body p-4">
                <form action="{{ route('admin.reports.warehouse') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label for="period" class="form-label text-white fw-semibold">
                            <i class="fa-solid fa-calendar me-1"></i> {{ __('admin.quick_period') }}
                        </label>
                        <select name="period" id="period" class="form-select" onchange="this.form.submit()">
                            <option value="today" {{ $period == 'today' ? 'selected' : '' }}>{{ __('admin.today') }}</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>{{ __('admin.this_week') }}
                            </option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>{{ __('admin.this_month') }}
                            </option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>{{ __('admin.this_year') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="start_date" class="form-label text-white fw-semibold">
                            <i class="fa-solid fa-calendar-days me-1"></i> {{ __('admin.start_date') }}
                        </label>
                        <input type="date" class="form-select" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="end_date" class="form-label text-white fw-semibold">
                            <i class="fa-solid fa-calendar-days me-1"></i> {{ __('admin.end_date') }}
                        </label>
                        <input type="date" class="form-select" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <button type="submit" class="btn btn-light w-100 fw-semibold">
                            <i class="fa-solid fa-filter me-1"></i> {{ __('admin.apply_filter') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ __('admin.total_valuation') }}</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($summary['total_valuation'], 0, ',', '.') }}
                                </h3>
                                <small class="opacity-75">{{ __('admin.based_on_cost') }}</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ __('admin.total_items') }}</p>
                                <h3 class="fw-bold mb-0">{{ number_format($summary['total_items']) }}</h3>
                                <small class="opacity-75">{{ __('admin.units_in_stock') }}</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #c17a5c 0%, #a18072 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ __('admin.low_stock_alerts') }}
                                </p>
                                <h3 class="fw-bold mb-0">{{ number_format($summary['low_stock_count']) }}</h3>
                                <small class="opacity-75">{{ __('admin.items_need_restocking') }}</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #0284c7 0%, #075985 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ __('admin.movements') }}</p>
                                <div class="mt-2">
                                    <span class="badge" style="background: rgba(255, 255, 255, 0.2); padding: 6px 10px;">
                                        <i class="fa-solid fa-arrow-down me-1"></i> {{ $summary['movements_in'] }} IN
                                    </span>
                                    <span class="badge ms-2"
                                        style="background: rgba(255, 255, 255, 0.2); padding: 6px 10px;">
                                        <i class="fa-solid fa-arrow-up me-1"></i> {{ $summary['movements_out'] }} OUT
                                    </span>
                                </div>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-arrows-rotate"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Sections -->
        <div class="row g-4 mb-4">
            <!-- Top Movers -->
            <div class="col-lg-6">
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                            <i class="fa-solid fa-trophy me-2"></i>{{ __('admin.top_moving_items') }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">
                                            {{ __('admin.product_management') }}</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                            {{ __('admin.movements') }}</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                            {{ __('admin.quantity') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topMovers as $index => $mover)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3"
                                                        style="width: 30px; height: 30px; background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.85rem;">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold" style="color: #6f5849;">{{ $mover->product->name }}
                                                        </div>
                                                        <small class="text-muted">{{ $mover->product->barcode }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    style="background: #e0cec7; color: #6f5849;">{{ $mover->total_movements }}</span>
                                            </td>
                                            <td class="text-center fw-bold" style="color: #85695a;">{{ $mover->total_quantity }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-inbox"></i>
                                                </div>
                                                <p class="mb-0">{{ __('admin.no_movements_found') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="col-lg-6">
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ __('admin.low_stock_items') }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">
                                            {{ __('admin.product_management') }}</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                            {{ __('admin.min_stock') }}</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                            {{ __('admin.current_stock') }}</th>
                                        <th class="border-0 fw-semibold text-end" style="color: #6f5849;">
                                            {{ __('admin.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockItems as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-bold" style="color: #6f5849;">{{ $item->name }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    style="background: #e0cec7; color: #6f5849;">{{ $item->min_stock }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $item->current_stock }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.products.edit', $item->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa-solid fa-plus-circle me-1"></i> Restock
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <div style="font-size: 3rem; opacity: 0.3;"><i
                                                        class="fa-solid fa-circle-check"></i></div>
                                                <p class="mb-0">{{ __('admin.all_well_stocked') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Movements Table -->
        <div class="card shadow-sm mb-4" style="border-radius: 16px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                    <i class="fa-solid fa-arrows-rotate me-2"></i>{{ __('admin.recent_stock_movements') }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.date') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">
                                    {{ __('admin.product_management') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('admin.activity_type') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('admin.quantity') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.reference') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.user') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $movement)
                                <tr>
                                    <td class="text-muted">{{ $movement->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="fw-bold" style="color: #6f5849;">{{ $movement->product->name }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if($movement->type == 'in')
                                            <span class="badge bg-success">IN</span>
                                        @elseif($movement->type == 'out')
                                            <span class="badge bg-warning text-dark">OUT</span>
                                        @else
                                            <span class="badge" style="background: #e0cec7; color: #6f5849;">ADJ</span>
                                        @endif
                                    </td>
                                    <td
                                        class="text-center fw-bold {{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                                    </td>
                                    <td>
                                        <span class="badge"
                                            style="background: #e0cec7; color: #6f5849;">{{ $movement->reference ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $movement->user->name ?? 'System' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-inbox"></i></div>
                                        <p class="mb-0">{{ __('admin.no_movements_found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Audit Logs Section -->
        <div class="card shadow-sm mb-4" style="border-radius: 16px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                    <i class="fa-solid fa-clipboard-list me-2"></i>{{ __('admin.audit_log') }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.date') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.user') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('admin.action') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.entity') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.details') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td class="text-muted">{{ $log->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="fw-bold" style="color: #6f5849;">{{ $log->user->name ?? 'System' }}</div>
                                        <small class="text-muted">{{ $log->user->role->name ?? '' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge"
                                            style="background: #e0cec7; color: #6f5849;">{{ strtoupper($log->action) }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold" style="color: #85695a;">{{ $log->model_type }}</span>
                                        <span class="text-muted small">#{{ $log->model_id }}</span>
                                    </td>
                                    <td>
                                        <span class="small">{{ $log->notes }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-inbox"></i></div>
                                        <p class="mb-0">{{ __('admin.no_audit_logs') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection