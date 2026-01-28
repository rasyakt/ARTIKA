<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('admin.logs_report') }} - ARTIKA</title>
    <style>
        @page {
            margin: 10mm;
            size: A4 landscape;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 10px;
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

        .text-muted {
            color: #78716c;
            font-size: 9px;
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
            padding: 1px 4px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            background-color: #f5f0ed;
            color: #6f5849;
            border: 1px solid #e0cec7;
        }

        code {
            font-family: monospace;
            font-size: 8px;
            color: #6f5849;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>{{ __('admin.logs_report') }}</h1>
        <p>ARTIKA POS SYSTEM</p>
    </div>

    <table class="report-meta">
        <tr>
            <td width="12%"><strong>{{ __('admin.period') }}</strong></td>
            <td width="38%">: {{ $startDate }} - {{ $endDate }}</td>
            <td width="12%"><strong>{{ __('admin.generated') }}</strong></td>
            <td width="38%">: {{ now()->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <table class="summary-grid">
        <tr>
            <td class="summary-box" width="25%">
                <h3>Total Logs</h3>
                <div class="value">{{ number_format($summary['total_logs']) }}</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>Transactions</h3>
                <div class="value">{{ number_format($summary['total_transactions']) }}</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>Total Amount</h3>
                <div class="value">Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</div>
            </td>
            <td class="summary-box" width="25%">
                <h3>Unique Users</h3>
                <div class="value">{{ count($summary['by_user']) }}</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th width="12%">Date & Time</th>
                <th width="15%">User Info</th>
                <th width="10%">Action</th>
                <th width="12%">Target</th>
                <th width="12%">Amount</th>
                <th width="10%">Network</th>
                <th width="10%">Device</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>
                        <strong>{{ $log->user?->name ?? 'System' }}</strong>
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
                        @if($log->model_id)
                        <div class="text-muted">#{{ $log->model_id }}</div> @endif
                    </td>
                    <td>
                        @if($log->amount)
                            <strong style="color: #16a34a;">Rp{{ number_format($log->amount, 0, ',', '.') }}</strong>
                            <div class="text-muted">{{ $log->payment_method }}</div>
                        @else - @endif
                    </td>
                    <td><code>{{ $log->ip_address }}</code></td>
                    <td>{{ $log->device_name }}</td>
                    <td>{{ Str::limit($log->notes, 60) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak otomatis oleh Sistem ARTIKA POS pada {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>