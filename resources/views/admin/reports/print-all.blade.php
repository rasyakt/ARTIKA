<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARTIKA Complete Report - {{ $startDate->format('d M Y') }} to {{ $endDate->format('d M Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 15mm;
            size: A4;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.4;
            color: #2c2c2c;
            background: #ffffff;
        }

        /* Header */
        .report-header {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px 0 15px 0;
            border-bottom: 4px double #85695a;
            background: linear-gradient(to bottom, #fdf8f6 0%, #ffffff 100%);
        }

        .report-header h1 {
            font-size: 24px;
            color: #6f5849;
            margin-bottom: 6px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .report-header .company-name {
            font-size: 18px;
            color: #85695a;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .report-header .subtitle {
            font-size: 11px;
            color: #78716c;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .report-header .meta {
            font-size: 9px;
            color: #a8a29e;
            margin-top: 8px;
        }

        /* Section Divider */
        .section-divider {
            margin: 25px 0 15px 0;
            padding: 10px 15px;
            background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);
            color: white;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            page-break-before: auto;
            box-shadow: 0 2px 4px rgba(133, 105, 90, 0.2);
        }

        .section-divider:first-of-type {
            page-break-before: avoid;
        }

        /* Subsection Title */
        .subsection-title {
            font-size: 11px;
            color: #6f5849;
            margin: 12px 0 8px 0;
            padding-left: 10px;
            border-left: 3px solid #85695a;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Summary Grid */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .summary-box {
            border: 1.5px solid #d4c4bb;
            padding: 10px;
            background: #faf9f8;
            border-radius: 5px;
            text-align: center;
        }

        .summary-box h3 {
            font-size: 8px;
            color: #78716c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .summary-box .value {
            font-size: 16px;
            font-weight: 700;
            color: #6f5849;
            margin-bottom: 3px;
            line-height: 1.2;
        }

        .summary-box .label {
            font-size: 7px;
            color: #a8a29e;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 8px;
            page-break-inside: auto;
        }

        table thead {
            display: table-header-group;
        }

        table th {
            background: #6f5849;
            color: white;
            font-weight: 600;
            padding: 7px 5px;
            border: 1px solid #5a4639;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        table td {
            border: 1px solid #e0cec7;
            padding: 6px 5px;
            vertical-align: top;
        }

        table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        table tr:nth-child(even) {
            background-color: #faf9f8;
        }

        /* Badge & Labels */
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: 600;
            background: #e0cec7;
            color: #6f5849;
            white-space: nowrap;
        }

        .badge-danger {
            background: #dc2626;
            color: white;
        }

        .badge-success {
            background: #16a34a;
            color: white;
        }

        .text-muted {
            color: #78716c;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .fw-bold {
            font-weight: 700;
        }

        code {
            background: #f5f0ed;
            padding: 1px 3px;
            border-radius: 2px;
            font-family: 'Courier New', monospace;
            font-size: 7px;
            color: #6f5849;
        }

        /* Footer */
        .report-footer {
            margin-top: 25px;
            padding-top: 12px;
            border-top: 2px solid #e0cec7;
            text-align: center;
            color: #a8a29e;
            font-size: 8px;
            line-height: 1.6;
        }

        .report-footer strong {
            color: #6f5849;
        }

        /* Print Optimizations */
        @media print {
            body {
                background: white;
            }

            .no-print {
                display: none !important;
            }

            .section-divider {
                page-break-after: avoid;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 15px;
            color: #a8a29e;
            font-style: italic;
        }
    </style>
</head>

<body onload="window.print()">

    <!-- Print Controls -->
    <div class="no-print"
        style="position: fixed; top: 10px; right: 10px; z-index: 1000; background: white; padding: 10px; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
        <button onclick="window.print()"
            style="padding: 8px 16px; background: #6f5849; color: white; border: none; border-radius: 4px; cursor: pointer; margin-right: 5px; font-weight: 600;">
            📄 Print
        </button>
        <button onclick="window.close()"
            style="padding: 8px 16px; background: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">
            ✕ Close
        </button>
    </div>

    <!-- Header -->
    <!-- Header -->
    <div class="report-header">
        <div class="company-name">🏪 ARTIKA POS SYSTEM</div>
        <h1>{{ __('admin.complete_store_report') }}</h1>
        <div class="subtitle">{{ __('admin.warehouse_reports_subtitle') }}</div>
        <div class="subtitle">{{ __('admin.report_period') }}: {{ $startDate->format('d M Y') }} -
            {{ $endDate->format('d M Y') }}</div>
        <div class="meta">{{ __('admin.generated') }}: {{ now()->format('d M Y H:i:s') }} WIB |
            {{ __('admin.confidential_document') }}</div>
    </div>

    <!-- ============================================ -->
    <!-- WAREHOUSE REPORT SECTION -->
    <!-- ============================================ -->
    <div class="section-divider">📦 {{ __('admin.warehouse_management_report') }}</div>

    <div class="summary-grid">
        <div class="summary-box">
            <h3>💰 {{ __('admin.total_valuation') }}</h3>
            <div class="value" style="font-size: 14px;">Rp
                {{ number_format($warehouseSummary['total_valuation'], 0, ',', '.') }}
            </div>
            <div class="label">{{ __('admin.inventory_value') }}</div>
        </div>
        <div class="summary-box">
            <h3>📊 {{ __('admin.total_stock') }}</h3>
            <div class="value">{{ number_format($warehouseSummary['total_items']) }}</div>
            <div class="label">{{ __('admin.units_available') }}</div>
        </div>
        <div class="summary-box">
            <h3>⚠️ {{ __('admin.low_stock_items') }}</h3>
            <div class="value">{{ number_format($warehouseSummary['low_stock_count']) }}</div>
            <div class="label">{{ __('admin.need_restock') }}</div>
        </div>
        <div class="summary-box">
            <h3>🔄 {{ __('admin.movements') }}</h3>
            <div class="value" style="font-size: 14px;">
                <span style="color: #16a34a;">{{ $warehouseSummary['movements_in'] }}↓</span>
                <span style="color: #dc2626;">{{ $warehouseSummary['movements_out'] }}↑</span>
            </div>
            <div class="label">{{ __('admin.in_out') }}</div>
        </div>
    </div>

    <div class="subsection-title">🏆 Top Moving Items</div>
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Rank</th>
                <th>Product Name</th>
                <th style="width: 18%;" class="text-center">Total Movements</th>
                <th style="width: 18%;" class="text-center">Quantity</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topMovers as $index => $mover)
                <tr>
                    <td class="text-center">
                        <strong style="font-size: 10px; color: #6f5849;">#{{ $index + 1 }}</strong>
                    </td>
                    <td>
                        <div class="fw-bold" style="color: #6f5849;">{{ $mover->product->name }}</div>
                        <div class="text-muted" style="font-size: 7px;">{{ $mover->product->barcode }}</div>
                    </td>
                    <td class="text-center"><span class="badge">{{ $mover->total_movements }}</span></td>
                    <td class="text-center fw-bold" style="color: #16a34a;">{{ number_format($mover->total_quantity) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-state">No movement data available for this period</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="subsection-title">⚠️ Low Stock Alert Items</div>
    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th style="width: 18%;" class="text-center">Min Stock</th>
                <th style="width: 18%;" class="text-center">Current</th>
                <th style="width: 18%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lowStockItems as $item)
                <tr>
                    <td class="fw-bold" style="color: #6f5849;">{{ $item->name }}</td>
                    <td class="text-center">{{ $item->min_stock }}</td>
                    <td class="text-center fw-bold" style="color: #dc2626;">{{ $item->current_stock }}</td>
                    <td class="text-center"><span class="badge badge-danger">URGENT RESTOCK</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-state" style="color: #16a34a;">✓ All items are well stocked</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ============================================ -->
    <!-- CASHIER REPORT SECTION -->
    <!-- ============================================ -->
    <div class="section-divider">💰 {{ __('admin.cashier_sales_report') }}</div>

    <div class="summary-grid">
        <div class="summary-box">
            <h3>💵 {{ __('admin.total_sales') }}</h3>
            <div class="value" style="font-size: 14px;">Rp
                {{ number_format($cashierSummary['total_sales'], 0, ',', '.') }}
            </div>
            <div class="label">{{ $cashierSummary['total_transactions'] }} {{ __('admin.transactions') }}</div>
        </div>
        <div class="summary-box">
            <h3>📈 {{ __('admin.avg_transaction') }}</h3>
            <div class="value" style="font-size: 14px;">Rp
                {{ number_format($cashierSummary['average_transaction'], 0, ',', '.') }}
            </div>
            <div class="label">{{ __('admin.per_transaction') }}</div>
        </div>
        <div class="summary-box">
            <h3>💵 {{ __('admin.cash_sales') }}</h3>
            <div class="value" style="font-size: 14px;">Rp
                {{ number_format($cashierSummary['cash_sales'], 0, ',', '.') }}
            </div>
            <div class="label">{{ $cashierSummary['cash_count'] }} {{ __('admin.transactions') }}</div>
        </div>
        <div class="summary-box">
            <h3>💳 {{ __('admin.non_cash_sales') }}</h3>
            <div class="value" style="font-size: 14px;">Rp
                {{ number_format($cashierSummary['non_cash_sales'], 0, ',', '.') }}
            </div>
            <div class="label">{{ $cashierSummary['non_cash_count'] }} {{ __('admin.transactions') }}</div>
        </div>
    </div>

    <div class="subsection-title">🏆 Top Selling Products</div>
    <table>
        <thead>
            <tr>
                <th style="width: 8%;">Rank</th>
                <th>Product Name</th>
                <th style="width: 15%;" class="text-center">Units Sold</th>
                <th style="width: 22%;" class="text-end">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts as $index => $product)
                <tr>
                    <td class="text-center">
                        <strong style="font-size: 10px; color: #6f5849;">#{{ $index + 1 }}</strong>
                    </td>
                    <td>
                        <div class="fw-bold" style="color: #6f5849;">{{ $product->name }}</div>
                        <div class="text-muted" style="font-size: 7px;">{{ $product->barcode }}</div>
                    </td>
                    <td class="text-center"><span
                            class="badge badge-success">{{ number_format($product->total_sold) }}</span></td>
                    <td class="text-end fw-bold" style="color: #16a34a;">Rp
                        {{ number_format($product->total_revenue, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="empty-state">No sales data available for this period</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="subsection-title">👥 Cashier Performance</div>
    <table>
        <thead>
            <tr>
                <th>Cashier Name</th>
                <th style="width: 22%;" class="text-center">Transactions</th>
                <th style="width: 28%;" class="text-end">Total Sales</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cashierPerformance as $performance)
                <tr>
                    <td>
                        <div class="fw-bold" style="color: #6f5849;">{{ $performance->user->name }}</div>
                        <div class="text-muted" style="font-size: 7px;">{{ $performance->user->role->name }}</div>
                    </td>
                    <td class="text-center"><span class="badge">{{ number_format($performance->transaction_count) }}</span>
                    </td>
                    <td class="text-end fw-bold" style="color: #16a34a;">Rp
                        {{ number_format($performance->total_sales, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="empty-state">No cashier activity for this period</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- ============================================ -->
    <!-- AUDIT LOGS SECTION -->
    <!-- ============================================ -->
    <div class="section-divider">📋 SYSTEM AUDIT LOGS (Recent 20)</div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Date & Time</th>
                <th style="width: 14%;">User</th>
                <th style="width: 13%;">Action</th>
                <th style="width: 12%;">Target</th>
                <th style="width: 11%;">IP Address</th>
                <th style="width: 13%;">Device</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @forelse($auditLogs as $log)
                <tr>
                    <td class="text-center">
                        <div class="fw-bold" style="font-size: 8px;">{{ $log->created_at->format('d/m/Y') }}</div>
                        <div class="text-muted" style="font-size: 7px;">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold" style="color: #6f5849;">{{ $log->user?->name ?? 'System' }}</div>
                        <div class="text-muted" style="font-size: 7px;">{{ $log->user?->role->name ?? '' }}</div>
                        @if($log->user && $log->user->role && $log->user->role->name === 'cashier')
                            <div style="font-size: 7px; color: #78716c;">NIS: {{ $log->user->nis ?? '-' }}</div>
                        @endif
                    </td>
                    <td><span class="badge"
                            style="font-size: 6px;">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span></td>
                    <td>
                        <div style="font-size: 8px; font-weight: 600;">{{ $log->model_type ?? '-' }}</div>
                        <div class="text-muted" style="font-size: 6px;">#{{ $log->model_id }}</div>
                    </td>
                    <td><code style="font-size: 6px;">{{ $log->ip_address }}</code></td>
                    <td style="font-size: 7px;">{{ Str::limit($log->device_name ?? 'Unknown', 25) }}</td>
                    <td style="font-size: 7px;">{{ Str::limit($log->notes ?? '-', 40) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">No audit logs for this period</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="report-footer">
        <div style="margin-bottom: 5px;"><strong>ARTIKA POS SYSTEM</strong> - Complete Store Performance Report</div>
        <div>This is a computer-generated comprehensive report combining Warehouse, Cashier, and System Audit data.
        </div>
        <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e0cec7;">
            📊 Report Period: <strong>{{ $startDate->format('d M Y') }}</strong> to
            <strong>{{ $endDate->format('d M Y') }}</strong> |
            📅 Generated: <strong>{{ now()->format('d M Y H:i:s') }}</strong> |
            🔒 <strong>CONFIDENTIAL</strong>
        </div>
    </div>

</body>

</html>