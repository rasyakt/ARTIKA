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
                    <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary me-3" style="border-radius: 10px;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0" style="color: #6f5849;">
                        <i class="fa-solid fa-cash-register me-2"></i>{{ __('admin.cashier_reports_title') }}
                    </h2>
                </div>
                <p class="text-muted mb-0 ms-5 ps-4">{{ __('admin.cashier_reports_subtitle') }}</p>
            </div>
            <div class="text-end">
                <a href="{{ route('admin.reports.cashier.export', request()->all()) }}" target="_blank"
                    class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-file-pdf me-1"></i> {{ __('admin.export_report') }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4"
            style="border-radius: 16px; border: none; background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);">
            <div class="card-body p-4">
                <form action="{{ route('admin.reports.cashier') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label for="period" class="form-label text-white fw-semibold">
                            <i class="fa-solid fa-calendar me-1"></i> {{ __('admin.quick_period') }}
                        </label>
                        <select name="period" id="period" class="form-select" onchange="this.form.submit()">
                            <option value="today" {{ $period == 'today' ? 'selected' : '' }}>{{ __('admin.today') }}</option>
                            <option value="week" {{ $period == 'week' ? 'selected' : '' }}>{{ __('admin.this_week') }}</option>
                            <option value="month" {{ $period == 'month' ? 'selected' : '' }}>{{ __('admin.this_month') }}</option>
                            <option value="year" {{ $period == 'year' ? 'selected' : '' }}>{{ __('admin.this_year') }}</option>
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
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('admin.total_sales') }}</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</h3>
                                <small class="opacity-75">{{ number_format($summary['total_transactions']) }}
                                    {{ __('admin.transactions_count') }}</small>
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
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('admin.avg_transaction_label') }}
                                </p>
                                <h3 class="fw-bold mb-0">Rp
                                    {{ number_format($summary['average_transaction'], 0, ',', '.') }}</h3>
                                <small class="opacity-75">{{ __('admin.per_transaction') }}</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-chart-line"></i>
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
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('admin.cash_sales') }}</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($summary['cash_sales'], 0, ',', '.') }}</h3>
                                <small class="opacity-75">{{ $summary['cash_count'] }} {{ __('admin.transactions_count') }}</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-coins"></i>
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
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('admin.non_cash_sales') }}</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($summary['non_cash_sales'], 0, ',', '.') }}
                                </h3>
                                <small class="opacity-75">{{ $summary['non_cash_count'] }} {{ __('admin.transactions_count') }}</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Sections -->
        <div class="row g-4 mb-4">
            <!-- Top Products -->
            <div class="col-lg-6">
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                            <i class="fa-solid fa-trophy me-2"></i>{{ __('admin.top_selling_products') }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.product_management') }}</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">{{ __('admin.sold') }}</th>
                                        <th class="border-0 fw-semibold text-end" style="color: #6f5849;">{{ __('admin.revenue') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $index => $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3"
                                                        style="width: 30px; height: 30px; background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.85rem;">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold" style="color: #6f5849;">{{ $product->name }}</div>
                                                        <small class="text-muted">{{ $product->barcode }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    style="background: #e0cec7; color: #6f5849;">{{ $product->total_sold }}</span>
                                            </td>
                                            <td class="text-end fw-bold" style="color: #c17a5c;">Rp
                                                {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-inbox"></i>
                                                </div>
                                                <p class="mb-0">{{ __('admin.no_sales_data') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cashier Performance -->
            <div class="col-lg-6">
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                            <i class="fa-solid fa-users me-2"></i>{{ __('admin.cashier_performance') }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.cashier') }}</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">{{ __('admin.transactions') }}
                                        </th>
                                        <th class="border-0 fw-semibold text-end" style="color: #6f5849;">{{ __('admin.total_sales') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cashierPerformance as $performance)
                                        <tr>
                                            <td>
                                                <div class="fw-bold" style="color: #6f5849;">{{ $performance->user->name }}
                                                </div>
                                                <small class="text-muted">{{ $performance->user->role->name }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    style="background: #e0cec7; color: #6f5849;">{{ $performance->transaction_count }}</span>
                                            </td>
                                            <td class="text-end fw-bold" style="color: #85695a;">Rp
                                                {{ number_format($performance->total_sales, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
                                                <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-inbox"></i>
                                                </div>
                                                <p class="mb-0">{{ __('admin.no_data_available') }}</p>
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

        <!-- Recent Transactions Table -->
        <div class="card shadow-sm mb-4" style="border-radius: 16px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                    <i class="fa-solid fa-receipt me-2"></i>{{ __('admin.recent_transactions') }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.invoice') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.date') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.cashier') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">{{ __('admin.payment_method') }}</th>
                                <th class="border-0 fw-semibold text-end" style="color: #6f5849;">{{ __('admin.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td>
                                        <div class="fw-bold" style="color: #85695a;">{{ $transaction->invoice_no }}</div>
                                    </td>
                                    <td class="text-muted">{{ $transaction->created_at->format('d M Y H:i') }}</td>
                                    <td>{{ $transaction->user->name }}</td>
                                    <td class="text-center">
                                        @if(strtolower($transaction->payment_method) == 'tunai' || strtolower($transaction->payment_method) == 'cash')
                                            <span class="badge bg-success">{{ __('admin.cash') }}</span>
                                        @else
                                            <span class="badge" style="background: #0284c7; color: white;">{{ __('admin.non_cash') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold" style="color: #c17a5c;">Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-inbox"></i></div>
                                        <p class="mb-0">{{ __('admin.no_transactions_found') }}</p>
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
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">{{ __('admin.action') }}</th>
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