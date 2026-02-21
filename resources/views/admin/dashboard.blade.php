@extends('layouts.app')

@section('content')
    <style>
        .chart-container {
            position: relative;
            height: 350px;
        }

        .table-hover tbody tr:hover {
            background-color: var(--brown-50);
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: var(--color-primary-dark);">{{ __('admin.dashboard_title') }}</h2>
                <p class="text-muted mb-0">{{ __('admin.dashboard_subtitle') }}</p>
            </div>
            <div class="text-end">
                <small class="text-muted">{{ __('common.last_updated') }}: {{ now()->format('d M Y, H:i') }}</small>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Sales -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('common.total_sales') }}</p>
                                <h4 class="fw-bold mb-0" style="color: var(--brown-900);">Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                                <small class="text-muted">{{ __('common.this_month') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('common.transactions') }}</p>
                                <h4 class="fw-bold mb-0" style="color: var(--brown-900);">{{ number_format($totalTransactions) }}</h4>
                                <small class="text-muted">{{ __('common.completed') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('common.products') }}</p>
                                <h4 class="fw-bold mb-0" style="color: var(--brown-900);">{{ $totalProducts }}</h4>
                                <small class="text-muted">{{ __('common.in_catalog') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Suppliers -->
            <div class="col-xl-3 col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('common.suppliers') }}</p>
                                <h4 class="fw-bold mb-0" style="color: var(--brown-900);">{{ $totalSuppliers ?? $totalCustomers ?? 0 }}</h4>
                                <small class="text-muted">{{ __('common.registered_suppliers') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Sales Chart -->
            <div class="col-xl-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header"
                        style="border-bottom: 2px solid var(--brown-100); border-radius: 16px 16px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold" style="color: var(--color-primary-dark);"><i class="fa-solid fa-chart-line me-2"></i>{{ __('common.sales_overview') }}</h5>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active"
                                    onclick="updateChart('daily')">{{ __('common.daily') }}</button>
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="updateChart('weekly')">{{ __('common.weekly') }}</button>
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="updateChart('monthly')">{{ __('common.monthly') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="col-xl-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header"
                        style="border-bottom: 2px solid var(--brown-100); border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: var(--color-primary-dark);"><i class="fa-solid fa-trophy me-2"></i>{{ __('common.top_products') }}</h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse($topProducts as $index => $product)
                            <div
                                class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="me-3"
                                        style="width: 30px; height: 30px; background: var(--color-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.85rem;">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: var(--color-primary-dark); font-size: 0.95rem;">{{ $product->name }}
                                        </div>
                                        <small class="text-muted">{{ $product->total_sold }} {{ __('common.sold') }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="color: var(--color-accent-warm);">Rp
                                        {{ number_format($product->total_revenue, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-chart-pie"></i></div>
                                <p class="mb-0">{{ __('common.no_sales_data') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions & Low Stock -->
        <div class="row g-4">
            <!-- Recent Transactions -->
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header"
                        style="border-bottom: 2px solid var(--brown-100); border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: var(--color-primary-dark);"><i class="fa-solid fa-receipt me-2"></i>{{ __('common.recent_transactions') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: var(--brown-50);">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.invoice') }}</th>
                                        <th class="border-0 fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.cashier') }}</th>
                                        <th class="border-0 fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.amount') }}</th>
                                        <th class="border-0 fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.payment') }}</th>
                                        <th class="border-0 fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.time') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $transaction)
                                        <tr>
                                            <td class="fw-bold" style="color: var(--color-primary);">{{ $transaction->invoice_no }}</td>
                                            <td>{{ $transaction->user->name ?? 'System/Deleted' }}</td>
                                            <td class="fw-bold" style="color: var(--color-accent-warm);">Rp
                                                {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                            <td><span class="badge"
                                                    style="background: var(--color-secondary-light); color: var(--color-primary-dark);">{{ ucfirst($transaction->payment_method) }}</span>
                                            </td>
                                            <td class="text-muted">{{ $transaction->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">{{ __('common.no_transactions') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock & Expiry Alerts -->
            <div class="col-md-5">
                <!-- Expired Products -->
                @if($expiredProducts->count() > 0)
                <div class="card shadow-sm border-danger mb-4">
                    <div class="card-header bg-danger text-white"
                        style="border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-xmark me-2"></i>{{ __('admin.expired_products') }}</h5>
                    </div>
                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                        @foreach($expiredProducts as $stock)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <div class="fw-bold text-danger">{{ $stock->product->name }}</div>
                                    <small class="text-muted">{{ __('admin.expired_on') }}: {{ $stock->expired_at->format('d M Y') }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger">{{ $stock->quantity }} {{ __('common.left') }} </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Expiring Soon Products -->
                @if($expiringSoonProducts->count() > 0)
                <div class="card shadow-sm border-warning mb-4">
                    <div class="card-header bg-warning"
                        style="border-radius: 16px 16px 0 0; color: var(--brown-900);">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-clock me-2"></i>{{ __('admin.expiring_soon_alerts') }}</h5>
                    </div>
                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                        @foreach($expiringSoonProducts as $stock)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <div class="fw-bold" style="color: var(--color-primary-dark);">{{ $stock->product->name }}</div>
                                    <small class="text-muted">{{ __('admin.expires_on') }}: {{ $stock->expired_at->format('d M Y') }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning text-dark">{{ $stock->quantity }} {{ __('common.left') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-header"
                        style="border-bottom: 2px solid var(--brown-100); border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: var(--color-primary-dark);"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ __('common.low_stock_alerts') }}</h5>
                    </div>
                    <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                        @forelse($lowStockProducts as $stock)
                            <div
                                class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <div class="fw-bold" style="color: var(--color-primary-dark);">{{ $stock->product->name ?? 'Deleted Product' }}</div>
                                    <small class="text-muted">{{ $stock->product->category->name ?? 'Uncategorized' }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-danger">{{ $stock->quantity }} {{ __('common.left') }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-circle-check"></i></div>
                                <p class="mb-0">{{ __('common.in_stock') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Separate Suppliers Card --}}
                @php $recentSuppliers = $recentSuppliers ?? ($suppliers ?? collect()); @endphp
                <div class="card shadow-sm mt-4">
                    <div class="card-header"
                        style="border-bottom: 2px solid var(--brown-100); border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: var(--color-primary-dark);"><i class="fa-solid fa-truck me-2"></i>{{ __('common.recent_suppliers') }}</h5>
                    </div>
                    <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                        @forelse($recentSuppliers->take(5) as $supplier)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <div class="fw-bold" style="color: var(--color-primary-dark);">{{ $supplier->name ?? $supplier['name'] ?? '-' }}</div>
                                    <small class="text-muted">{{ $supplier->phone ?? $supplier['phone'] ?? '-' }} • {{ $supplier->address ?? $supplier['address'] ?? '-' }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">{{ __('common.last_po') }}: {{ $supplier->last_purchase_at ? 
                                        \Carbon\Carbon::parse($supplier->last_purchase_at)->diffForHumans() : '-' }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">{{ __('common.no_suppliers') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Helper: read CSS custom property value for use in Canvas/Chart.js
        function cssVar(name) {
            return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
        }

        const salesData = @json($salesChartData);
        let currentChart = null;

        function createChart(period = 'daily') {
            const ctx = document.getElementById('salesChart').getContext('2d');

            if (currentChart) {
                currentChart.destroy();
            }

            const data = salesData[period];
            const colorPrimary = cssVar('--color-primary');
            const colorPrimaryDark = cssVar('--color-primary-dark');
            const gray500 = cssVar('--gray-500');
            const gray100 = cssVar('--gray-100');

            currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Sales (Rp)',
                        data: data.values,
                        borderColor: colorPrimary,
                        backgroundColor: colorPrimary + '1a',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: colorPrimary,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: colorPrimaryDark,
                            padding: 12,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            callbacks: {
                                label: function (context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                },
                                color: gray500
                            },
                            grid: {
                                color: gray100
                            }
                        },
                        x: {
                            ticks: {
                                color: gray500
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        function updateChart(period) {
            // Update button states
            document.querySelectorAll('.btn-group button').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Recreate chart
            createChart(period);
        }

        // Initialize chart on page load
        document.addEventListener('DOMContentLoaded', function () {
            createChart('daily');
        });
    </script>
@endsection