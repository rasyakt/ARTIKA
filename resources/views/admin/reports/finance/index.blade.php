@extends('layouts.app')

@section('content')
    <style>
        html {
            scroll-behavior: auto !important;
        }
    </style>
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.reports') }}" class="btn btn-outline-brown me-3 shadow-sm"
                    style="border-radius: 10px;">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="fw-bold mb-1" style="color: #6f5849;">{{ __('admin.finance_report') }}</h2>
                    <p class="text-muted mb-0">{{ __('admin.finance_reports_subtitle') }}</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-brown shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#filterModal">
                    <i class="fa-solid fa-filter me-2"></i> {{ __('admin.apply_filter') }}
                </button>
                <button type="button" class="btn btn-outline-brown shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#exportPdfCustomModal">
                    <i class="fa-solid fa-file-pdf me-2"></i> {{ __('admin.download_pdf') }}
                </button>
                <a href="{{ route('admin.reports.finance.export', array_merge(request()->all(), ['format' => 'csv'])) }}"
                    class="btn btn-brown shadow-sm">
                    <i class="fa-solid fa-file-csv me-2"></i> {{ __('admin.export_csv') ?? 'Export CSV' }}
                </a>
            </div>
        </div>

        <!-- Filter Period Display -->
        <div class="alert alert-brown mb-4 d-flex justify-content-between align-items-center">
            <div>
                <i class="fa-solid fa-calendar-days me-2"></i>
                {{ __('admin.select_period') }}:
                <span class="fw-bold">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</span>
                <span class="badge bg-brown ms-2">{{ __('admin.' . $period) }}</span>
            </div>
        </div>

        <!-- Financial KPI Cards -->
        <div class="row g-3 mb-4">
            <!-- Gross Revenue -->
            <div class="col-md col-sm-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="p-2 rounded-3 me-2" style="background: #fdf8f6; color: #6f5849;">
                                <i class="fa-solid fa-money-bill-trend-up"></i>
                            </div>
                            <p class="text-muted small mb-0 fw-semibold">{{ strtoupper(__('admin.gross_revenue')) }}</p>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: #4b382f; font-size: 1.1rem;">Rp
                            {{ number_format($summary['gross_revenue'], 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Total Cost (COGS) -->
            <div class="col-md col-sm-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="p-2 rounded-3 me-2" style="background: #fff5f2; color: #c17a5c;">
                                <i class="fa-solid fa-tags"></i>
                            </div>
                            <p class="text-muted small mb-0 fw-semibold">{{ strtoupper(__('admin.cogs')) }}</p>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: #4b382f; font-size: 1.1rem;">Rp
                            {{ number_format($summary['cogs'], 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Operating Expenses -->
            <div class="col-md col-sm-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="p-2 rounded-3 me-2" style="background: #fef9c3; color: #a16207;">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <p class="text-muted small mb-0 fw-semibold">{{ strtoupper(__('admin.operational_expenses')) }}
                            </p>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: #4b382f; font-size: 1.1rem;">Rp
                            {{ number_format($summary['total_expenses'], 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Stock Procurement -->
            <div class="col-md col-sm-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="p-2 rounded-3 me-2" style="background: #f3e8ff; color: #7e22ce;">
                                <i class="fa-solid fa-truck-ramp-box"></i>
                            </div>
                            <p class="text-muted small mb-0 fw-semibold">{{ strtoupper(__('admin.stock_procurement')) }}</p>
                        </div>
                        <h5 class="fw-bold mb-0" style="color: #4b382f; font-size: 1.1rem;">Rp
                            {{ number_format($summary['total_procurement'], 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

            <!-- Net Profit -->
            <div class="col-md-3 col-sm-12">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted small mb-1 fw-semibold">{{ strtoupper(__('admin.net_profit')) }}</p>
                                <h4 class="fw-bold mb-0"
                                    style="color: {{ $summary['net_profit'] >= 0 ? '#15803d' : '#991b1b' }}; font-size: 1.3rem;">
                                    Rp {{ number_format($summary['net_profit'], 0, ',', '.') }}
                                </h4>
                            </div>
                            <div class="p-2 rounded-4"
                                style="background: {{ $summary['net_profit'] >= 0 ? '#f0fdf4' : '#fef2f2' }}; color: {{ $summary['net_profit'] >= 0 ? '#16a34a' : '#dc2626' }};">
                                <i class="fa-solid fa-wallet fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <!-- Profit Margin & Returns -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="fw-bold mb-4" style="color: #6f5849;">{{ __('admin.quick_info') }}</h6>

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <div>
                                <div class="small text-muted mb-1">{{ __('admin.gross_profit') }}</div>
                                <h5 class="fw-bold mb-0" style="color: #16a34a;">
                                    Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}
                                </h5>
                            </div>
                            <div class="text-success opacity-25">
                                <i class="fa-solid fa-sack-dollar fa-2x"></i>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                            <div>
                                <div class="small text-muted mb-1">{{ __('admin.profit_margin') }}</div>
                                <h5 class="fw-bold mb-0"
                                    style="color: {{ $summary['profit_margin'] > 15 ? '#16a34a' : ($summary['profit_margin'] > 5 ? '#ca8a04' : '#dc2626') }}">
                                    {{ number_format($summary['profit_margin'], 2) }}%
                                </h5>
                            </div>
                            <div class="circular-progress-container">
                                <i class="fa-solid fa-chart-line fa-2x opacity-25"></i>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small text-muted mb-1">{{ __('admin.returns_refunds') }}</div>
                                <h5 class="fw-bold mb-0 text-danger">- Rp
                                    {{ number_format($summary['total_returns'], 0, ',', '.') }}
                                </h5>
                            </div>
                            <div class="text-danger opacity-25">
                                <i class="fa-solid fa-rotate-left fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trend Chart -->
            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0" style="color: #6f5849;">{{ __('admin.financial_trend') }}</h6>
                        <div id="chart-controls" class="btn-group btn-group-sm">
                            <!-- Toggle buttons will be injected or managed via CSS/JS -->
                        </div>
                    </div>
                    <div class="card-body px-4">
                        <div style="height: 300px;">
                            <canvas id="financeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Daily Data -->
        <div class="card shadow-sm" id="daily-profit-section">
            <div class="card-header border-0 pt-4 px-4">
                <h6 class="fw-bold mb-0" style="color: #6f5849;">{{ __('admin.daily_profit') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr style="background: #fdf8f6;">
                                <th>{{ __('admin.date') }}</th>
                                <th class="text-end">{{ __('admin.gross_revenue') }}</th>
                                <th class="text-end">{{ __('admin.cogs') }}</th>
                                <th class="text-end">{{ __('admin.operational_expenses') }}</th>
                                <th class="text-end">{{ __('admin.stock_procurement') }}</th>
                                <th class="text-end">{{ __('admin.net_profit') }}</th>
                                <th class="text-end">{{ __('admin.profit_margin') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailyData as $day)
                                <tr>
                                    <td class="fw-medium">{{ \Carbon\Carbon::parse($day['date'])->format('d M Y') }}</td>
                                    <td class="text-end">Rp {{ number_format($day['revenue'], 0, ',', '.') }}</td>
                                    <td class="text-end text-muted">Rp {{ number_format($day['cogs'], 0, ',', '.') }}</td>
                                    <td class="text-end text-muted">Rp {{ number_format($day['expenses'], 0, ',', '.') }}</td>
                                    <td class="text-end text-muted">Rp {{ number_format($day['procurement'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end fw-bold {{ $day['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        Rp {{ number_format($day['profit'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-end">
                                        @php $margin = $day['revenue'] > 0 ? ($day['profit'] / $day['revenue']) * 100 : 0; @endphp
                                        <span
                                            class="badge {{ $margin > 15 ? 'bg-success' : ($margin > 5 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ number_format($margin, 1) }}%
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-3 py-2 border-top d-flex justify-content-end">
                    {{ $dailyData->fragment('daily-profit-section')->links('vendor.pagination.no-prevnext') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px; border: none;">
                <form action="{{ route('admin.reports.finance') }}" method="GET">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" style="color: #6f5849;">{{ __('admin.filter_report') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"
                                style="color: #85695a;">{{ __('admin.quick_period') }}</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <a href="?period=today"
                                        class="btn btn-outline-brown w-100 {{ $period == 'today' ? 'active' : '' }}">{{ __('admin.today') }}</a>
                                </div>
                                <div class="col-6">
                                    <a href="?period=week"
                                        class="btn btn-outline-brown w-100 {{ $period == 'week' ? 'active' : '' }}">{{ __('admin.this_week') }}</a>
                                </div>
                                <div class="col-6">
                                    <a href="?period=month"
                                        class="btn btn-outline-brown w-100 {{ $period == 'month' ? 'active' : '' }}">{{ __('admin.this_month') }}</a>
                                </div>
                                <div class="col-6">
                                    <a href="?period=year"
                                        class="btn btn-outline-brown w-100 {{ $period == 'year' ? 'active' : '' }}">{{ __('admin.this_year') }}</a>
                                </div>
                            </div>
                        </div>

                        <div class="hr-text text-muted mb-3"><span>{{ __('admin.custom_range') }}</span></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.start_date') }}</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ $startDate->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ __('admin.end_date') }}</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ $endDate->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                        <button type="submit" class="btn btn-brown px-4">{{ __('admin.apply_filter') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- PDF Customization Modal -->
    <div class="modal fade" id="exportPdfCustomModal" tabindex="-1" aria-labelledby="exportPdfCustomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow border-0" style="border-radius: 16px;">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-brown" id="exportPdfCustomModalLabel">
                        <i class="fa-solid fa-file-settings me-2"></i>Kustomisasi Laporan Keuangan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.reports.finance.export') }}" method="GET">
                    <input type="hidden" name="format" value="pdf">
                    <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">
                    <input type="hidden" name="end_date" value="{{ $endDate->format('Y-m-d') }}">
                    <input type="hidden" name="period" value="{{ $period }}">

                    <div class="modal-body py-4">
                        <p class="text-muted mb-4 small">Pilih bagian laporan yang ingin ditampilkan dalam dokumen PDF:
                        </p>

                        <div class="list-group list-group-flush border rounded-12">
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]" value="summary"
                                    checked>
                                <div>
                                    <div class="fw-bold">Ringkasan KPI Keuangan</div>
                                    <small class="text-muted small">Pendapatan kotor, COGS, Laba bersih, dll.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]" value="quick_info"
                                    checked>
                                <div>
                                    <div class="fw-bold">Info Cepat & Margin</div>
                                    <small class="text-muted small">Margin profit, Gross profit, Retur & Refund.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]" value="trend_chart"
                                    checked>
                                <div>
                                    <div class="fw-bold">Grafik Tren Keuangan</div>
                                    <small class="text-muted small">Visualisasi tren pendapatan vs keuntungan.</small>
                                </div>
                            </label>
                            <label class="list-group-item d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="checkbox" name="sections[]" value="daily_data"
                                    checked>
                                <div>
                                    <div class="fw-bold">Data Harian Detail</div>
                                    <small class="text-muted small">Tabel rincian keuangan setiap harinya.</small>
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
    </div>

    <style>
        .btn-brown {
            background-color: #85695a;
            color: white;
        }

        .btn-brown:hover {
            background-color: #6f5849;
            color: white;
        }

        .btn-outline-brown {
            border-color: #85695a;
            color: #85695a;
        }

        .btn-outline-brown:hover,
        .btn-outline-brown.active {
            background-color: #85695a;
            color: white;
        }

        .alert-brown {
            background-color: #fdf8f6;
            border-color: #f2e8e5;
            color: #6f5849;
            border-radius: 12px;
        }

        .bg-brown {
            background-color: #85695a;
        }

        .table-earth {
            background-color: #fdf8f6 !important;
        }

        .stats-icon-small {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .hr-text {
            display: flex;
            align-items: center;
            text-align: center;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hr-text::before,
        .hr-text::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #f2e8e5;
        }

        .hr-text::before {
            margin-right: .5em;
        }

        .hr-text::after {
            margin-left: .5em;
        }

        .chart-legend-btn {
            background: transparent;
            border: 1px solid #f2e8e5;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6f5849;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            cursor: pointer;
            margin: 2px;
        }

        .chart-legend-btn:hover {
            background: #fdf8f6;
            border-color: #eaddd7;
        }

        .chart-legend-btn.hidden {
            opacity: 0.4;
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dailyData = {!! json_encode($allDailyData) !!};

            const labels = dailyData.map(d => {
                const date = new Date(d.date);
                return date.toLocaleDateString(navigator.language, { day: 'numeric', month: 'short' });
            });

            const revenueData = dailyData.map(d => d.revenue);
            const costData = dailyData.map(d => d.cogs);
            const expenseData = dailyData.map(d => d.expenses);
            const profitData = dailyData.map(d => d.profit);

            const ctx = document.getElementById('financeChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '{{ __('admin.gross_revenue') }}',
                            data: revenueData,
                            borderColor: '#85695a',
                            backgroundColor: 'rgba(133, 105, 90, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: '#85695a'
                        },
                        {
                            label: '{{ __('admin.cogs') }}',
                            data: costData,
                            borderColor: '#c17a5c',
                            borderDash: [5, 5],
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 0
                        },
                        {
                            label: '{{ __('admin.operational_expenses') }}',
                            data: expenseData,
                            borderColor: '#ca8a04',
                            borderDash: [2, 2],
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 0
                        },
                        {
                            label: '{{ __('admin.stock_procurement') }}',
                            data: dailyData.map(d => d.procurement),
                            borderColor: '#9333ea',
                            borderDash: [3, 3],
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 0
                        },
                        {
                            label: '{{ __('admin.net_profit') }}',
                            data: profitData,
                            borderColor: '#16a34a',
                            borderWidth: 2,
                            pointRadius: 0,
                            fill: false,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            display: false // Hide default legend to use custom buttons
                        },
                        tooltip: {
                            padding: 12,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 13 },
                            callbacks: {
                                label: function (context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f5f5f4' },
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + (value >= 1000000 ? (value / 1000000) + 'M' : (value / 1000) + 'k');
                                }
                            }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });

            // Handle custom legend / toggle buttons
            const controls = document.getElementById('chart-controls');
            chart.data.datasets.forEach((dataset, i) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'chart-legend-btn';

                const dot = document.createElement('span');
                dot.className = 'legend-dot';
                dot.style.backgroundColor = dataset.borderColor;

                btn.appendChild(dot);
                btn.appendChild(document.createTextNode(dataset.label));

                btn.onclick = () => {
                    const meta = chart.getDatasetMeta(i);
                    meta.hidden = meta.hidden === null ? !chart.data.datasets[i].hidden : null;
                    chart.update();

                    if (meta.hidden) {
                        btn.classList.add('hidden');
                    } else {
                        btn.classList.remove('hidden');
                    }
                };
                controls.appendChild(btn);
            });
        });
    </script>
@endpush