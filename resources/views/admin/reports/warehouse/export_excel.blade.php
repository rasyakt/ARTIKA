<table>
    <thead>
        <tr>
            <th colspan="4" style="font-weight: bold; font-size: 14px; text-align: center;">LAPORAN GUDANG ARTIKA POS
            </th>
        </tr>
        <tr>
            <th colspan="4" style="text-align: center;">Periode: {{ $startDate->format('d/m/Y') }} -
                {{ $endDate->format('d/m/Y') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #f2f2f2;">Ringkasan Stok</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total Item Barang</td>
            <td>{{ $summary['total_products'] }}</td>
        </tr>
        <tr>
            <td>Item Stok Rendah</td>
            <td>{{ $summary['low_stock_count'] }}</td>
        </tr>
        <tr>
            <td>Total Pergerakan Stok</td>
            <td>{{ $summary['movement_count'] }}</td>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="4" style="font-weight: bold; background-color: #f2f2f2;">Pergerakan Stok</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">Produk</th>
            <th style="font-weight: bold;">Tipe</th>
            <th style="font-weight: bold;">Jumlah</th>
            <th style="font-weight: bold;">Alasan</th>
            <th style="font-weight: bold;">Waktu</th>
        </tr>
        @foreach($movements as $movement)
            <tr>
                <td>{{ $movement->product->name ?? 'N/A' }}</td>
                <td>{{ strtoupper($movement->type) }}</td>
                <td>{{ $movement->quantity_change }}</td>
                <td>{{ $movement->reason }}</td>
                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>