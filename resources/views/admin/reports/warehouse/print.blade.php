<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('admin.warehouse_report') }} - ARTIKA</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 11px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #85695a;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            color: #6f5849;
            margin: 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #85695a;
        }

        .report-meta {
            margin-bottom: 20px;
            width: 100%;
        }

        .report-meta td {
            padding: 2px 0;
        }

        .section-title {
            font-size: 13px;
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
            font-size: 9px;
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
            padding: 8px;
            border: 1px solid #f2e8e5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        table.data td {
            padding: 8px;
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

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
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

        .badge-info {
            background-color: #f0f9ff;
            color: #0369a1;
            border: 1px solid #e0f2fe;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('admin.warehouse_report') }}</h1>
        <p>ARTIKA POS SYSTEM</p>
    </div>

    <table class="report-meta">
        <tr>
            <td width="15%"><strong>{{ __('admin.period') }}</strong></td>
            <td width="35%">: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</td>
            <td width="15%"><strong>{{ __('admin.generated') }}</strong></td>
            <td width="35%">: {{ now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <table class="summary-grid">
        <tr>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.total_valuation') }}</h3>
                <div class="value">Rp {{ number_format($summary['total_valuation'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.total_stock') }}</h3>
                <div class="value">{{ number_format($summary['total_items']) }} unit</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.low_stock_alerts') }}</h3>
                <div class="value text-danger">{{ number_format($summary['low_stock_count']) }} item</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.movements') }}</h3>
                <div class="value">
                    <span class="text-success">{{ $summary['movements_in'] }}↑</span> /
                    <span class="text-danger">{{ $summary['movements_out'] }}↓</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">{{ __('admin.top_moving_items') }}</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>{{ __('admin.product_management') }}</th>
                <th width="20%" class="text-center">{{ __('admin.total_movements') }}</th>
                <th width="20%" class="text-center">{{ __('admin.quantity') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topMovers as $index => $mover)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $mover->product->name }}</strong><br>
                        <small>{{ $mover->product->barcode }}</small>
                    </td>
                    <td class="text-center">{{ $mover->total_movements }}</td>
                    <td class="text-center">{{ number_format($mover->total_quantity) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">{{ __('admin.low_stock_items') }}</div>
    <table class="data">
        <thead>
            <tr>
                <th>{{ __('admin.product_management') }}</th>
                <th width="20%" class="text-center">{{ __('admin.min_stock') }}</th>
                <th width="20%" class="text-center">{{ __('admin.current_stock') }}</th>
                <th width="20%" class="text-center">{{ __('admin.status') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lowStockItems as $item)
                <tr>
                    <td><strong>{{ $item->name }}</strong></td>
                    <td class="text-center">{{ $item->min_stock }}</td>
                    <td class="text-center text-danger"><strong>{{ $item->current_stock }}</strong></td>
                    <td class="text-center">
                        <span class="badge badge-danger">{{ __('admin.needs_restock') }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">{{ __('admin.all_well_stocked') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">{{ __('admin.recent_stock_movements') }}</div>
    <table class="data">
        <thead>
            <tr>
                <th width="15%">{{ __('admin.date') }}</th>
                <th>{{ __('admin.product_management') }}</th>
                <th width="10%" class="text-center">{{ __('admin.activity_type') }}</th>
                <th width="10%" class="text-center">{{ __('admin.quantity') }}</th>
                <th width="20%">{{ __('admin.reference') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
                <tr>
                    <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                    <td><strong>{{ $movement->product->name }}</strong></td>
                    <td class="text-center">
                        @if($movement->type == 'in')
                            <span class="badge badge-success">IN</span>
                        @elseif($movement->type == 'out')
                            <span class="badge badge-danger">OUT</span>
                        @else
                            <span class="badge badge-info">ADJ</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <strong class="{{ $movement->quantity_change > 0 ? 'text-success' : 'text-danger' }}">
                            {{ $movement->quantity_change > 0 ? '+' : '' }}{{ $movement->quantity_change }}
                        </strong>
                    </td>
                    <td>{{ $movement->reference ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak otomatis oleh Sistem ARTIKA POS pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>