<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('admin.complete_store_report') }} - ARTIKA</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 10px;
            margin: 0;
            padding: 20px;
        }

        .report-header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 4px double #85695a;
            padding-bottom: 15px;
        }

        .report-header h1 {
            font-size: 20px;
            color: #6f5849;
            margin: 10px 0 5px 0;
            text-transform: uppercase;
        }

        .report-header .company-name {
            font-size: 16px;
            color: #85695a;
            font-weight: bold;
        }

        .report-header .subtitle {
            font-size: 11px;
            color: #78716c;
        }

        .section-divider {
            margin: 30px 0 15px 0;
            padding: 10px;
            background-color: #85695a;
            color: white;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
        }

        .subsection-title {
            font-size: 12px;
            font-weight: bold;
            margin: 15px 0 10px 0;
            color: #6f5849;
            text-transform: uppercase;
            border-left: 4px solid #85695a;
            padding-left: 10px;
            background-color: #fdf8f6;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .summary-grid {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .summary-box {
            border: 1px solid #e0cec7;
            padding: 10px;
            text-align: center;
        }

        .summary-box h3 {
            margin: 0 0 5px 0;
            font-size: 8px;
            color: #78716c;
            text-transform: uppercase;
        }

        .summary-box .value {
            font-size: 14px;
            font-weight: bold;
            color: #6f5849;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.data th {
            background-color: #fdf8f6;
            color: #6f5849;
            text-align: left;
            padding: 6px;
            border: 1px solid #f2e8e5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        table.data td {
            padding: 6px;
            border: 1px solid #f2e8e5;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-danger {
            color: #dc2626;
        }

        .text-success {
            color: #16a34a;
        }

        .text-muted {
            color: #78716c;
            font-size: 9px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 2px solid #eee;
            padding-top: 15px;
        }

        .badge {
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            background-color: #f5f0ed;
            color: #6f5849;
            border: 1px solid #e0cec7;
        }

        .badge-danger {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
        }

        .badge-success {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #dcfce7;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="report-header">
        <div class="company-name">ARTIKA POS SYSTEM</div>
        <h1>{{ __('admin.complete_store_report') }}</h1>
        <div class="subtitle">{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</div>
        <div class="text-muted">Generated: {{ now()->format('d/m/Y H:i') }} | Confidential Document</div>
    </div>

    <!-- FINANCE SECTION -->
    <div class="section-divider">{{ __('admin.finance_reports_title') }}</div>
    <table class="summary-grid">
        <tr>
            <td class="summary-box" width="20%">
                <h3>{{ __('admin.gross_revenue') }}</h3>
                <div class="value">Rp {{ number_format($financeSummary['gross_revenue'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="20%">
                <h3>{{ __('admin.cogs') }}</h3>
                <div class="value">Rp {{ number_format($financeSummary['cogs'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="20%">
                <h3>{{ __('admin.operational_expenses') }}</h3>
                <div class="value text-danger">Rp {{ number_format($financeSummary['total_expenses'], 0, ',', '.') }}
                </div>
            </td>
            <td class="summary-box" width="20%">
                <h3>{{ __('admin.stock_procurement') }}</h3>
                <div class="value text-danger">Rp {{ number_format($financeSummary['total_procurement'], 0, ',', '.') }}
                </div>
            </td>
            <td class="summary-box" width="20%">
                <h3>{{ __('admin.net_profit') }}</h3>
                <div class="value {{ $financeSummary['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                    Rp {{ number_format($financeSummary['net_profit'], 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- WAREHOUSE SECTION -->
    <div class="section-divider">{{ __('admin.warehouse_management_report') }}</div>
    <table class="summary-grid">
        <tr>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.total_valuation') }}</h3>
                <div class="value">Rp {{ number_format($warehouseSummary['total_valuation'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.total_stock') }}</h3>
                <div class="value">{{ number_format($warehouseSummary['total_items']) }} unit</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.low_stock_items') }}</h3>
                <div class="value text-danger">{{ number_format($warehouseSummary['low_stock_count']) }} item</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.movements') }}</h3>
                <div class="value">
                    <span class="text-success">{{ $warehouseSummary['movements_in'] }}↑</span> /
                    <span class="text-danger">{{ $warehouseSummary['movements_out'] }}↓</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="subsection-title">{{ __('admin.top_moving_items') }}</div>
    <table class="data">
        <thead>
            <tr>
                <th width="8%">RANK</th>
                <th>PRODUCT</th>
                <th width="20%" class="text-center">{{ __('admin.total_movements') }}</th>
                <th width="20%" class="text-center">{{ __('common.quantity') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topMovers as $index => $mover)
                <tr>
                    <td class="text-center">#{{ $index + 1 }}</td>
                    <td><strong>{{ $mover->product->name }}</strong><br><small>{{ $mover->product->barcode }}</small></td>
                    <td class="text-center"><span class="badge">{{ $mover->total_movements }}</span></td>
                    <td class="text-center text-success"><strong>{{ number_format($mover->total_quantity) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- CASHIER SECTION -->
    <div class="section-divider">{{ __('admin.cashier_sales_report') }}</div>
    <table class="summary-grid">
        <tr>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.total_sales') }}</h3>
                <div class="value text-success">Rp {{ number_format($cashierSummary['total_sales'], 0, ',', '.') }}
                </div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.transactions') }}</h3>
                <div class="value">{{ number_format($cashierSummary['total_transactions']) }} trx</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.cash_sales') }}</h3>
                <div class="value">Rp {{ number_format($cashierSummary['cash_sales'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.non_cash_sales') }}</h3>
                <div class="value">Rp {{ number_format($cashierSummary['non_cash_sales'], 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <div class="subsection-title">{{ __('admin.top_selling_products') }}</div>
    <table class="data">
        <thead>
            <tr>
                <th width="8%">RANK</th>
                <th>PRODUCT</th>
                <th width="15%" class="text-center">{{ __('admin.sold') }}</th>
                <th width="25%" class="text-right">{{ __('admin.revenue') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $index => $product)
                <tr>
                    <td class="text-center">#{{ $index + 1 }}</td>
                    <td><strong>{{ $product->name }}</strong><br><small>{{ $product->barcode }}</small></td>
                    <td class="text-center"><span
                            class="badge badge-success">{{ number_format($product->total_sold) }}</span></td>
                    <td class="text-right text-success"><strong>Rp
                            {{ number_format($product->total_revenue, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- AUDIT SECTION -->
    <div class="section-divider">{{ __('admin.system_audit_logs') }} (Recent 20)</div>
    <table class="data">
        <thead>
            <tr>
                <th width="15%">DATE & TIME</th>
                <th width="15%">USER</th>
                <th width="12%">ACTION</th>
                <th width="12%">TARGET</th>
                <th width="15%">NETWORK</th>
                <th>DETAILS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($auditLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $log->user?->name ?? 'System' }}</strong></td>
                    <td><span class="badge">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span></td>
                    <td>{{ $log->model_type }} #{{ $log->model_id }}</td>
                    <td><code>{{ $log->ip_address }}</code></td>
                    <td>{{ Str::limit($log->notes, 40) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>ARTIKA POS SYSTEM</strong> - Consolidated Report</p>
        <p>This document is generated automatically by the system. Page totals may reflect the specified period only.
        </p>
        <p>Printed on: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>