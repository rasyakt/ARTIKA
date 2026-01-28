@extends('layouts.app')

@section('content')
    <style>
        .table-hover tbody tr:hover {
            background-color: #fdf8f6;
        }

        html {
            scroll-behavior: auto !important;
        }
    </style>

        <div class="container-fluid py-4">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <a href="{{ route('admin.reports') }}" class="btn btn-light me-3 shadow-sm"
                            style="border-radius: 10px; padding: 0.5rem 0.75rem; border: 1px solid #dee2e6;">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h2 class="fw-bold mb-0" style="color: #6f5849;">
                            <i class="fa-solid fa-warehouse me-2"></i>{{ __('admin.warehouse_reports_title') }}
                        </h2>
                    </div>
                    <p class="text-muted mb-0 ms-5 ps-4">{{ __('admin.warehouse_reports_subtitle') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.reports.warehouse.export', array_merge(request()->all(), ['format' => 'pdf'])) }}"
                        class="btn btn-outline-brown shadow-sm" style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;">
                        <i class="fa-solid fa-file-pdf me-2"></i> {{ __('admin.download_pdf') }}
                    </a>
                    <a href="{{ route('admin.reports.warehouse.export', array_merge(request()->all(), ['auto_print' => 'true'])) }}"
                        target="_blank" class="btn btn-brown shadow-sm"
                        style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;">
                        <i class="fa-solid fa-print me-2"></i> {{ __('admin.print_report') }}
                    </a>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('admin.reports.warehouse') }}" method="GET" class="row g-3 align-items-end">
                        <!-- Date Row -->
                        <div class="col-lg-2 col-md-4">
                            <label for="period" class="form-label text-dark fw-semibold">
                                <i class="fa-solid fa-calendar me-1" style="color: #c17a5c;"></i> {{ __('admin.quick_period') }}
                            </label>
                            <select name="period" id="period" class="form-select" onchange="this.form.submit()">
                                <option value="today" {{ $period == 'today' ? 'selected' : '' }}>{{ __('admin.today') }}</option>
                                <option value="week" {{ $period == 'week' ? 'selected' : '' }}>{{ __('admin.this_week') }}</option>
                                <option value="month" {{ $period == 'month' ? 'selected' : '' }}>{{ __('admin.this_month') }}</option>
                                <option value="year" {{ $period == 'year' ? 'selected' : '' }}>{{ __('admin.this_year') }}</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label for="start_date" class="form-label text-dark fw-semibold">
                                <i class="fa-solid fa-calendar-days me-1" style="color: #c17a5c;"></i> {{ __('admin.start_date') }}
                            </label>
                            <input type="date" class="form-select" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label for="end_date" class="form-label text-dark fw-semibold">
                                <i class="fa-solid fa-calendar-days me-1" style="color: #c17a5c;"></i> {{ __('admin.end_date') }}
                            </label>
                            <input type="date" class="form-select" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                        </div>

                        <!-- Info Row -->
                        <div class="col-lg-2 col-md-4">
                            <label for="category_id" class="form-label text-dark fw-semibold">
                                <i class="fa-solid fa-list me-1" style="color: #c17a5c;"></i> {{ __('common.category') }}
                            </label>
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">-- {{ __('admin.all_categories') }} --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $categoryId == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <label for="stock_status" class="form-label text-dark fw-semibold">
                                <i class="fa-solid fa-layer-group me-1" style="color: #c17a5c;"></i> {{ __('admin.stock_status') }}
                            </label>
                            <select name="stock_status" id="stock_status" class="form-select">
                                <option value="">-- {{ __('admin.all_status') }} --</option>
                                <option value="low" {{ $stockStatus == 'low' ? 'selected' : '' }}>
                                    {{ __('admin.low_stock') }}</option>
                                <option value="out" {{ $stockStatus == 'out' ? 'selected' : '' }}>
                                    {{ __('admin.out_of_stock') }}</option>
                                <option value="available" {{ $stockStatus == 'available' ? 'selected' : '' }}>
                                    {{ __('admin.available') }}</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-brown flex-grow-1 fw-bold"
                                style="border-radius: 8px; padding: 0.6rem;">
                                <i class="fa-solid fa-filter me-1"></i> {{ __('admin.apply_filter') }}
                            </button>
                            @if ($categoryId || $stockStatus || $search || request('start_date'))
                                <a href="{{ route('admin.reports.warehouse') }}" class="btn btn-outline-brown"
                                    style="border-radius: 8px; padding: 0.6rem;">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            @endif
                        </div>

                        <!-- Search Row -->
                        <div class="col-12 mt-3">
                            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <span class="input-group-text bg-white border-0">
                                    <i class="fa-solid fa-magnifying-glass" style="color: #c17a5c;"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-0"
                                    placeholder="{{ __('admin.search_placeholder') }}" value="{{ $search }}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm accent-brown">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-2 text-muted text-uppercase"
                                        style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                        {{ __('admin.total_valuation') }}</p>
                                    <h4 class="fw-bold mb-0" style="color: #4b382f; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        Rp {{ number_format($summary['total_valuation'], 0, ',', '.') }}
                                    </h4>
                                    <small class="text-muted">{{ __('admin.based_on_cost') }}</small>
                                </div>
                                <div class="icon-box-premium bg-brown-soft">
                                    <i class="fa-solid fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm accent-success">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-2 text-muted text-uppercase"
                                        style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                        {{ __('admin.total_items') }}</p>
                                    <h4 class="fw-bold mb-0" style="color: #4b382f;">{{ number_format($summary['total_items']) }}</h4>
                                    <small class="text-muted">{{ __('admin.units_in_stock') }}</small>
                                </div>
                                <div class="icon-box-premium bg-success-soft">
                                    <i class="fa-solid fa-box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm accent-danger">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1 me-3">
                                    <p class="mb-2 text-muted text-uppercase"
                                        style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                        {{ __('admin.low_stock_alerts') }}
                                    </p>
                                    <h4 class="fw-bold mb-0" style="color: #4b382f;">{{ number_format($summary['low_stock_count']) }}</h4>
                                    <small class="text-muted">{{ __('admin.items_need_restocking') }}</small>
                                </div>
                                <div class="icon-box-premium bg-danger-soft">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card shadow-sm accent-info">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-2 text-muted text-uppercase"
                                        style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                        {{ __('admin.movements') }}</p>
                                    <div class="mt-2">
                                        <span class="badge" style="background: var(--color-primary-light); color: white; padding: 6px 10px;">
                                            <i class="fa-solid fa-arrow-down me-1"></i> {{ $summary['movements_in'] }} IN
                                        </span>
                                        <span class="badge ms-2"
                                            style="background: var(--color-primary-light); color: white; padding: 6px 10px;">
                                            <i class="fa-solid fa-arrow-up me-1"></i> {{ $summary['movements_out'] }} OUT
                                        </span>
                                        <span class="badge ms-2"
                                            style="background: var(--color-primary-light); color: white; padding: 6px 10px;">
                                            <i class="fa-solid fa-gear me-1"></i> {{ $summary['movements_adjustment'] }} ADJ
                                        </span>
                                    </div>
                                </div>
                                <div class="icon-box-premium bg-info-soft">
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
                    <div class="card shadow-sm">
                        <div class="card-header"
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
                                                            style="width: 30px; height: 30px; background: #6f5849; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.85rem;">
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
                <div class="col-lg-6" id="low-stock-section">
                    <div class="card shadow-sm">
                        <div class="card-header"
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
                                                        class="btn btn-outline-primary" style="border-radius: 8px; padding: 0.25rem 0.75rem; font-size: 0.85rem;">
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
                        <div class="px-3 py-2 border-top d-flex justify-content-end" style="border-radius: 0 0 16px 16px;">
                            {{ $lowStockItems->fragment('low-stock-section')->links('vendor.pagination.no-prevnext') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Movements Table -->
            <div class="card shadow-sm mb-4" id="movements-section">
                <div class="card-header" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
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
                    <div class="px-3 py-2 border-top bg-white d-flex justify-content-end" style="border-radius: 0 0 16px 16px;">
                        {{ $movements->fragment('movements-section')->links('vendor.pagination.no-prevnext') }}
                    </div>
                </div>
            </div>

            <!-- Audit Logs Section -->
            <div class="card shadow-sm mb-4" id="audit-section">
                <div class="card-header" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
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
                <div class="px-3 py-2 border-top bg-white d-flex justify-content-end" style="border-radius: 0 0 16px 16px;">
                    {{ $auditLogs->fragment('audit-section')->links('vendor.pagination.no-prevnext') }}
                </div>
            </div>
        </div>
@endsection