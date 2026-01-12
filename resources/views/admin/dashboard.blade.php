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
                <h2 class="fw-bold mb-1" style="color: #6f5849;">Admin Dashboard</h2>
                <p class="text-muted mb-0">Overview of store performance</p>
            </div>
            <div class="text-end">
                <a href="{{ route('admin.audit.index') }}" class="btn btn-outline-primary btn-sm me-2">
                    <i class="fas fa-clipboard-list"></i> Audit Log
                </a>
                <small class="text-muted">Last updated: {{ now()->format('d M Y, H:i') }}</small>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Sales -->
            <div class="col-md-3">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Total Sales</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                                <small class="opacity-75">This month</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Transactions -->
            <div class="col-md-3">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Transactions</p>
                                <h3 class="fw-bold mb-0">{{ number_format($totalTransactions) }}</h3>
                                <small class="opacity-75">Completed</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-md-3">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #c17a5c 0%, #a18072 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Products</p>
                                <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                                <small class="opacity-75">In catalog</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Suppliers -->
            <div class="col-md-3">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #0284c7 0%, #075985 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Suppliers</p>
                                <h3 class="fw-bold mb-0">{{ $totalSuppliers ?? $totalCustomers ?? 0 }}</h3>
                                <small class="opacity-75">Registered suppliers</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
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
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-chart-line me-2"></i>Sales Overview</h5>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-primary active"
                                    onclick="updateChart('daily')">Daily</button>
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="updateChart('weekly')">Weekly</button>
                                <button type="button" class="btn btn-outline-primary"
                                    onclick="updateChart('monthly')">Monthly</button>
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
                <div class="card shadow-sm" style="border-radius: 16px; border: none; height: 100%;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-trophy me-2"></i>Top Products</h5>
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
                                        <small class="text-muted">{{ $product->total_sold }} sold</small>
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
                                <p class="mb-0">No sales data yet</p>
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
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-receipt me-2"></i>Recent Transactions</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Invoice</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Cashier</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Amount</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Payment</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Time</th>
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
                                            <td colspan="5" class="text-center text-muted py-4">No transactions yet</td>
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
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-triangle-exclamation me-2"></i>Low Stock Alerts</h5>
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
                                    <span class="badge bg-danger">{{ $stock->quantity }} left</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">
                                <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-circle-check"></i></div>
                                <p class="mb-0">All products in stock</p>
                            </div>
                        @endforelse

                        {{-- Suppliers summary --}}
                        @php $recentSuppliers = $recentSuppliers ?? ($suppliers ?? collect()); @endphp
                        <hr style="border-color: #f2e8e5; margin: 1rem 0;">
                        <h6 class="fw-bold mb-3" style="color: #6f5849;"><i class="fa-solid fa-truck me-2"></i>Recent Suppliers</h6>
                        @forelse($recentSuppliers->take(5) as $supplier)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div>
                                    <div class="fw-bold" style="color: #6f5849;">{{ $supplier->name ?? $supplier['name'] ?? '-' }}</div>
                                    <small class="text-muted">{{ $supplier->phone ?? $supplier['phone'] ?? '-' }} • {{ $supplier->address ?? $supplier['address'] ?? '-' }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Last PO: {{ $supplier->last_purchase_at ? 
                                        \Carbon\Carbon::parse($supplier->last_purchase_at)->diffForHumans() : '-' }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">No suppliers yet</div>
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