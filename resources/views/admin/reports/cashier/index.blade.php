@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp
@extends('layouts.app')

@section('content')
    <style>
        .table-hover tbody tr:hover {
            background-color: #fdf8f6;
        }

        html {
            scroll-behavior: auto !important;
        }

        .opacity-50 {
            opacity: 0.5;
        }

        .grayscale {
            filter: grayscale(1);
        }

        .text-decoration-line-through {
            text-decoration: line-through;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="d-flex align-items-center mb-1">

                    <a href="{{ route($routePrefix . 'reports') }}" class="btn btn-light me-3 shadow-sm"
                        style="border-radius: 10px; padding: 0.5rem 0.75rem; border: 1px solid #dee2e6;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="fw-bold mb-0" style="color: #6f5849;">
                        <i class="fa-solid fa-cash-register me-2"></i>{{ __('admin.cashier_reports_title') }}
                    </h2>
                </div>
                <p class="text-muted mb-0 ms-5 ps-4">{{ __('admin.cashier_reports_subtitle') }}</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-brown shadow-sm"
                    style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;" data-bs-toggle="modal"
                    data-bs-target="#exportPdfModal">
                    <i class="fa-solid fa-file-pdf me-2"></i> {{ __('admin.download_pdf') }}
                </button>
                <a href="{{ route($routePrefix . 'reports.cashier.export', array_merge(request()->all(), ['format' => 'csv', 'search' => $search, 'action' => $action])) }}"
                    class="btn btn-brown shadow-sm" style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;">
                    <i class="fa-solid fa-file-csv me-2"></i> {{ __('admin.export_csv') ?? 'Export CSV' }}
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-4">
                <form action="{{ route($routePrefix . 'reports.cashier') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label for="period" class="form-label text-dark fw-semibold">
                            <i class="fa-solid fa-calendar me-1" style="color: #c17a5c;"></i> {{ __('admin.quick_period') }}
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
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label for="start_date" class="form-label text-dark fw-semibold">
                            <i class="fa-solid fa-calendar-days me-1" style="color: #c17a5c;"></i>
                            {{ __('admin.start_date') }}
                        </label>
                        <input type="date" class="form-select" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <label for="end_date" class="form-label text-dark fw-semibold">
                            <i class="fa-solid fa-calendar-days me-1" style="color: #c17a5c;"></i>
                            {{ __('admin.end_date') }}
                        </label>
                        <input type="date" class="form-select" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6">
                        <label for="search" class="form-label text-dark fw-semibold">
                            <i class="fa-solid fa-user me-1" style="color: #c17a5c;"></i> {{ __('common.user') }}
                        </label>
                        <input type="text" name="search" class="form-control" placeholder="NIS/Username/Nama"
                            value="{{ $search }}">
                    </div>
                    <div class="col-xl-2 col-lg-6 col-md-6">
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
                    <div class="col-xl-2 col-lg-12 col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-brown flex-grow-1 fw-bold"
                            style="border-radius: 8px; padding: 0.6rem;">
                            <i class="fa-solid fa-filter me-1"></i> {{ __('admin.apply_filter') }}
                        </button>
                        @if($search || $action || request('start_date') || request('end_date'))
                            <a href="{{ route($routePrefix . 'reports.cashier') }}" class="btn btn-outline-brown"
                                style="border-radius: 8px; padding: 0.6rem;">
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ __('admin.total_sales') }}
                                </p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp
                                    {{ number_format($summary['total_sales'], 0, ',', '.') }}
                                </h3>
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
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ __('admin.avg_transaction_label') }}
                                </p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp
                                    {{ number_format($summary['average_transaction'], 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">{{ __('admin.per_transaction') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ __('admin.cash_sales') }}
                                </p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp
                                    {{ number_format($summary['cash_sales'], 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">{{ $summary['cash_count'] }}
                                    {{ __('admin.transactions_count') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-coins"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">
                                    {{ __('admin.non_cash_sales') }}
                                </p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp
                                    {{ number_format($summary['non_cash_sales'], 0, ',', '.') }}
                                </h3>
                                <small class="text-muted">{{ $summary['non_cash_count'] }}
                                    {{ __('admin.transactions_count') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-credit-card"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Reports Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                <ul class="nav nav-pills nav-fill gap-2 p-1 bg-light rounded-pill mb-0" id="reportTabs" role="tablist"
                    style="border: 1px solid #f2e8e5;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill fw-bold" id="transactions-tab" data-bs-toggle="tab"
                            data-bs-target="#transactions-pane" type="button" role="tab">
                            <i class="fa-solid fa-receipt me-2"></i>{{ __('admin.recent_transactions') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold" id="products-tab" data-bs-toggle="tab"
                            data-bs-target="#products-pane" type="button" role="tab">
                            <i class="fa-solid fa-trophy me-2"></i>{{ __('admin.top_selling_products') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold" id="categories-tab" data-bs-toggle="tab"
                            data-bs-target="#categories-pane" type="button" role="tab">
                            <i class="fa-solid fa-tags me-2"></i>Kategori
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold" id="performance-tab" data-bs-toggle="tab"
                            data-bs-target="#performance-pane" type="button" role="tab">
                            <i class="fa-solid fa-users me-2"></i>{{ __('admin.cashier_performance') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold" id="payment-tab" data-bs-toggle="tab"
                            data-bs-target="#payment-pane" type="button" role="tab">
                            <i class="fa-solid fa-credit-card me-2"></i>Pembayaran
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill fw-bold" id="discount-tab" data-bs-toggle="tab"
                            data-bs-target="#discount-pane" type="button" role="tab">
                            <i class="fa-solid fa-percent me-2"></i>Diskon
                        </button>
                    </li>
                    @if(App\Models\Setting::get('admin_enable_audit_logs', true))
                        <li class="nav-item flex-sm-fill text-center" role="presentation">
                            <button class="nav-link w-100 fw-bold py-3" id="audit-tab" data-bs-toggle="tab"
                                data-bs-target="#audit-pane" type="button" role="tab" aria-selected="false">
                                <i class="fa-solid fa-clipboard-list me-2"></i>{{ __('admin.audit_log') }}
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content" id="reportTabsContent">
                    <!-- Transactions Tab -->
                    <div class="tab-pane fade show active" id="transactions-pane" role="tabpanel" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.invoice') }}
                                        </th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.date') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.cashier') }}
                                        </th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                            {{ __('admin.payment_method') }}
                                        </th>
                                        <th class="border-0 fw-semibold text-end" style="color: #6f5849;">
                                            {{ __('admin.amount') }}
                                        </th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                            {{ __('admin.action') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $transaction)
                                        <tr class="{{ $transaction->status === 'rolled_back' ? 'opacity-50 grayscale' : '' }}">
                                            <td>
                                                <div class="fw-bold {{ $transaction->status === 'rolled_back' ? 'text-decoration-line-through' : '' }}"
                                                    style="color: #85695a;">
                                                    {{ $transaction->invoice_no }}
                                                </div>
                                            </td>
                                            <td class="text-muted small">{{ $transaction->created_at->format('d M Y H:i') }}
                                            </td>
                                            <td>{{ $transaction->user->name }}</td>
                                            <td class="text-center">
                                                @php $method = strtolower($transaction->payment_method); @endphp
                                                @if($method == 'tunai' || $method == 'cash')
                                                    <span class="badge bg-success">{{ __('admin.cash') }}</span>
                                                @elseif($method == 'qris')
                                                    <span class="badge bg-info text-white">QRIS</span>
                                                @elseif($method == 'transfer')
                                                    <span class="badge bg-primary">Transfer</span>
                                                @elseif($method == 'debit')
                                                    <span class="badge bg-warning text-dark">Debit</span>
                                                @else
                                                    <span class="badge"
                                                        style="background: #0284c7; color: white;">{{ __('admin.non_cash') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end fw-bold" style="color: #c17a5c;">
                                                @if(\App\Models\Setting::get('cashier_enable_returns', true) && $transaction->total_refunded > 0)
                                                    <div class="small text-muted text-decoration-line-through">Rp
                                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                                    </div>
                                                    <div>Rp
                                                        {{ number_format($transaction->total_amount - $transaction->total_refunded, 0, ',', '.') }}
                                                    </div>
                                                    @if($transaction->status === 'returned')
                                                        <span class="badge bg-danger mt-1"
                                                            style="font-size: 0.65rem;">{{ strtoupper(__('admin.returned') ?? 'RETURNED') }}</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark mt-1"
                                                            style="font-size: 0.65rem;">{{ strtoupper(__('admin.partial_return') ?? 'PARTIAL RETURN') }}</span>
                                                    @endif
                                                @else
                                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light border shadow-sm" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                                        <li>
                                                            <h6 class="dropdown-header text-muted small text-uppercase fw-bold">
                                                                {{ __('admin.action') }}
                                                            </h6>
                                                        </li>
                                                        <li><button class="dropdown-item btn-view-transaction py-2"
                                                                data-id="{{ $transaction->id }}"><i
                                                                    class="fas fa-eye me-2 text-brown"></i> Detail</button></li>
                                                        <li><button class="dropdown-item btn-print-direct py-2"
                                                                data-id="{{ $transaction->id }}"><i
                                                                    class="fas fa-print me-2 text-secondary"></i> Cetak</button>
                                                        </li>
                                                        @if($transaction->status !== 'rolled_back' && strtolower($user?->role?->name ?? '') !== 'manager')
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><button class="dropdown-item btn-edit-transaction py-2 text-primary"
                                                                    data-id="{{ $transaction->id }}"
                                                                    data-method="{{ $transaction->payment_method }}"
                                                                    data-cash="{{ $transaction->cash_amount }}"><i
                                                                        class="fas fa-edit me-2"></i> Edit</button></li>
                                                        @endif
                                                        @if(strtolower($user?->role?->name ?? '') !== 'manager')
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><button type="button"
                                                                    class="dropdown-item py-2 text-danger btn-delete-tx"
                                                                    data-id="{{ $transaction->id }}"><i
                                                                        class="fas fa-trash me-2"></i> Hapus</button></li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5"><i
                                                    class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i><br>{{ __('admin.no_transactions_found') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-3 py-2 border-top bg-white d-flex justify-content-end"
                            style="border-radius: 0 0 16px 16px;">
                            {{ $recentTransactions->fragment('transactions-pane')->links('vendor.pagination.custom-brown') }}
                        </div>
                    </div>

                    <!-- Top Products Tab -->
                    <div class="tab-pane fade" id="products-pane" role="tabpanel" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Produk</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                            {{ __('admin.sold') }}
                                        </th>
                                        <th class="border-0 fw-semibold text-end" style="color: #6f5849;">
                                            {{ __('admin.revenue') }}
                                        </th>
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
                                            <td class="text-center"><span class="badge"
                                                    style="background: #e0cec7; color: #6f5849;">{{ $product->total_sold }}</span>
                                            </td>
                                            <td class="text-end fw-bold" style="color: #c17a5c;">Rp
                                                {{ number_format($product->total_revenue, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-5"><i
                                                    class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i><br>{{ __('admin.no_sales_data') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Category Tab -->
                    <div class="tab-pane fade" id="categories-pane" role="tabpanel" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Kategori</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">Item Terjual
                                        </th>
                                        <th class="border-0 fw-semibold text-end" style="color: #6f5849;">Total Pendapatan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categorySales as $cat)
                                        <tr>
                                            <td class="fw-bold" style="color: #6f5849;">{{ $cat->name }}</td>
                                            <td class="text-center"><span class="badge"
                                                    style="background: #e0cec7; color: #6f5849;">{{ $cat->total_sold }}</span>
                                            </td>
                                            <td class="text-end fw-bold" style="color: #c17a5c;">Rp
                                                {{ number_format($cat->total_revenue, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-5"><i
                                                    class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i><br>Tidak ada data
                                                kategori</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Cashier Tab -->
                    <div class="tab-pane fade" id="performance-pane" role="tabpanel" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.cashier') }}
                                        </th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">Transaksi</th>
                                        <th class="border-0 fw-semibold text-end" style="color: #6f5849;">
                                            {{ __('admin.total_sales') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($cashierPerformance as $p)
                                        <tr>
                                            <td>
                                                <div class="fw-bold" style="color: #6f5849;">{{ $p->user->name }}</div>
                                                <small class="text-muted">{{ $p->user->role->name }}</small>
                                            </td>
                                            <td class="text-center"><span class="badge"
                                                    style="background: #e0cec7; color: #6f5849;">{{ $p->transaction_count }}</span>
                                            </td>
                                            <td class="text-end fw-bold" style="color: #85695a;">Rp
                                                {{ number_format($p->total_sales, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-5"><i
                                                    class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i><br>{{ __('admin.no_data_available') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Payment Method Tab -->
                    <div class="tab-pane fade" id="payment-pane" role="tabpanel" tabindex="0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Metode Pembayaran</th>
                                        <th class="border-0 fw-semibold text-center" style="color: #6f5849;">Jumlah
                                            Transaksi</th>
                                        <th class="border-0 fw-semibold text-end" style="color: #6f5849;">Total Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($paymentBreakdown as $pay)
                                        <tr>
                                            <td class="fw-bold text-brown">{{ $pay->payment_method }}</td>
                                            <td class="text-center">{{ $pay->count }}</td>
                                            <td class="text-end fw-bold" style="color: #c17a5c;">Rp
                                                {{ number_format($pay->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-5"><i
                                                    class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i><br>Tidak ada data
                                                pembayaran</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Discount Tab -->
                    <div class="tab-pane fade" id="discount-pane" role="tabpanel" tabindex="0">
                        <div class="p-4 text-center">
                            <div class="row g-4 justify-content-center">
                                <div class="col-md-4">
                                    <div class="p-4 rounded-16" style="background: #fef2f2; border: 1px solid #fee2e2;">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Transaksi Berdiskon</div>
                                        <h2 class="fw-bold mb-0 text-danger">{{ $discountSummary['count'] }}</h2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 rounded-16" style="background: #fdf8f6; border: 1px solid #f2e8e5;">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Total Potongan Harga</div>
                                        <h2 class="fw-bold mb-0" style="color: #6f5849;">Rp
                                            {{ number_format($discountSummary['total_discount'], 0, ',', '.') }}
                                        </h2>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-4 rounded-16" style="background: #f0fdf4; border: 1px solid #dcfce7;">
                                        <div class="text-muted small text-uppercase fw-bold mb-1">Total Setelah Diskon</div>
                                        <h2 class="fw-bold mb-0 text-success">Rp
                                            {{ number_format($discountSummary['total_revenue'], 0, ',', '.') }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(App\Models\Setting::get('admin_enable_audit_logs', true))
                        <!-- Audit Tab -->
                        <div class="tab-pane fade" id="audit-pane" role="tabpanel" tabindex="0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead style="background: #fdf8f6;">
                                        <tr>
                                            <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.date') }}</th>
                                            <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.user') }}</th>
                                            <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                                {{ __('admin.action') }}
                                            </th>
                                            <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.details') }}
                                            </th>
                                            <th class="border-0 fw-semibold text-center" style="color: #6f5849;">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($auditLogs as $log)
                                            <tr>
                                                <td class="text-muted small">{{ $log->created_at->format('d M Y H:i') }}</td>
                                                <td>
                                                    <div class="fw-bold" style="color: #6f5849;">{{ $log->user->name ?? 'System' }}
                                                    </div>
                                                    <small class="text-muted">{{ $log->user->role->name ?? '' }}</small>
                                                </td>
                                                <td class="text-center"><span class="badge"
                                                        style="background: #e0cec7; color: #6f5849;">{{ strtoupper($log->action) }}</span>
                                                </td>
                                                <td><span class="small">{{ Str::limit($log->notes, 40) }}</span></td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-brown rounded-circle"
                                                        data-bs-toggle="modal" data-bs-target="#detailModal"
                                                        data-id="{{ $log->id }}" data-user="{{ $log->user?->name ?? 'System' }}"
                                                        data-role="{{ $log->user?->role->name ?? '' }}"
                                                        data-nis="{{ $log->user?->nis ?? '-' }}"
                                                        data-username="{{ $log->user?->username ?? '-' }}"
                                                        data-action="{{ $log->action }}" data-model="{{ $log->model_type }}"
                                                        data-model-id="{{ $log->model_id }}"
                                                        data-amount="{{ $log->amount ? 'Rp' . number_format($log->amount, 0, ',', '.') : '-' }}"
                                                        data-method="{{ $log->payment_method ?? '-' }}"
                                                        data-ip="{{ $log->ip_address }}" data-mac="{{ $log->mac_address ?? '-' }}"
                                                        data-device="{{ $log->device_name ?? 'Unknown' }}"
                                                        data-agent="{{ $log->user_agent }}"
                                                        data-date="{{ $log->created_at->format('d M Y H:i:s') }}"
                                                        data-changes="{{ json_encode($log->changes ?? []) }}"
                                                        data-notes="{{ $log->notes ?? '-' }}">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-5"><i
                                                        class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i><br>{{ __('admin.no_audit_logs') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="px-3 py-2 border-top bg-white d-flex justify-content-end"
                                style="border-radius: 0 0 16px 16px;">
                                {{ $auditLogs->fragment('audit-pane')->links('vendor.pagination.custom-brown') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header text-white"
                    style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border-radius: 16px 16px 0 0;">
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
                    @if(\App\Models\Setting::get('cashier_enable_returns', true))
                        <button type="button" id="btn-initiate-return-audit" class="btn btn-outline-danger px-4"
                            style="border-radius: 10px; display: none;">
                            <i class="fa-solid fa-rotate-left me-2"></i>{{ __('admin.return_items') }}
                        </button>
                    @endif
                    <button type="button" class="btn btn-brown px-4" style="border-radius: 10px;"
                        data-bs-dismiss="modal">{{ __('common.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Detail Modal -->
    <div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-receipt me-2"></i>Detail Transaksi <span id="tx-invoice-no"
                            class="text-white-50 small"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Metadata Header -->
                    <div class="row mb-4 bg-light p-3 rounded-16 mx-0">
                        <div class="col-md-3">
                            <label class="text-muted small d-block">Kasir</label>
                            <span id="tx-cashier" class="fw-bold text-dark">-</span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block">Waktu</label>
                            <span id="tx-date" class="fw-bold text-dark">-</span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block">Metode</label>
                            <span id="tx-payment-method" class="badge bg-brown-soft text-brown fw-bold">-</span>
                        </div>
                        <div class="col-md-3">
                            <label class="text-muted small d-block">Status</label>
                            <span id="tx-status" class="badge">-</span>
                        </div>
                    </div>

                    <!-- Items Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-hover">
                            <thead>
                                <tr style="background: #fdf8f6;">
                                    <th class="border-0">Produk</th>
                                    <th class="border-0 text-center">Qty</th>
                                    <th class="border-0 text-center">Subtotal</th>
                                    <th class="border-0 text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tx-items-body">
                                <!-- Loaded via JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary -->
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Subtotal:</span>
                                <span id="tx-subtotal" class="fw-bold">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Diskon:</span>
                                <span id="tx-discount" class="fw-bold text-danger">- Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 border-top pt-1 mt-1">
                                <span class="fw-bold text-dark">TOTAL:</span>
                                <span id="tx-total" class="fw-bold text-primary fs-5">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1 mt-3">
                                <span class="text-muted small">Bayar:</span>
                                <span id="tx-cash-received" class="text-dark small">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted small">Kembalian:</span>
                                <span id="tx-change" class="text-dark small">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4">
                    @if(\App\Models\Setting::get('cashier_enable_returns', true))
                        <button type="button" class="btn btn-outline-danger px-4 me-auto d-none" id="btn-initiate-return-tx"
                            style="border-radius: 10px;">
                            <i class="fa-solid fa-rotate-left me-2"></i>{{ __('admin.return_items') }}
                        </button>
                    @endif
                    <button type="button" class="btn btn-outline-brown px-4" id="btn-print-receipt"
                        style="border-radius: 10px;">
                        <i class="fa-solid fa-print me-2"></i>Cetak Struk
                    </button>
                    <button type="button" class="btn btn-brown px-4" style="border-radius: 10px;"
                        data-bs-dismiss="modal">{{ __('admin.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Edit Modal -->
    <div class="modal fade" id="transactionEditModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
                <form id="tx-edit-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Edit Transaksi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="payment_method" id="tx-edit-method" class="form-select">
                                <option value="Cash">Tunai</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Debit">Debit</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Uang Diterima</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="cash_amount" id="tx-edit-cash" class="form-control" min="0"
                                    step="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light px-4" style="border-radius: 10px;"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-brown px-4" style="border-radius: 10px;">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Return Process Modal -->
    <div class="modal fade" id="returnProcessModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
                <form id="return-process-form" action="{{ route('admin.returns.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="transaction_id" id="return-tx-id">
                    <div class="modal-header border-0 pb-0 pt-4 px-4">
                        <h5 class="modal-title fw-bold">
                            <i class="fa-solid fa-rotate-left me-2"></i>{{ __('admin.process_return') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-muted small mb-4">{{ __('admin.select_items_to_return') }}</p>

                        <div class="table-responsive mb-4">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="text-muted small">
                                        <th class="border-0">Produk</th>
                                        <th class="border-0 text-center">Terjual</th>
                                        <th class="border-0 text-center" style="width: 150px;">
                                            {{ __('admin.return_quantity') }}
                                        </th>
                                        <th class="border-0 text-end">Refund Est.</th>
                                    </tr>
                                </thead>
                                <tbody id="return-items-body">
                                    <!-- Populated via JS -->
                                </tbody>
                            </table>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-brown">{{ __('admin.return_reason') }}*</label>
                            <textarea name="reason" class="form-control" rows="2" required
                                placeholder="Contoh: Barang cacat, Salah beli..." style="border-radius: 12px;"></textarea>
                        </div>

                        <div class="p-3 bg-brown-soft rounded-16">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-brown">Total Pengembalian Dana (Refund)</span>
                                <h4 class="fw-bold text-brown mb-0">Rp <span id="return-total-refund">0</span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-outline-brown px-4" style="border-radius: 10px;"
                            data-bs-toggle="modal"
                            data-bs-target="#transactionDetailModal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-danger px-4" style="border-radius: 10px;">
                            {{ __('admin.process_return') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- PDF Customization Modal -->
    <div class="modal fade" id="exportPdfModal" tabindex="-1" aria-labelledby="exportPdfModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0" style="border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="exportPdfModalLabel">
                        <i
                            class="fa-solid fa-file-settings me-2"></i>{{ __('admin.pdf_report_customization') ?? 'Kustomisasi Laporan PDF' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route($routePrefix . 'reports.cashier.export') }}" method="GET">
                    <input type="hidden" name="format" value="pdf">
                    <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    <input type="hidden" name="search" value="{{ $search }}">
                    <input type="hidden" name="action" value="{{ $action }}">

                    <div class="modal-body py-4">
                        <p class="text-muted mb-4 small">
                            {{ __('admin.select_report_sections') ?? 'Pilih bagian laporan yang ingin ditampilkan dalam dokumen PDF:' }}
                        </p>

                        <div class="list-group list-group-flush border rounded-12">
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]" value="summary"
                                    checked>
                                <div>
                                    <div class="fw-bold">Ringkasan Utama</div>
                                    <small class="text-muted small">Total penjualan, Transaksi, Rata-rata, dll.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]"
                                    value="payment_methods" checked>
                                <div>
                                    <div class="fw-bold">Metode Pembayaran</div>
                                    <small class="text-muted small">Breakdown Tunai, QRIS, Transfer, dll.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]" value="top_products"
                                    checked>
                                <div>
                                    <div class="fw-bold">Produk Terlaris</div>
                                    <small class="text-muted small">Daftar produk dengan penjualan tertinggi.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]"
                                    value="category_sales" checked>
                                <div>
                                    <div class="fw-bold">Penjualan per Kategori</div>
                                    <small class="text-muted small">Statistik penjualan berdasarkan kategori produk.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]"
                                    value="cashier_performance" checked>
                                <div>
                                    <div class="fw-bold">Performa Kasir</div>
                                    <small class="text-muted small">Statistik penjualan untuk setiap staff kasir.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]"
                                    value="discount_summary" checked>
                                <div>
                                    <div class="fw-bold">Laporan Diskon</div>
                                    <small class="text-muted small">Ringkasan potongan harga yang diberikan.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]"
                                    value="recent_transactions" checked>
                                <div>
                                    <div class="fw-bold">Transaksi Terbaru</div>
                                    <small class="text-muted small">Daftar 50 transaksi terakhir pada periode ini.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]" value="audit_logs">
                                <div>
                                    <div class="fw-bold">Log Aktivitas Kasir</div>
                                    <small class="text-muted small">Catatan audit log untuk periode terpilih.</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0 pb-4 justify-content-center">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-brown px-4" style="border-radius: 10px;">
                            <i class="fa-solid fa-download me-2"></i>Generate PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tab Persistence logic
            const hash = window.location.hash;
            if (hash) {
                const tabTriggerEl = document.querySelector(`.nav-link[data-bs-target="${hash}"]`);
                if (tabTriggerEl) {
                    const tab = new bootstrap.Tab(tabTriggerEl);
                    tab.show();
                }
            }

            // Update hash on tab change
            const tabEls = document.querySelectorAll('button[data-bs-toggle="tab"]');
            tabEls.forEach(el => {
                el.addEventListener('shown.bs.tab', event => {
                    window.location.hash = event.target.getAttribute('data-bs-target');
                });
            });

            // Audit Log Modal Detail View
            const detailModal = document.getElementById('detailModal');
            if (detailModal) {
                detailModal.addEventListener('show.bs.modal', function (event) {
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

            // Transaction Detail View
            const transactionDetailModal = document.getElementById('transactionDetailModal');
            const returnProcessModal = document.getElementById('returnProcessModal');
            document.querySelectorAll('.btn-view-transaction').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const itemsBody = document.getElementById('tx-items-body');
                    const invoiceSpan = document.getElementById('tx-invoice-no');

                    // Loader
                    itemsBody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>';
                    bootstrap.Modal.getOrCreateInstance(transactionDetailModal).show();

                    fetch(`{{ route($routePrefix . 'reports.cashier.items', ['id' => ':id']) }}`.replace(':id', id))
                        .then(response => response.json())
                        .then(data => {
                            invoiceSpan.textContent = `#${data.invoice_no}`;
                            document.getElementById('tx-cashier').textContent = data.cashier_name;
                            document.getElementById('tx-date').textContent = data.date;
                            document.getElementById('tx-payment-method').textContent = data.payment_method;

                            // Status Badge
                            const statusBadge = document.getElementById('tx-status');
                            if (data.status === 'rolled_back') {
                                statusBadge.textContent = 'ROLLED BACK';
                                statusBadge.className = 'badge bg-danger py-2 w-100';
                            } else if (data.status === 'returned') {
                                statusBadge.textContent = 'RETURNED';
                                statusBadge.className = 'badge bg-danger py-2 w-100';
                            } else if (data.status === 'partial_return') {
                                statusBadge.textContent = 'PARTIAL RETURN';
                                statusBadge.className = 'badge bg-warning text-dark py-2 w-100';
                            } else {
                                statusBadge.textContent = 'COMPLETED';
                                statusBadge.className = 'badge bg-success py-2 w-100';
                            }

                            // Summary fields
                            const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);
                            document.getElementById('tx-subtotal').textContent = `Rp ${formatIDR(data.subtotal)}`;
                            document.getElementById('tx-discount').textContent = `- Rp ${formatIDR(data.discount)}`;

                            // Handling Refund display if any
                            const totalRow = document.getElementById('tx-total').closest('.row'); // Get the row container
                            if (data.total_refunded > 0) {
                                document.getElementById('tx-total').innerHTML = `<span class="text-decoration-line-through text-muted small">Rp ${formatIDR(data.total_amount)}</span><br>Rp ${formatIDR(data.total_amount - data.total_refunded)}`;
                                // Add a refund info if not exists
                                let refundSummary = document.getElementById('tx-refund-summary');
                                if (!refundSummary) {
                                    refundSummary = document.createElement('div');
                                    refundSummary.id = 'tx-refund-summary';
                                    refundSummary.className = 'd-flex justify-content-between align-items-center mt-2 p-2 bg-light rounded text-danger col-md-5 ms-auto';
                                    totalRow.parentNode.insertBefore(refundSummary, totalRow.nextSibling);
                                }
                                refundSummary.innerHTML = `<span>Refunded:</span><span class="fw-bold">- Rp ${formatIDR(data.total_refunded)}</span>`;
                                refundSummary.classList.remove('d-none');
                            } else {
                                document.getElementById('tx-total').textContent = `Rp ${formatIDR(data.total_amount)}`;
                                const refundSummary = document.getElementById('tx-refund-summary');
                                if (refundSummary) {
                                    refundSummary.classList.add('d-none');
                                }
                            }

                            document.getElementById('tx-cash-received').textContent = `Rp ${formatIDR(data.cash_amount)}`;
                            document.getElementById('tx-change').textContent = `Rp ${formatIDR(data.change_amount)}`;

                            // Return Button Logic
                            const btnReturn = document.getElementById('btn-initiate-return-tx');
                            if (data.status === 'completed' || data.status === 'partial_return') {
                                btnReturn.classList.remove('d-none');
                                btnReturn.onclick = function () {
                                    // Transition to Return Modal
                                    bootstrap.Modal.getOrCreateInstance(transactionDetailModal).hide();

                                    document.getElementById('return-tx-id').value = id;
                                    let returnItemsHtml = '';
                                    data.items.forEach((item, index) => {
                                        const qty = parseInt(item.quantity) || 0;
                                        const retQty = parseInt(item.returned_quantity) || 0;
                                        const availableToReturn = qty - retQty;
                                        returnItemsHtml += `
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <div class="fw-bold">${item.name}</div>
                                                                                                                        <div class="small text-muted">Rp ${formatIDR(item.price)} / unit</div>
                                                                                                                        ${retQty > 0 ? `<div class="badge bg-light text-brown border" style="font-size: 0.65rem;">${retQty} Terkumpul</div>` : ''}
                                                                                                                        <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                                                                                                                    </td>
                                                                                                                    <td class="text-center">
                                                                                                                        ${availableToReturn}
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <div class="input-group input-group-sm">
                                                                                                                            <button type="button" class="btn btn-outline-brown btn-qty" data-type="minus" ${availableToReturn <= 0 ? 'disabled' : ''}>-</button>
                                                                                                                            <input type="number" name="items[${index}][quantity]" class="form-control text-center input-qty" 
                                                                                                                                value="0" min="0" max="${availableToReturn}" data-price="${item.price}" readonly>
                                                                                                                            <button type="button" class="btn btn-outline-brown btn-qty" data-type="plus" ${availableToReturn <= 0 ? 'disabled' : ''}>+</button>
                                                                                                                            </div>
                                                                                                                    </td>
                                                                                                                    <td class="text-end fw-bold text-brown">Rp <span class="item-refund-est">0</span></td>
                                                                                                                </tr>
                                                                                                            `;
                                    });
                                    document.getElementById('return-items-body').innerHTML = returnItemsHtml;
                                    document.getElementById('return-total-refund').textContent = '0';

                                    // Re-bind events for the new inputs
                                    bindReturnEvents();

                                    setTimeout(() => {
                                        bootstrap.Modal.getOrCreateInstance(returnProcessModal).show();
                                    }, 400);
                                };
                            } else {
                                btnReturn.classList.add('d-none');
                            }
                            // Setup Print Button
                            document.getElementById('btn-print-receipt').onclick = function () {
                                const width = 400;
                                const height = 600;
                                const left = (window.screen.width / 2) - (width / 2);
                                const top = (window.screen.height / 2) - (height / 2);
                                window.open(`{{ route($routePrefix . 'reports.cashier.receipt', ['id' => ':id']) }}`.replace(':id', id), 'Receipt', `width=${width},height=${height},top=${top},left=${left},scrollbars=yes`);
                            };

                            // Table body
                            itemsBody.innerHTML = '';
                            data.items.forEach(item => {
                                const netQuantity = item.quantity - item.returned_quantity;
                                itemsBody.innerHTML += `
                                                                                                        <tr>
                                                                                                            <td>
                                                                                                                <div class="fw-bold text-brown">${item.name}</div>
                                                                                                                <div class="small text-muted">Rp ${formatIDR(item.price)}</div>
                                                                                                            </td>
                                                                                                            <td class="text-center text-brown">
                                                                                                                ${item.returned_quantity > 0
                                        ? `<span class="text-decoration-line-through text-muted small">${item.quantity}</span><br><b>${netQuantity}</b>`
                                        : `<b>${item.quantity}</b>`}
                                                                                                            </td>
                                                                                                            <td class="text-center text-brown">
                                                                                                                ${item.returned_quantity > 0
                                        ? `<span class="text-decoration-line-through text-muted small">Rp ${formatIDR(item.subtotal)}</span><br><b>Rp ${formatIDR(netQuantity * item.price)}</b>`
                                        : `<b>Rp ${formatIDR(item.subtotal)}</b>`}
                                                                                                            </td>
                                                                                                            <td class="text-end">
                                                                                                                ${item.returned_quantity > 0
                                        ? `<span class="badge bg-danger-soft text-danger border border-danger-subtle">- ${item.returned_quantity} Retur</span>`
                                        : '<i class="fa-solid fa-check text-success"></i>'}
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    `;
                            });
                        })
                        .catch(err => {
                            console.error(err);
                            showToast('error', 'Gagal memuat detail transaksi');
                            itemsBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-2"></i>Error loading transaction details</td></tr>';
                        });
                });
            });

            // Direct Print
            document.querySelectorAll('.btn-print-direct').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const width = 400;
                    const height = 600;
                    const left = (window.screen.width / 2) - (width / 2);
                    const top = (window.screen.height / 2) - (height / 2);
                    window.open(`{{ route($routePrefix . 'reports.cashier.receipt', ['id' => ':id']) }}`.replace(':id', id), 'Receipt', `width=${width},height=${height},top=${top},left=${left},scrollbars=yes`);
                });
            });

            // Transaction Edit
            const transactionEditModal = new bootstrap.Modal(document.getElementById('transactionEditModal'));
            document.querySelectorAll('.btn-edit-transaction').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const method = this.getAttribute('data-method');
                    const cash = this.getAttribute('data-cash');

                    document.getElementById('tx-edit-form').action = `{{ route($routePrefix . 'reports.cashier.update', ['id' => ':id']) }}`.replace(':id', id);
                    document.getElementById('tx-edit-method').value = method;
                    document.getElementById('tx-edit-cash').value = cash;

                    transactionEditModal.show();
                });
            });

            // Delete Confirmation
            document.querySelectorAll('.btn-delete-tx').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    confirmAction({
                        title: 'Hapus Permanen?',
                        text: 'Tindakan ini akan menghapus data transaksi secara permanen dan tidak bisa dikembalikan!',
                        icon: 'error',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${id}`).submit();
                        }
                    });
                });
            });
        });

        function bindReturnEvents() {
            const updateTotals = () => {
                let totalRefund = 0;
                document.querySelectorAll('.input-qty').forEach(input => {
                    const qty = parseInt(input.value) || 0;
                    const price = parseFloat(input.getAttribute('data-price')) || 0;
                    const refund = qty * price;
                    input.closest('tr').querySelector('.item-refund-est').textContent = new Intl.NumberFormat('id-ID').format(refund);
                    totalRefund += refund;
                });
                document.getElementById('return-total-refund').textContent = new Intl.NumberFormat('id-ID').format(totalRefund);
            };

            document.querySelectorAll('.btn-qty').forEach(btn => {
                btn.onclick = function () {
                    const input = this.closest('.input-group').querySelector('.input-qty');
                    const type = this.getAttribute('data-type');
                    const max = parseInt(input.getAttribute('max'));
                    let val = parseInt(input.value) || 0;

                    if (type === 'plus' && val < max) val++;
                    else if (type === 'minus' && val > 0) val--;

                    input.value = val;
                    updateTotals();
                };
            });

            document.querySelectorAll('.input-qty').forEach(input => {
                input.oninput = function () {
                    let val = parseInt(this.value) || 0;
                    const max = parseInt(this.getAttribute('max'));
                    if (val > max) this.value = max;
                    if (val < 0) this.value = 0;
                    updateTotals();
                };
            });
        }
    </script>

    <style>
        .bg-brown-soft {
            background-color: rgba(111, 88, 73, 0.1);
        }

        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.1);
        }

        .rounded-16 {
            border-radius: 16px;
        }
    </style>
@endsection