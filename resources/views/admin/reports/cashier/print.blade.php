<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('admin.cashier_report') }} - ARTIKA</title>
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

        .text-success {
            color: #16a34a;
        }

        .text-primary {
            color: #0284c7;
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

        .badge-success {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #dcfce7;
        }

        .badge-primary {
            background-color: #f0f9ff;
            color: #0284c7;
            border: 1px solid #e0f2fe;
        }

        .badge-info {
            background-color: #f5f0ed;
            color: #6f5849;
            border: 1px solid #e0cec7;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('admin.cashier_report') }}</h1>
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
                <h3>{{ __('admin.total_sales') }}</h3>
                <div class="value text-success">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.transactions') }}</h3>
                <div class="value">{{ number_format($summary['total_transactions']) }} trx</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.avg_transaction') }}</h3>
                <div class="value">Rp {{ number_format($summary['average_transaction'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>{{ __('admin.payment_method') }}</h3>
                <div class="value" style="font-size: 11px;">
                    {{ __('admin.cash') }}: {{ $summary['cash_count'] }}<br>
                    {{ __('admin.non_cash') }}: {{ $summary['non_cash_count'] }}
                </div>
            </td>
        </tr>
    </table>

    <div class="section-title">{{ __('admin.top_selling_products') }}</div>
    <table class="data">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th>{{ __('admin.product_management') }}</th>
                <th width="15%" class="text-center">{{ __('admin.sold') }}</th>
                <th width="25%" class="text-right">{{ __('admin.revenue') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $product->name }}</strong><br>
                        <small>{{ $product->barcode }}</small>
                    </td>
                    <td class="text-center">{{ number_format($product->total_sold) }}</td>
                    <td class="text-right">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">{{ __('admin.cashier_performance') }}</div>
    <table class="data">
        <thead>
            <tr>
                <th>{{ __('admin.cashier') }}</th>
                <th width="20%" class="text-center">{{ __('admin.transactions') }}</th>
                <th width="25%" class="text-right">{{ __('admin.total_sales') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashierPerformance as $performance)
                <tr>
                    <td>
                        <strong>{{ $performance->user->name }}</strong><br>
                        <small>{{ $performance->user->role->name }}</small>
                    </td>
                    <td class="text-center">{{ $performance->transaction_count }}</td>
                    <td class="text-right">Rp {{ number_format($performance->total_sales, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">{{ __('admin.recent_transactions') }}</div>
    <table class="data">
        <thead>
            <tr>
                <th width="15%">{{ __('admin.invoice') }}</th>
                <th width="15%">{{ __('admin.date') }}</th>
                <th>{{ __('admin.cashier') }}</th>
                <th width="15%" class="text-center">{{ __('admin.payment_method') }}</th>
                <th width="20%" class="text-right">{{ __('admin.amount') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recentTransactions as $transaction)
                <tr>
                    <td><strong>{{ $transaction->invoice_no }}</strong></td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td class="text-center">
                        @php
                            $isCash = in_array(strtolower($transaction->payment_method), ['tunai', 'cash']);
                        @endphp
                        <span class="badge {{ $isCash ? 'badge-success' : 'badge-primary' }}">
                            {{ $isCash ? __('admin.cash') : __('admin.non_cash') }}
                        </span>
                    </td>
                    <td class="text-right">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak otomatis oleh Sistem ARTIKA POS pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>