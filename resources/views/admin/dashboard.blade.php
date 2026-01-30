@extends('layouts.app')

@section('content')
    <style>
        .chart-container {
            position: relative;
            height: 350px;
        }

        .table-hover tbody tr:hover {
            background-color: #fdf8f6;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;">{{ __('admin.dashboard_title') }}</h2>
                <p class="text-muted mb-0">{{ __('admin.dashboard_subtitle') }}</p>
            </div>
            <div class="text-end">
                <small class="text-muted">{{ __('common.last_updated') }}: {{ now()->format('d M Y, H:i') }}</small>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Sales -->
            <div class="col-md-3">
                <div class="card h-100 shadow-sm accent-brown">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('common.total_sales') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
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
            <div class="col-md-3">
                <div class="card h-100 shadow-sm accent-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('common.transactions') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">{{ number_format($totalTransactions) }}</h3>
                                <small class="text-muted">{{ __('common.completed') }}</small>
                            </div>
                            <div class="icon-box-premium bg-success-soft">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-md-3">
                <div class="card h-100 shadow-sm accent-sienna">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('common.products') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">{{ $totalProducts }}</h3>
                                <small class="text-muted">{{ __('common.in_catalog') }}</small>
                            </div>
                            <div class="icon-box-premium bg-sienna-soft">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Suppliers -->
            <div class="col-md-3">
                <div class="card h-100 shadow-sm accent-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('common.suppliers') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">{{ $totalSuppliers ?? $totalCustomers ?? 0 }}</h3>
                                <small class="text-muted">{{ __('common.registered_suppliers') }}</small>
                            </div>
                            <div class="icon-box-premium bg-info-soft">
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
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-chart-line me-2"></i>{{ __('common.sales_overview') }}</h5>
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
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-trophy me-2"></i>{{ __('common.top_products') }}</h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @forelse($topProducts as $index => $product)
                            <div
                                class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex align-items-center">
                                    <div class="me-3"
                                        style="width: 30px; height: 30px; background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.85rem;">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <div class="fw-bold" style="color: #6f5849; font-size: 0.95rem;">{{ $product->name }}
                                        </div>
                                        <small class="text-muted">{{ $product->total_sold }} {{ __('common.sold') }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold" style="color: #c17a5c;">Rp
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
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-receipt me-2"></i>{{ __('common.recent_transactions') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.invoice') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.cashier') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.amount') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.payment') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.time') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTransactions as $transaction)
                                        <tr>
                                            <td class="fw-bold" style="color: #85695a;">{{ $transaction->invoice_no }}</td>
                                            <td>{{ $transaction->user->name }}</td>
                                            <td class="fw-bold" style="color: #c17a5c;">Rp
                                                {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                            <td><span class="badge"
                                                    style="background: #e0cec7; color: #6f5849;">{{ ucfirst($transaction->payment_method) }}</span>
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

            <!-- Low Stock Alerts -->
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ __('common.low_stock_alerts') }}</h5>
                    </div>
                    <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                        @forelse($lowStockProducts as $stock)
                            <div
                                class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <div class="fw-bold" style="color: #6f5849;">{{ $stock->product->name }}</div>
                                    <small class="text-muted">{{ $stock->product->category->name }}</small>
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

                        {{-- Suppliers summary --}}
                        @php $recentSuppliers = $recentSuppliers ?? ($suppliers ?? collect()); @endphp
                        <hr style="border-color: #f2e8e5; margin: 1rem 0;">
                        <h6 class="fw-bold mb-3" style="color: #6f5849;"><i class="fa-solid fa-truck me-2"></i>{{ __('common.recent_suppliers') }}</h6>
                        @forelse($recentSuppliers->take(5) as $supplier)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <div class="fw-bold" style="color: #6f5849;">{{ $supplier->name ?? $supplier['name'] ?? '-' }}</div>
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
        const salesData = @json($salesChartData);
        let currentChart = null;

        function createChart(period = 'daily') {
            const ctx = document.getElementById('salesChart').getContext('2d');

            if (currentChart) {
                currentChart.destroy();
            }

            const data = salesData[period];

            currentChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Sales (Rp)',
                        data: data.values,
                        borderColor: '#85695a',
                        backgroundColor: 'rgba(133, 105, 90, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#85695a',
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
                            backgroundColor: '#6f5849',
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
                                color: '#78716c'
                            },
                            grid: {
                                color: '#f5f5f4'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#78716c'
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