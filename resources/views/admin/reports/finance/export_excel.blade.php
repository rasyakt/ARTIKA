<table>
    <thead>
        <tr>
            <th colspan="3" style="font-weight: bold; font-size: 14px; text-align: center;">LAPORAN KEUANGAN ARTIKA POS
            </th>
        </tr>
        <tr>
            <th colspan="3" style="text-align: center;">Periode: {{ $startDate->format('d/m/Y') }} -
                {{ $endDate->format('d/m/Y') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2;">Ringkasan</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Nilai</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Penjualan Kotor</td>
            <td>Rp {{ number_format($summary['gross_sales'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Pengeluaran (Beban)</td>
            <td>Rp {{ number_format($summary['total_expenses'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon Diberikan</td>
            <td>Rp {{ number_format($summary['total_discounts'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">Laba Rugi Bersih</td>
            <td style="font-weight: bold; color: {{ $summary['net_profit'] >= 0 ? '#008000' : '#FF0000' }}">
                Rp {{ number_format($summary['net_profit'], 0, ',', '.') }}
            </td>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="3" style="font-weight: bold; background-color: #f2f2f2;">Data Harian</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Tanggal</th>
            <th style="font-weight: bold;">Penjualan</th>
            <th style="font-weight: bold;">Pengeluaran</th>
        </tr>
        @foreach($dailyData as $row)
            <tr>
                <td>{{ \Carbon\Carbon::parse($row['date'])->format('d/m/Y') }}</td>
                <td>Rp {{ number_format($row['sales'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($row['expenses'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>