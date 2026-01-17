<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Report - {{ $startDate->format('d M Y') }} to {{ $endDate->format('d M Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #4b382f;
            padding: 20px;
            background: #ffffff;
        }

        .header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #85695a;
        }

        .header h1 {
            font-size: 28px;
            color: #6f5849;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .header .subtitle {
            font-size: 13px;
            color: #78716c;
            margin-bottom: 5px;
        }

        .header .meta {
            font-size: 11px;
            color: #a8a29e;
        }

        /* Summary Grid */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .summary-box {
            border: 2px solid #e0cec7;
            padding: 15px;
            background: #fdf8f6;
            border-radius: 8px;
        }

        .summary-box h3 {
            font-size: 10px;
            color: #78716c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .summary-box .value {
            font-size: 20px;
            font-weight: 700;
            color: #6f5849;
            margin-bottom: 5px;
        }

        .summary-box .label {
            font-size: 9px;
            color: #a8a29e;
        }

        /* Sections */
        .section {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }

        .section h2 {
            font-size: 16px;
            border-left: 4px solid #85695a;
            padding-left: 12px;
            margin-bottom: 15px;
            text-transform: uppercase;
            color: #6f5849;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(133, 105, 90, 0.1);
        }

        table th,
        table td {
            border: 1px solid #e0cec7;
            padding: 10px 8px;
            text-align: left;
        }

        table th {
            background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);
            color: white;
            font-weight: 600;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table tr:nth-child(even) {
            background-color: #fdf8f6;
        }

        /* Utilities */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #78716c;
            font-size: 10px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: #16a34a;
            color: white;
        }

        .badge-info {
            background: #e0cec7;
            color: #6f5849;
        }

        .badge-primary {
            background: #0284c7;
            color: white;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            .section {
                page-break-inside: avoid;
            }
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e0cec7;
            text-align: center;
            color: #a8a29e;
            font-size: 10px;
        }
    </style>
</head>

<body @if(!isset($isPdf) || !$isPdf) onload="window.print()" @endif>

    @if(!isset($isPdf) || !$isPdf)
        <div class="no-print"
            style="margin-bottom: 20px; padding: 15px; background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); text-align: center; border-radius: 8px;">
            <button onclick="window.print()"
                style="padding: 10px 24px; font-size: 14px; cursor: pointer; background: white; border: none; border-radius: 6px; font-weight: 600; margin-right: 10px; color: #6f5849;">
                <i class="fa-solid fa-print"></i> Print Report
            </button>
            <button onclick="window.close()"
                style="padding: 10px 24px; font-size: 14px; cursor: pointer; background: rgba(255,255,255,0.2); color: white; border: 2px solid white; border-radius: 6px; font-weight: 600;">
                <i class="fa-solid fa-times"></i> Close
            </button>
        </div>
    @endif

    <div class="header">
        <h1><i class="fa-solid fa-cash-register"></i> {{ __('admin.cashier_reports_title') }}</h1>
        <div class="subtitle">{{ __('admin.period') }}: {{ $startDate->format('d M Y') }} -
            {{ $endDate->format('d M Y') }}
        </div>
        <div class="meta">{{ __('admin.generated') }}: {{ now()->format('d M Y H:i') }} | ARTIKA POS System</div>
    </div>

    <div class="summary-grid">
        <div class="summary-box">
            <h3>{{ __('admin.total_sales') }}</h3>
            <div class="value">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</div>
            <div class="label">{{ number_format($summary['total_transactions']) }} {{ __('admin.transactions') }}</div>
        </div>
        <div class="summary-box">
            <h3>{{ __('admin.avg_transaction') }}</h3>
            <div class="value">Rp {{ number_format($summary['average_transaction'], 0, ',', '.') }}</div>
            <div class="label">{{ __('admin.per_transaction') }}</div>
        </div>
        <div class="summary-box">
            <h3>{{ __('admin.cash_sales') }}</h3>
            <div class="value">Rp {{ number_format($summary['cash_sales'], 0, ',', '.') }}</div>
            <div class="label">{{ $summary['cash_count'] }} {{ __('admin.transactions') }}</div>
        </div>
        <div class="summary-box">
            <h3>{{ __('admin.non_cash_sales') }}</h3>
            <div class="value">Rp {{ number_format($summary['non_cash_sales'], 0, ',', '.') }}</div>
            <div class="label">{{ $summary['non_cash_count'] }} {{ __('admin.transactions') }}</div>
        </div>
    </div>

    <div class="section">
        <h2>{{ __('admin.top_selling_products') }}</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>{{ __('admin.product_management') }}</th>
                    <th style="width: 15%;" class="text-center">{{ __('admin.sold') }}</th>
                    <th style="width: 20%;" class="text-right">{{ __('admin.revenue') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $index => $product)
                    <tr>
                        <td class="text-center"><strong>{{ $index + 1 }}</strong></td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            <div class="text-muted">{{ $product->barcode }}</div>
                        </td>
                        <td class="text-center">{{ $product->total_sold }}</td>
                        <td class="text-right"><strong>Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 20px;">No sales data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>{{ __('admin.cashier_performance') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.cashier') }}</th>
                    <th style="width: 20%;" class="text-center">{{ __('admin.transactions') }}</th>
                    <th style="width: 25%;" class="text-right">{{ __('admin.total_sales') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cashierPerformance as $performance)
                    <tr>
                        <td>
                            <strong>{{ $performance->user->name }}</strong>
                            <div class="text-muted">{{ $performance->user->role->name }}</div>
                        </td>
                        <td class="text-center">{{ $performance->transaction_count }}</td>
                        <td class="text-right"><strong>Rp
                                {{ number_format($performance->total_sales, 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center" style="padding: 20px;">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>{{ __('admin.recent_transactions') }}</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 15%;">{{ __('admin.invoice') }}</th>
                    <th style="width: 15%;">{{ __('admin.date') }}</th>
                    <th>{{ __('admin.cashier') }}</th>
                    <th style="width: 12%;" class="text-center">{{ __('admin.payment_method') }}</th>
                    <th style="width: 15%;" class="text-right">{{ __('admin.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions as $transaction)
                    <tr>
                        <td><strong>{{ $transaction->invoice_no }}</strong></td>
                        <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $transaction->user->name }}</td>
                        <td class="text-center">
                            @if(strtolower($transaction->payment_method) == 'tunai' || strtolower($transaction->payment_method) == 'cash')
                                <span class="badge badge-success">{{ __('admin.cash') }}</span>
                            @else
                                <span class="badge badge-primary">{{ __('admin.non_cash') }}</span>
                            @endif
                        </td>
                        <td class="text-right"><strong>Rp
                                {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px;">No transactions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>{{ __('admin.audit_log') }}</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">{{ __('admin.date') }}</th>
                    <th style="width: 15%;">{{ __('admin.user') }}</th>
                    <th style="width: 12%;" class="text-center">{{ __('admin.action') }}</th>
                    <th style="width: 15%;">{{ __('admin.entity') }}</th>
                    <th>{{ __('admin.details') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($auditLogs as $log)
                    <tr>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $log->user->name ?? 'System' }}</strong>
                            <div class="text-muted">{{ $log->user->role->name ?? '' }}</div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-info">{{ strtoupper($log->action) }}</span>
                        </td>
                        <td>
                            <strong>{{ $log->model_type }}</strong>
                            <span class="text-muted">#{{ $log->model_id }}</span>
                        </td>
                        <td>{{ $log->notes }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px;">No audit logs found for this period</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>ARTIKA POS System</strong> - Cashier Performance Report</p>
        <p>This is a computer-generated report. No signature required.</p>
    </div>

</body>

</html>