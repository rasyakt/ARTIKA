@extends('layouts.app')

@section('content')
    <style>
        .report-card {
            border-radius: 20px;
            border: 1px solid #f2e8e5 !important;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            cursor: pointer;
        }

        .report-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(133, 105, 90, 0.12) !important;
            border-color: #eaddd7 !important;
        }

        .report-icon {
            width: 72px;
            height: 72px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .report-card:hover .report-icon {
            transform: scale(1.1) rotate(5deg);
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;">
                    <i class="fa-solid fa-chart-line me-2"></i>{{ __('admin.reports_hub') }}
                </h2>
                <p class="text-muted mb-0">{{ __('admin.reports_hub_subtitle') }}</p>
            </div>
            <div>
                <button class="btn shadow-sm"
                    style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); color: white; border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600; transition: all 0.3s;"
                    data-bs-toggle="modal" data-bs-target="#printAllModal"
                    onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fa-solid fa-file-invoice me-2"></i> {{ __('admin.print_all_reports') }}
                </button>
            </div>
        </div>



        <!-- Report Cards -->
        <div class="row g-4 mb-4">
            <!-- Finance Report -->
            <div class="col-xl-3 col-md-6">
                <a href="{{ route($routePrefix . 'reports.finance') }}" class="text-decoration-none">
                    <div class="card report-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="report-icon mx-auto bg-brown-soft">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #6f5849;">{{ __('admin.finance_report') }}</h5>
                            <p class="text-muted mb-3 small">{{ __('admin.finance_report_desc') }}</p>
                            <ul class="list-unstyled text-start small" style="color: #78716c; font-size: 0.75rem;">
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.gross_revenue') }}</li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.net_profit') }}</li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.profit_margin') }}</li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.financial_trend') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Warehouse Report -->
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('admin.reports.warehouse') }}" class="text-decoration-none">
                    <div class="card report-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="report-icon mx-auto bg-success-soft">
                                <i class="fa-solid fa-warehouse"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #6f5849;">{{ __('admin.warehouse_report') }}</h5>
                            <p class="text-muted mb-3 small">{{ __('admin.warehouse_report_desc') }}</p>
                            <ul class="list-unstyled text-start small" style="color: #78716c; font-size: 0.75rem;">
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.total_valuation') }}
                                </li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.top_moving_items') }}
                                </li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.low_stock_alerts') }}
                                </li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.movements') }}</li>
                            </ul>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Cashier Report -->
            <div class="col-xl-3 col-md-6">
                <a href="{{ route('admin.reports.cashier') }}" class="text-decoration-none">
                    <div class="card report-card shadow-sm h-100">
                        <div class="card-body text-center p-4">
                            <div class="report-icon mx-auto bg-info-soft">
                                <i class="fa-solid fa-cash-register"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #6f5849;">{{ __('admin.cashier_report') }}</h5>
                            <p class="text-muted mb-3 small">{{ __('admin.cashier_report_desc') }}</p>
                            <ul class="list-unstyled text-start small" style="color: #78716c; font-size: 0.75rem;">
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.total_sales') }}</li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.top_selling_products') }}
                                </li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.cashier_performance') }}
                                </li>
                                <li class="mb-1"><i
                                        class="fa-solid fa-check text-success me-2"></i>{{ __('admin.payment_method') }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </a>
            </div>

            @if(App\Models\Setting::get('admin_enable_audit_logs', true))
                <div class="col-xl-3 col-md-6">
                    <a href="{{ route($routePrefix . 'audit.index') }}" class="text-decoration-none">
                        <div class="card report-card shadow-sm h-100">
                            <div class="card-body text-center p-4">
                                <div class="report-icon mx-auto bg-sienna-soft">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: #6f5849;">{{ __('admin.logs_report') }}</h5>
                                <p class="text-muted mb-3 small">{{ __('admin.logs_report_desc') }}</p>
                                <ul class="list-unstyled text-start small" style="color: #78716c; font-size: 0.75rem;">
                                    <li class="mb-1"><i
                                            class="fa-solid fa-check text-success me-2"></i>{{ __('admin.user_management') }}
                                    </li>
                                    <li class="mb-1"><i
                                            class="fa-solid fa-check text-success me-2"></i>{{ __('admin.ip_address') }}</li>
                                    <li class="mb-1"><i class="fa-solid fa-check text-success me-2"></i>Security</li>
                                    <li class="mb-1"><i class="fa-solid fa-check text-success me-2"></i>Tracking</li>
                                </ul>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Stats -->
        <div class="card shadow-sm">
            <div class="card-header" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                    <i class="fa-solid fa-info-circle me-2"></i>{{ __('admin.quick_report') }}
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info"
                            style="background: #e0f2fe; border: none; border-left: 4px solid #0284c7;">
                            <h6 class="fw-bold mb-2"><i class="fa-solid fa-lightbulb me-2"></i>{{ __('admin.how_to_use') }}
                            </h6>
                            <ul class="mb-0 small">
                                <li>{{ __('admin.how_to_use_1') }}</li>
                                <li>{{ __('admin.how_to_use_2') }}</li>
                                <li>{{ __('admin.how_to_use_3') }}</li>
                                <li>{{ __('admin.how_to_use_4') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print All Modal -->
    <div class="modal fade" id="printAllModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 16px;">
                <div class="modal-header" style="background: #6f5849; color: white; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title">
                        <i class="fa-solid fa-file-invoice me-2"></i>{{ __('admin.print_all_reports') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route($routePrefix . 'reports.print-all') }}" method="GET" id="printForm">
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #6f5849;">
                                <i class="fa-solid fa-calendar-days me-2"></i>{{ __('admin.select_period') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="fa-solid fa-clock text-muted"></i></span>
                                <select name="period" id="period" class="form-select border-start-0 ps-0">
                                    <option value="today">{{ __('admin.today') }}</option>
                                    <option value="week">{{ __('admin.this_week') }}</option>
                                    <option value="month" selected>{{ __('admin.this_month') }}</option>
                                    <option value="year">{{ __('admin.this_year') }}</option>
                                    <option value="custom">{{ __('admin.custom_range') }}</option>
                                </select>
                            </div>
                        </div>

                        <div id="customRange" style="display: none;" class="mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: #6f5849;">
                                        <i class="fa-regular fa-calendar-plus me-2"></i>{{ __('admin.start_date') }}
                                    </label>
                                    <input type="date" name="start_date" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold" style="color: #6f5849;">
                                        <i class="fa-regular fa-calendar-minus me-2"></i>{{ __('admin.end_date') }}
                                    </label>
                                    <input type="date" name="end_date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold" style="color: #6f5849;">
                                <i
                                    class="fa-solid fa-layer-group me-2"></i>{{ __('admin.report_sections') ?? 'Report Sections' }}
                            </label>
                            <div class="list-group list-group-flush border rounded-12">
                                <label class="list-group-item d-flex align-items-center py-2">
                                    <input class="form-check-input me-3" type="checkbox" name="modules[]" value="finance"
                                        checked>
                                    <div>
                                        <div class="fw-bold small">Laporan Keuangan</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">Ringkasan KPI & Trend</small>
                                    </div>
                                </label>
                                <label class="list-group-item d-flex align-items-center py-2">
                                    <input class="form-check-input me-3" type="checkbox" name="modules[]" value="warehouse"
                                        checked>
                                    <div>
                                        <div class="fw-bold small">Laporan Gudang</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">Stok, Pergerakan &
                                            Valuasi</small>
                                    </div>
                                </label>
                                <label class="list-group-item d-flex align-items-center py-2">
                                    <input class="form-check-input me-3" type="checkbox" name="modules[]" value="cashier"
                                        checked>
                                    <div>
                                        <div class="fw-bold small">Laporan Kasir</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">Penjualan, Produk &
                                            Performa</small>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center"
                            style="background-color: #fff7ed; color: #9a3412;">
                            <i class="fa-solid fa-circle-info fa-lg me-3"></i>
                            <div>
                                {{ __('admin.comprehensive_report_warning') }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 rounded-bottom-4 px-4 pb-3">
                        <input type="hidden" name="format" id="exportFormat" value="">

                        <button type="button" class="btn btn-light text-muted border-0 px-4"
                            style="border-radius: 10px; padding: 0.6rem 1.25rem;"
                            data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>

                        <button type="submit"
                            onclick="document.getElementById('exportFormat').value='pdf'; document.getElementById('printForm').target='_blank';"
                            class="btn btn-outline-brown px-4 fw-bold"
                            style="border-radius: 10px; padding: 0.6rem 1.25rem;">
                            <i class="fa-solid fa-file-pdf me-2"></i> {{ __('admin.download_pdf') }}
                        </button>

                        <button type="submit"
                            onclick="document.getElementById('exportFormat').value='csv'; document.getElementById('printForm').target='_self';"
                            class="btn px-4 fw-bold"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); color: white; border-radius: 10px; padding: 0.6rem 1.25rem;">
                            <i class="fa-solid fa-file-csv me-2"></i> {{ __('admin.export_csv') ?? 'Export CSV' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('period').addEventListener('change', function () {
            const customRange = document.getElementById('customRange');
            if (this.value === 'custom') {
                customRange.style.display = 'block';
            } else {
                customRange.style.display = 'none';
            }
        });
    </script>
@endsection