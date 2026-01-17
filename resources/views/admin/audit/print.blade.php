<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.logs_report') }} - {{ $startDate }} to {{ $endDate }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        @page {
            margin: 10mm;
            size: A4 landscape;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #2c2c2c;
            padding: 20px;
            background: #ffffff;
        }

        .header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #85695a;
            text-align: center;
        }

        .header h1 {
            font-size: 22px;
            color: #6f5849;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 12px;
            color: #78716c;
            margin-bottom: 3px;
        }

        .summary-grid {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 20px;
        }

        .summary-box {
            flex: 1;
            border: 1px solid #d4c4bb;
            padding: 10px;
            background: #faf9f8;
            border-radius: 6px;
            text-align: center;
        }

        .summary-box h3 {
            font-size: 9px;
            color: #78716c;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-box .value {
            font-size: 18px;
            font-weight: 700;
            color: #6f5849;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table th {
            background: #6f5849;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            padding: 8px 5px;
            border: 1px solid #5a4639;
            font-size: 9px;
            text-align: left;
        }

        table td {
            border: 1px solid #e0cec7;
            padding: 6px 5px;
            vertical-align: top;
        }

        table tr:nth-child(even) {
            background-color: #faf9f8;
        }

        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: 600;
            background: #e0cec7;
            color: #6f5849;
        }

        .user-name {
            font-weight: 700;
            color: #6f5849;
        }

        .text-muted {
            color: #78716c;
            font-size: 8px;
        }

        code {
            background: #f5f0ed;
            padding: 1px 3px;
            border-radius: 2px;
            font-family: monospace;
            font-size: 8px;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #e0cec7;
            text-align: center;
            color: #a8a29e;
            font-size: 9px;
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

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    @if(!isset($isPdf) || !$isPdf)
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
        <h1>📋 {{ __('admin.logs_report') }}</h1>
        <div class="subtitle">{{ __('admin.period') }}: {{ $startDate }} - {{ $endDate }}</div>
        <div class="text-muted">Generated: {{ now()->format('d M Y H:i:s') }} | ARTIKA POS System</div>
    </div>

    <div class="summary-grid">
        <div class="summary-box">
            <h3>Total Logs</h3>
            <div class="value">{{ number_format($summary['total_logs']) }}</div>
        </div>
        <div class="summary-box">
            <h3>Transactions</h3>
            <div class="value">{{ number_format($summary['total_transactions']) }}</div>
        </div>
        <div class="summary-box">
            <h3>Total Amount</h3>
            <div class="value">Rp{{ number_format($summary['total_amount'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-box">
            <h3>Unique Users</h3>
            <div class="value">{{ count($summary['by_user']) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 12%;">Date & Time</th>
                <th style="width: 15%;">User Info</th>
                <th style="width: 10%;">Action</th>
                <th style="width: 12%;">Target</th>
                <th style="width: 12%;">Amount</th>
                <th style="width: 12%;">Network</th>
                <th style="width: 12%;">Device</th>
                <th style="width: 15%;">Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        <div class="user-name">{{ $log->user?->name ?? 'System' }}</div>
                        @if($log->user && $log->user->role)
                            <div class="text-muted">{{ strtoupper($log->user->role->name) }}</div>
                            @if($log->user->role->name === 'cashier')
                                <div class="text-muted">NIS: {{ $log->user->nis ?? '-' }}</div>
                            @endif
                        @endif
                    </td>
                    <td><span class="badge">{{ strtoupper(str_replace('_', ' ', $log->action)) }}</span></td>
                    <td>
                        <strong>{{ $log->model_type }}</strong>
                        @if($log->model_id) <br><code style="font-size: 7px;">#{{ $log->model_id }}</code> @endif
                    </td>
                    <td>
                        @if($log->amount)
                            <strong style="color: #16a34a;">Rp{{ number_format($log->amount, 0, ',', '.') }}</strong>
                            <div class="text-muted">{{ $log->payment_method }}</div>
                        @else - @endif
                    </td>
                    <td><code>{{ $log->ip_address }}</code></td>
                    <td>{{ $log->device_name }}</td>
                    <td>{{ Str::limit($log->notes, 50) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>ARTIKA POS System</strong> - Audit Log Report</p>
        <p>Document generated electronically. No signature required.</p>
    </div>

    @if(!isset($isPdf) || !$isPdf)
        <script>
            window.onload = function () {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.get('auto_print') === 'true') {
                    window.print();
                }
            }
        </script>
    @endif
</body>

</html>