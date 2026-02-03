<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; font-size: 14px; text-align: center;">LAPORAN KASIR ARTIKA POS
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">Periode: {{ $startDate->format('d/m/Y') }} -
                {{ $endDate->format('d/m/Y') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #f2f2f2;">Ringkasan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Penjualan</td>
            <td>Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Transaksi</td>
            <td>{{ $summary['transaction_count'] }}</td>
        </tr>
        <tr>
            <td>Rata-rata per Transaksi</td>
            <td>Rp {{ number_format($summary['average_transaction'], 0, ',', '.') }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="3" style="font-weight: bold; background-color: #f2f2f2;">Transaksi Terbaru</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Invoice</th>
            <th style="font-weight: bold;">Kasir</th>
            <th style="font-weight: bold;">Total</th>
            <th style="font-weight: bold;">Metode</th>
            <th style="font-weight: bold;">Waktu</th>
        </tr>
        @foreach($recentTransactions as $transaction)
            <tr>
                <td>{{ $transaction->invoice_no }}</td>
                <td>{{ $transaction->user->name ?? 'System' }}</td>
                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($transaction->payment_method) }}</td>
                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>