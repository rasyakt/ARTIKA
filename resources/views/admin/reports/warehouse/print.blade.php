<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.warehouse_report') }} - {{ $startDate->format('d M Y') }} to {{ $endDate->format('d M Y') }}
    </title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }

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
        .summary-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 0;
            margin: 0 -12px 30px -12px;
            table-layout: fixed;
        }

        .summary-box {
            padding: 15px;
            border: 2px solid #e0cec7;
            background: #fdf8f6;
            border-radius: 8px;
            vertical-align: top;
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

        .badge-warning {
            background: #fbbf24;
            color: #78350f;
        }

        .badge-info {
            background: #e0cec7;
            color: #6f5849;
        }

        .badge-danger {
            background: #dc2626;
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
                <i class="fa-solid fa-print"></i> {{ __('admin.print_report') }}
            </button>
            <button onclick="window.close()"
                style="padding: 10px 24px; font-size: 14px; cursor: pointer; background: rgba(255,255,255,0.2); color: white; border: 2px solid white; border-radius: 6px; font-weight: 600;">
                <i class="fa-solid fa-times"></i> {{ __('admin.close') }}
            </button>
        </div>
    @endif

    <div class="header">
        <h1><i class="fa-solid fa-warehouse"></i> {{ __('admin.warehouse_report') }}</h1>
        <div class="subtitle">Period: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</div>
        <div class="meta">{{ __('admin.generated') }}: {{ now()->format('d M Y H:i') }} | ARTIKA POS System</div>
    </div>

    <table class="summary-table">
        <tr>
            <td class="summary-box">
                <h3>{{ __('admin.total_valuation') }}</h3>
                <div class="value">Rp {{ number_format($summary['total_valuation'], 0, ',', '.') }}</div>
                <div class="label">{{ __('admin.based_on_cost') }}</div>
            </td>
            <td class="summary-box">
                <h3>{{ __('admin.total_stock') }}</h3>
                <div class="value">{{ number_format($summary['total_items']) }}</div>
                <div class="label">{{ __('admin.units_in_warehouse') }}</div>
            </td>
            <td class="summary-box">
                <h3>{{ __('admin.low_stock_alerts') }}</h3>
                <div class="value">{{ number_format($summary['low_stock_count']) }}</div>
                <div class="label">{{ __('admin.items_need_restocking') }}</div>
            </td>
            <td class="summary-box">
                <h3>{{ __('admin.movements') }}</h3>
                <div class="value">
                    <span style="color: #16a34a;">↓{{ $summary['movements_in'] }}</span> /
                    <span style="color: #dc2626;">↑{{ $summary['movements_out'] }}</span>
                </div>
                <div class="label">{{ __('admin.in_out_period') }}</div>
            </td>
        </tr>
    </table>

    <div class="section">
        <h2>{{ __('admin.top_moving_items') }}</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th>{{ __('admin.product_management') }}</th>
                    <th style="width: 15%;" class="text-center">{{ __('admin.movements') }}</th>
                    <th style="width: 15%;" class="text-center">{{ __('admin.quantity') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topMovers as $index => $mover)
                    <tr>
                        <td class="text-center"><strong>{{ $index + 1 }}</strong></td>
                        <td>
                            <strong>{{ $mover->product->name }}</strong>
                            <div class="text-muted">{{ $mover->product->barcode }}</div>
                        </td>
                        <td class="text-center">{{ $mover->total_movements }}</td>
                        <td class="text-center"><strong>{{ $mover->total_quantity }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 20px;">No data available</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>{{ __('admin.low_stock_items') }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.product_management') }}</th>
                    <th style="width: 15%;" class="text-center">{{ __('admin.min_stock') }}</th>
                    <th style="width: 15%;" class="text-center">{{ __('admin.current_stock') }}</th>
                    <th style="width: 15%;" class="text-center">{{ __('admin.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lowStockItems as $item)
                    <tr>
                        <td><strong>{{ $item->name }}</strong></td>
                        <td class="text-center">{{ $item->min_stock }}</td>
                        <td class="text-center"><strong style="color: #dc2626;">{{ $item->current_stock }}</strong></td>
                        <td class="text-center">
                            <span class="badge badge-danger">{{ __('admin.needs_restock') }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center" style="padding: 20px; color: #16a34a;">✓
                            {{ __('admin.all_well_stocked') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>{{ __('admin.recent_stock_movements') }}</h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 12%;">{{ __('admin.date') }}</th>
                    <th>{{ __('admin.product_management') }}</th>
                    <th style="width: 10%;" class="text-center">{{ __('admin.activity_type') }}</th>
                    <th style="width: 10%;" class="text-center">{{ __('admin.quantity') }}</th>
                    <th style="width: 15%;">{{ __('admin.reference') }}</th>
                    <th style="width: 12%;">{{ __('admin.user') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $movement)
                    <tr>
                        <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $movement->product->name }}</strong></td>
                        <td class="text-center">
                            @if($movement->type == 'in')
                                <span class="badge badge-success">IN</span>
                            @elseif($movement->type == 'out')
                                <span class="badge badge-warning">OUT</span>
                            @else
                                <span class="badge badge-info">ADJ</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <strong style="color: {{ $movement->quantity_change > 0 ? '#16a34a' : '#dc2626' }};">
                                {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                            </strong>
                        </td>
                        <td>{{ $movement->reference ?? 'N/A' }}</td>
                        <td>{{ $movement->user->name ?? 'System' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 20px;">{{ __('admin.no_movements_found') }}</td>
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
                        <td colspan="5" class="text-center" style="padding: 20px;">{{ __('admin.no_audit_logs') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>ARTIKA POS System</strong> - {{ __('admin.warehouse_report') }}</p>
        <p>{{ __('admin.footer_generated_by') }}</p>
    </div>

</body>

</html>