<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Audit</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #85695a;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #85695a;
            margin: 0;
        }
        .report-title {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
            font-size: 12px;
        }
        .info-table td {
            padding: 5px 10px;
            border: 1px solid #ddd;
        }
        .info-table .label {
            background: #f5f5f5;
            font-weight: bold;
            width: 20%;
        }
        .summary-section {
            margin-bottom: 30px;
        }
        .summary-section h3 {
            background: #85695a;
            color: white;
            padding: 10px;
            margin: 0 0 15px 0;
            font-size: 14px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        .summary-table th {
            background: #e8e8e8;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        .summary-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .summary-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #f5f5f5;
            padding: 15px;
            border-left: 4px solid #85695a;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #85695a;
        }
        .details-section {
            margin-top: 30px;
        }
        .details-section h3 {
            background: #85695a;
            color: white;
            padding: 10px;
            margin: 0 0 15px 0;
            font-size: 14px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }
        .details-table th {
            background: #e8e8e8;
            padding: 6px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        .details-table td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        .details-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <p class="company-name">ARTIKA MINIMARKET</p>
        <p style="margin: 5px 0; font-size: 12px;">Jl. Utama No. 1</p>
        <p class="report-title">Laporan Audit Sistem</p>
        <p style="margin: 5px 0; font-size: 12px;">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
        <p style="margin: 5px 0; font-size: 11px; color: #666;">Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-section">
        <h3>📊 Ringkasan Laporan</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Total Activity Log</div>
                <div class="stat-value">{{ $summary['total_logs'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Transaksi</div>
                <div class="stat-value">{{ $summary['total_transactions'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Nilai Transaksi</div>
                <div class="stat-value">Rp{{ number_format($summary['total_amount'], 0, ',', '.') }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Rata-rata Transaksi</div>
                <div class="stat-value">Rp{{ $summary['total_transactions'] > 0 ? number_format($summary['total_amount'] / $summary['total_transactions'], 0, ',', '.') : 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Payment Method Summary -->
    @if($summary['by_payment_method']->count() > 0)
    <div class="summary-section">
        <h3>💳 Ringkasan Metode Pembayaran</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Metode Pembayaran</th>
                    <th class="text-center">Jumlah Transaksi</th>
                    <th class="amount">Total Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary['by_payment_method'] as $method => $data)
                <tr>
                    <td>{{ $method ?? 'Tidak Diketahui' }}</td>
                    <td class="text-center">{{ $data['count'] }}</td>
                    <td class="amount">Rp{{ number_format($data['total'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Activity by User -->
    @if($summary['by_user']->count() > 0)
    <div class="summary-section">
        <h3>👤 Ringkasan Aktivitas per User</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Nama User</th>
                    <th class="text-center">Total Aktivitas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary['by_user'] as $userId => $data)
                <tr>
                    <td>{{ $data['user']?->name ?? 'Unknown' }}</td>
                    <td class="text-center">{{ $data['count'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Activity by Type -->
    @if($summary['by_action']->count() > 0)
    <div class="summary-section">
        <h3>📋 Ringkasan Aktivitas per Tipe</h3>
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Tipe Aktivitas</th>
                    <th class="text-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary['by_action'] as $action => $count)
                <tr>
                    <td>{{ $action }}</td>
                    <td class="text-center">{{ $count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Detailed Activity Logs -->
    <div class="details-section">
        <h3>📝 Detail Aktivitas Lengkap</h3>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Tanggal & Waktu</th>
                    <th>User</th>
                    <th>Tipe Aktivitas</th>
                    <th>Model/Referensi</th>
                    <th>Nilai</th>
                    <th>Metode</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $log->user?->name ?? 'System' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->model_type }}{{ $log->model_id ? ' #' . $log->model_id : '' }}</td>
                    <td class="amount">{{ $log->amount ? 'Rp' . number_format($log->amount, 0, ',', '.') : '-' }}</td>
                    <td>{{ $log->payment_method ?? '-' }}</td>
                    <td>{{ $log->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data aktivitas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dicetak otomatis oleh Sistem ARTIKA POS</p>
        <p>Untuk keperluan audit internal dan pelaporan manajemen</p>
        <p style="margin-top: 20px;">{{ now()->format('d M Y, H:i:s') }}</p>
    </div>
</body>
</html> 
