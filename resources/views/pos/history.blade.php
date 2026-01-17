<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - ARTIKA POS</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .accordion-button:not(.collapsed) {
            background-color: var(--brown-50);
            color: var(--primary-dark);
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(133, 105, 90, 0.5);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid px-4">
            <span class="navbar-brand fw-bold mb-0 h1"><i class="fa-solid fa-clock-rotate-left me-2"></i>{{ __('pos.transaction_history') }}</span>
            <div>
                <a href="{{ route('pos.index') }}" class="btn" style="border-radius: 10px; padding: 0.5rem 1.25rem; background: rgba(255, 255, 255, 0.15); border: none; color: white; font-weight: 600;">
                    <i class="fa-solid fa-arrow-left me-2"></i>{{ __('pos.back_to_pos') }}
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Date Filter & Summary -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <form action="{{ route('pos.history') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">{{ __('admin.from_date') }}</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-bold">{{ __('admin.to_date') }}</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100" style="border-radius: 10px; padding: 0.6rem;">
                                    <i class="fa-solid fa-filter me-1"></i> {{ __('pos.filter_report') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <small class="opacity-75 text-uppercase fw-bold">{{ __('pos.total_revenue') }}</small>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                        <small class="mt-2"><i class="fa-solid fa-calendar-check me-1"></i> {{ __('pos.filtered_period') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sold Items Summary -->
        @if(isset($soldItems) && $soldItems->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary-dark"><i class="fa-solid fa-chart-pie me-2"></i>{{ __('pos.items_sold_overview') }}
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-sm table-striped mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-3">{{ __('pos.item_name') }}</th>
                                    <th class="text-center">{{ __('pos.qty_sold') }}</th>
                                    <th class="text-end pe-3">{{ __('pos.total_sales') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($soldItems as $item)
                                    <tr>
                                        <td class="ps-3">{{ $item->name }}</td>
                                        <td class="text-center fw-bold">{{ $item->total_qty }}</td>
                                        <td class="text-end pe-3">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="px-4 py-3">{{ __('pos.invoice_no') }}</th>
                                <th class="px-4 py-3">{{ __('pos.date') }}</th>
                                <th class="px-4 py-3 text-end">{{ __('pos.total') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('pos.payment_method_label') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('pos.items') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('pos.details') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="px-4 py-3 fw-bold text-primary-dark">
                                        {{ $transaction->invoice_no }}
                                    </td>
                                    <td class="px-4 py-3 text-muted">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="px-4 py-3 text-end fw-bold">
                                        Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="badge bg-light text-dark border">
                                            {{ strtoupper(ucfirst($transaction->payment_method)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span
                                            class="badge rounded-pill bg-secondary">{{ $transaction->items->count() }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button class="btn btn-sm btn-link text-primary" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#details-{{ $transaction->id }}"
                                            title="{{ __('pos.view_items') }}">
                                            <i class="fa-solid fa-list"></i>
                                        </button>
                                        <button class="btn btn-sm btn-link text-secondary"
                                            onclick="openReceipt('{{ route('pos.receipt', $transaction->id) }}')"
                                            title="{{ __('pos.print_receipt') }}">
                                            <i class="fa-solid fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="p-0 border-0">
                                        <div class="collapse bg-light" id="details-{{ $transaction->id }}">
                                            <div class="p-4">
                                                <!-- Payment Details -->
                                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                                            <span class="text-muted small">{{ __('pos.total') }}:</span>
                                                        <span class="fw-bold">Rp
                                                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                                    </div>
                                                    @if(strtolower($transaction->payment_method) === 'cash')
                                                        <div>
                                                            <span class="text-muted small">{{ __('pos.paid') }}:</span>
                                                            <span class="fw-bold text-success">Rp
                                                                {{ number_format($transaction->cash_amount, 0, ',', '.') }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="text-muted small">{{ __('pos.change') }}:</span>
                                                            <span class="fw-bold text-warning">Rp
                                                                {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <h6 class="fw-bold mb-3">{{ __('pos.items_purchased') }}</h6>
                                                <table class="table table-sm table-bordered bg-white mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>{{ __('pos.items') }}</th>
                                                            <th class="text-center">{{ __('pos.qty') }}</th>
                                                            <th class="text-end">{{ __('pos.price') }}</th>
                                                            <th class="text-end">{{ __('pos.subtotal') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($transaction->items as $item)
                                                            <tr>
                                                                <td>{{ $item->product->name }}</td>
                                                                <td class="text-center">{{ $item->quantity }}</td>
                                                                <td class="text-end">Rp
                                                                    {{ number_format($item->price, 0, ',', '.') }}</td>
                                                                <td class="text-end">Rp
                                                                    {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-receipt fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">{{ __('pos.no_transactions') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($transactions->hasPages())
                <div class="card-footer bg-white py-3 d-flex justify-content-end">
                    {{ $transactions->links('vendor.pagination.no-prevnext') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function openReceipt(url) {
            const width = 400;
            const height = 600;
            const left = (window.screen.width / 2) - (width / 2);
            const top = (window.screen.height / 2) - (height / 2);

            window.open(url, 'Receipt', `width=${width},height=${height},top=${top},left=${left},scrollbars=yes`);
        }
    </script>
</body>

</html>