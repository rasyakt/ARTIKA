<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.finance_report') }} - {{ $startDate->format('d/m/Y') }}</title>
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #6f5849;
            padding-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #6f5849;
            font-size: 24px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0;
            color: #85695a;
            font-size: 14px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            background: #fdf8f6;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f2e8e5;
        }

        .report-info div span {
            font-weight: bold;
            color: #6f5849;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-box {
            border: 1px solid #f2e8e5;
            padding: 15px;
            border-radius: 8px;
            background: white;
        }

        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            color: #85695a;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #4b382f;
        }

        .stat-value.success {
            color: #16a34a;
        }

        .stat-value.danger {
            color: #dc2626;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            background: #fdf8f6;
            color: #6f5849;
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #f2e8e5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #f2e8e5;
        }

        .text-end {
            text-align: right;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #85695a;
            border-top: 1px solid #f2e8e5;
            padding-top: 20px;
        }

        .profit-pos {
            color: #16a34a;
            font-weight: bold;
        }

        .profit-neg {
            color: #dc2626;
            font-weight: bold;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-before: always;
            }
        }

        /* Controls Panel */
        .no-print-panel {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn-print {
            background-color: #6f5849;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-close {
            background-color: #dc2626;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>
    @if(!isset($isPdf) || !$isPdf)
        <!-- Controls Panel -->
        <div class="no-print-panel no-print">
            <button onclick="window.print()" class="btn-print">
                <i class="fa-solid fa-print"></i> {{ __('common.print') }}
            </button>
            <button onclick="window.close()" class="btn-close">
                <i class="fa-solid fa-xmark"></i> {{ __('common.close') }}
            </button>
        </div>
    @endif
    <div class="header">
        <h1>ARTIKA POS</h1>
        <p>{{ __('admin.finance_report') }}</p>
    </div>

    <div class="report-info">
        <div>
            <span>{{ __('admin.select_period') }}:</span> {{ $startDate->format('d M Y') }} -
            {{ $endDate->format('d M Y') }}
        </div>
        <div>
            <span>{{ __('admin.generated') }}:</span> {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">{{ __('admin.gross_revenue') }}</div>
            <div class="stat-value">Rp {{ number_format($summary['gross_revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">{{ __('admin.cogs') }}</div>
            <div class="stat-value">Rp {{ number_format($summary['cogs'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">{{ __('admin.gross_profit') }}</div>
            <div class="stat-value success">Rp {{ number_format($summary['gross_profit'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">{{ __('admin.returns_refunds') }}</div>
            <div class="stat-value danger">Rp {{ number_format($summary['total_returns'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">{{ __('admin.operational_expenses') }}</div>
            <div class="stat-value danger">Rp {{ number_format($summary['total_expenses'], 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">{{ __('admin.net_profit') }}</div>
            <div class="stat-value {{ $summary['net_profit'] >= 0 ? 'success' : 'danger' }}">
                Rp {{ number_format($summary['net_profit'], 0, ',', '.') }}
            </div>
        </div>
        <div class="stat-box">
            <div class="stat-label">{{ __('admin.profit_margin') }}</div>
            <div class="stat-value">{{ number_format($summary['profit_margin'], 2) }}%</div>
        </div>
    </div>

    <h3 style="color: #6f5849; margin-bottom: 15px;">{{ __('admin.daily_profit') }}</h3>
    <table>
        <thead>
            <tr>
                <th>{{ __('admin.date') }}</th>
                <th class="text-end">{{ __('admin.gross_revenue') }}</th>
                <th class="text-end">{{ __('admin.cogs') }}</th>
                <th class="text-end">{{ __('admin.operational_expenses') }}</th>
                <th class="text-end">{{ __('admin.net_profit') }}</th>
                <th class="text-end">{{ __('admin.profit_margin') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyData as $day)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($day['date'])->format('d M Y') }}</td>
                    <td class="text-end">Rp {{ number_format($day['revenue'], 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($day['cogs'], 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($day['expenses'], 0, ',', '.') }}</td>
                    <td class="text-end {{ $day['profit'] >= 0 ? 'profit-pos' : 'profit-neg' }}">
                        Rp {{ number_format($day['profit'], 0, ',', '.') }}
                    </td>
                    <td class="text-end">
                        @php $margin = $day['revenue'] > 0 ? ($day['profit'] / $day['revenue']) * 100 : 0; @endphp
                        {{ number_format($margin, 1) }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>{{ __('admin.footer_generated_by') }}</p>
        <p>{{ __('admin.computer_generated') }}</p>
        <p>ARTIKA POS &copy; {{ date('Y') }}</p>
    </div>

    @if(!isset($isPdf) || !$isPdf)
        <script>
            window.onload = function () {
                if (window.location.search.indexOf('auto_print=true') > -1) {
                    window.print();
                }
            }
        </script>
    @endif
</body>

</html>