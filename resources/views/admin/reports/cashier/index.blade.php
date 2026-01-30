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
                    <a href="{{ route('admin.reports') }}" class="btn btn-light me-3 shadow-sm" style="border-radius: 10px; padding: 0.5rem 0.75rem; border: 1px solid #dee2e6;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0" style="color: #6f5849;">
                        <i class="fa-solid fa-cash-register me-2"></i>{{ __('admin.cashier_reports_title') }}
                    </h2>
                </div>
                <p class="text-muted mb-0 ms-5 ps-4">{{ __('admin.cashier_reports_subtitle') }}</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.cashier.export', array_merge(request()->all(), ['format' => 'pdf', 'search' => $search, 'action' => $action])) }}"
                    class="btn btn-outline-brown shadow-sm" style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;">
                    <i class="fa-solid fa-file-pdf me-2"></i> {{ __('admin.download_pdf') }}
                </a>
                <a href="{{ route('admin.reports.cashier.export', array_merge(request()->all(), ['auto_print' => 'true', 'search' => $search, 'action' => $action])) }}"
                    target="_blank" class="btn btn-brown shadow-sm"
                    style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;">
                    <i class="fa-solid fa-print me-2"></i> {{ __('admin.print_report') }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route('admin.reports.cashier') }}" method="GET" class="row g-3 align-items-end">
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
                    <div class="col-lg-2 col-md-6">
                        <label for="search" class="form-label text-dark fw-semibold">
                            <i class="fa-solid fa-user me-1" style="color: #c17a5c;"></i> {{ __('common.user') }}
                        </label>
                        <input type="text" name="search" class="form-control" placeholder="NIS/Username/Nama" value="{{ $search }}">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="action" class="form-label text-dark fw-semibold">
                            <i class="fa-solid fa-clipboard-list me-1" style="color: #c17a5c;"></i> {{ __('admin.action') }}
                        </label>
                        <select name="action" class="form-select">
                            <option value="">-- {{ __('admin.all_actions') }} --</option>
                            @foreach($actions as $act)
                                <option value="{{ $act }}" {{ $action == $act ? 'selected' : '' }}>{{ $act }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-brown flex-grow-1 fw-bold" style="border-radius: 8px; padding: 0.6rem;">
                            <i class="fa-solid fa-filter me-1"></i> {{ __('admin.apply_filter') }}
                        </button>
                        @if($search || $action || request('start_date') || request('end_date'))
                            <a href="{{ route('admin.reports.cashier') }}" class="btn btn-outline-brown" style="border-radius: 8px; padding: 0.6rem;">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
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
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('admin.total_sales') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</h3>
                                <small class="text-muted">{{ number_format($summary['total_transactions']) }}
                                    {{ __('admin.transactions_count') }}</small>
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
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('admin.avg_transaction_label') }}
                                </p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp
                                    {{ number_format($summary['average_transaction'], 0, ',', '.') }}</h3>
                                <small class="text-muted">{{ __('admin.per_transaction') }}</small>
                            </div>
                            <div class="icon-box-premium bg-success-soft">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm accent-sienna">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('admin.cash_sales') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp {{ number_format($summary['cash_sales'], 0, ',', '.') }}</h3>
                                <small class="text-muted">{{ $summary['cash_count'] }} {{ __('admin.transactions_count') }}</small>
                            </div>
                            <div class="icon-box-premium bg-sienna-soft">
                                <i class="fa-solid fa-coins"></i>
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
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('admin.non_cash_sales') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp {{ number_format($summary['non_cash_sales'], 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">{{ $summary['non_cash_count'] }} {{ __('admin.transactions_count') }}</small>
                            </div>
                            <div class="icon-box-premium bg-info-soft">
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
                <div class="card shadow-sm">
                    <div class="card-header"
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
                                                            style="width: 30px; height: 30px; background: #6f5849; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.85rem;">
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
                <div class="card shadow-sm">
                    <div class="card-header"
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
        <div class="card shadow-sm mb-4" id="transactions-section">
            <div class="card-header" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
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
                                        @php
                                            $method = strtolower($transaction->payment_method);
                                        @endphp
                                        @if($method == 'tunai' || $method == 'cash')
                                            <span class="badge bg-success">{{ __('admin.cash') }}</span>
                                        @elseif($method == 'qris')
                                            <span class="badge bg-info text-white">QRIS</span>
                                        @elseif($method == 'transfer')
                                            <span class="badge bg-primary">Transfer</span>
                                        @elseif($method == 'debit')
                                            <span class="badge bg-warning text-dark">Debit</span>
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
                <div class="px-3 py-2 border-top bg-white d-flex justify-content-end" style="border-radius: 0 0 16px 16px;">
                    {{ $recentTransactions->fragment('transactions-section')->links('vendor.pagination.no-prevnext') }}
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
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">{{ __('admin.action') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.entity') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.details') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">{{ __('admin.action') }}</th>
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
                                        <span class="small">{{ Str::limit($log->notes, 50) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-brown shadow-sm" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" 
                                            data-id="{{ $log->id }}"
                                            data-user="{{ $log->user?->name ?? __('common.system') }}"
                                            data-role="{{ $log->user?->role->name ?? '' }}"
                                            data-nis="{{ $log->user?->nis ?? '-' }}"
                                            data-username="{{ $log->user?->username ?? '-' }}"
                                            data-action="{{ $log->action }}" 
                                            data-model="{{ $log->model_type }}"
                                            data-model-id="{{ $log->model_id }}"
                                            data-amount="{{ $log->amount ? 'Rp' . number_format($log->amount, 0, ',', '.') : '-' }}"
                                            data-method="{{ $log->payment_method ?? '-' }}" 
                                            data-ip="{{ $log->ip_address }}"
                                            data-mac="{{ $log->mac_address ?? 'Not Available' }}"
                                            data-device="{{ $log->device_name ?? 'Unknown Device' }}"
                                            data-agent="{{ $log->user_agent }}"
                                            data-date="{{ $log->created_at->format('d M Y H:i:s') }}"
                                            data-changes="{{ json_encode($log->changes ?? []) }}"
                                            data-notes="{{ $log->notes ?? '-' }}"
                                            style="border-radius: 8px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-inbox"></i></div>
                                        <p class="mb-0">{{ __('admin.no_audit_logs') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2 border-top bg-white d-flex justify-content-end" style="border-radius: 0 0 16px 16px;">
                    {{ $auditLogs->fragment('audit-section')->links('vendor.pagination.no-prevnext') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header text-white" style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title">{{ __('admin.audit_log_detail') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                   <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('common.date') }}</label>
                            <p id="detail-date" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('common.user') }}</label>
                            <p id="detail-user" class="mb-0 fw-bold"></p>
                            <p id="detail-role" class="mb-0 text-muted small"></p>
                        </div>
                    </div>
                    <div class="row mb-3" id="cashier-info-row" style="display: none;">
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="fa-solid fa-id-card me-1"></i>NIS</label>
                            <p id="detail-nis" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="fa-solid fa-user me-1"></i>Username</label>
                            <p id="detail-username" class="mb-0 fw-bold"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('common.action') }}</label>
                            <p id="detail-action" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.model_type') }}</label>
                            <p id="detail-model" class="mb-0 fw-bold"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.model_id') }}</label>
                            <p id="detail-model-id" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('common.amount') }}</label>
                            <p id="detail-amount" class="mb-0 fw-bold"></p>
                        </div>
                    </div>
                    <hr>
                    <h6 class="text-muted mb-3"><i class="fa-solid fa-network-wired me-2"></i>Device Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="fa-solid fa-globe me-1"></i>IP Address</label>
                            <p id="detail-ip" class="mb-0 fw-bold"><code></code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="fa-solid fa-ethernet me-1"></i>MAC Address</label>
                            <p id="detail-mac" class="mb-0 fw-bold"><code></code></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small"><i class="fa-solid fa-desktop me-1"></i>Device Name</label>
                        <p id="detail-device" class="mb-0 fw-bold"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small"><i class="fa-solid fa-browser me-1"></i>User Agent</label>
                        <p id="detail-agent" class="mb-0 fw-bold" style="font-size: 0.75rem; word-break: break-all;"></p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="text-muted small"><i class="fa-solid fa-sticky-note me-1"></i>Notes</label>
                        <p id="detail-notes" class="mb-0 fw-bold"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small"><i class="fa-solid fa-code me-1"></i>Data Changes</label>
                        <pre id="detail-changes" class="bg-light p-2"
                            style="border-radius: 6px; max-height: 300px; overflow-y: auto;"></pre>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-brown px-4" style="border-radius: 10px;" data-bs-dismiss="modal">{{ __('common.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = document.getElementById('detailModal');
            if (detailModal) {
                detailModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const modal = this;

                    // Get data from attributes
                    const date = button.getAttribute('data-date');
                    const user = button.getAttribute('data-user');
                    const role = button.getAttribute('data-role');
                    const nis = button.getAttribute('data-nis');
                    const username = button.getAttribute('data-username');
                    const action = button.getAttribute('data-action');
                    const model = button.getAttribute('data-model');
                    const modelId = button.getAttribute('data-model-id');
                    const amount = button.getAttribute('data-amount');
                    const ip = button.getAttribute('data-ip');
                    const mac = button.getAttribute('data-mac');
                    const device = button.getAttribute('data-device');
                    const agent = button.getAttribute('data-agent');
                    const notes = button.getAttribute('data-notes');
                    const changesRaw = button.getAttribute('data-changes');
                    let changes = {};
                    try {
                        changes = JSON.parse(changesRaw || '{}');
                    } catch (e) {
                        console.error('Error parsing changes', e);
                    }

                    // Populate modal
                    modal.querySelector('#detail-date').textContent = date;
                    modal.querySelector('#detail-user').textContent = user;
                    modal.querySelector('#detail-role').textContent = role;
                    
                    const cashierInfoRow = modal.querySelector('#cashier-info-row');
                    if (role && role.toLowerCase() === 'cashier') {
                        cashierInfoRow.style.display = 'flex';
                        modal.querySelector('#detail-nis').textContent = nis;
                        modal.querySelector('#detail-username').textContent = username;
                    } else {
                        cashierInfoRow.style.display = 'none';
                    }
                    
                    modal.querySelector('#detail-action').innerHTML = `<span class="badge" style="background: #e0cec7; color: #6f5849;">${action}</span>`;
                    modal.querySelector('#detail-model').textContent = model;
                    modal.querySelector('#detail-model-id').textContent = modelId || '-';
                    modal.querySelector('#detail-amount').textContent = amount;
                    modal.querySelector('#detail-ip').innerHTML = `<code>${ip}</code>`;
                    modal.querySelector('#detail-mac').innerHTML = `<code>${mac}</code>`;
                    modal.querySelector('#detail-device').textContent = device;
                    modal.querySelector('#detail-agent').textContent = agent;
                    modal.querySelector('#detail-notes').textContent = notes;
                    modal.querySelector('#detail-changes').textContent = JSON.stringify(changes, null, 2);
                });
            }
        });
    </script>
@endsection