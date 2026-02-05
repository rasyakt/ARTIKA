<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('admin.finance_report') }} - ARTIKA</title>
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
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('admin.finance_report') }}</h1>
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

    @if(in_array('summary', $sections))
        <table class="summary-grid">
            <tr>
                <td class="summary-box" width="33.33%">
                    <h3>{{ __('admin.gross_revenue') }}</h3>
                    <div class="value">Rp {{ number_format($summary['gross_revenue'], 0, ',', '.') }}</div>
                </td>
                <td class="summary-box" width="33.33%">
                    <h3>{{ __('admin.cogs') }}</h3>
                    <div class="value">Rp {{ number_format($summary['cogs'], 0, ',', '.') }}</div>
                </td>
                <td class="summary-box" width="33.33%">
                    <h3>{{ __('admin.gross_profit') }}</h3>
                    <div class="value text-success">Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}</div>
                </td>
            </tr>
            <tr>
                <td class="summary-box">
                    <h3>{{ __('admin.operational_expenses') }}</h3>
                    <div class="value text-danger">Rp {{ number_format($summary['total_expenses'], 0, ',', '.') }}</div>
                </td>
                <td class="summary-box">
                    <h3>{{ __('admin.stock_procurement') }}</h3>
                    <div class="value text-danger">Rp {{ number_format($summary['total_procurement'], 0, ',', '.') }}</div>
                </td>
                <td class="summary-box">
                    <h3>{{ __('admin.net_profit') }}</h3>
                    <div class="value {{ $summary['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                        Rp {{ number_format($summary['net_profit'], 0, ',', '.') }}
                    </div>
                </td>
            </tr>
        </table>
    @endif

    @if(in_array('quick_info', $sections))
        <div class="section-title">{{ __('admin.quick_info') }} & Profitability</div>
        <table class="summary-grid">
            <tr>
                <td class="summary-box" width="50%">
                    <h3>{{ __('admin.profit_margin') }}</h3>
                    <div class="value"
                        style="color: {{ $summary['profit_margin'] > 15 ? '#16a34a' : ($summary['profit_margin'] > 5 ? '#ca8a04' : '#dc2626') }}">
                        {{ number_format($summary['profit_margin'], 2) }}%
                    </div>
                </td>
                <td class="summary-box" width="50%">
                    <h3>Retur & Refund</h3>
                    <div class="value text-danger">
                        - Rp {{ number_format($summary['total_returns'], 0, ',', '.') }}
                    </div>
                </td>
            </tr>
        </table>
    @endif

    @if(in_array('daily_data', $sections))
        <div class="section-title">{{ __('admin.daily_profit') }}</div>
        <table class="data">
            <thead>
                <tr>
                    <th>{{ __('admin.date') }}</th>
                    <th class="text-right">{{ __('admin.gross_revenue') }}</th>
                    <th class="text-right">{{ __('admin.cogs') }}</th>
                    <th class="text-right">{{ __('admin.operational_expenses') }}</th>
                    <th class="text-right">{{ __('admin.stock_procurement') }}</th>
                    <th class="text-right">{{ __('admin.net_profit') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyData as $day)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($day['date'])->format('d M Y') }}</td>
                        <td class="text-right">Rp {{ number_format($day['revenue'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($day['cogs'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($day['expenses'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($day['procurement'], 0, ',', '.') }}</td>
                        <td class="text-right {{ $day['profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                            <strong>Rp {{ number_format($day['profit'], 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Laporan ini dicetak otomatis oleh Sistem ARTIKA POS pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>