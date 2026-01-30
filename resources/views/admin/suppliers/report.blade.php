<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Supplier Report - {{ $supplier->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #85695a;
            padding-bottom: 10px;
        }

        .supplier-info {
            margin-bottom: 30px;
        }

        .supplier-info table {
            width: 100%;
        }

        .supplier-info td {
            padding: 5px 0;
        }

        .supplier-name {
            font-size: 20px;
            font-weight: bold;
            color: #6f5849;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #6f5849;
            text-transform: uppercase;
            border-left: 4px solid #85695a;
            padding-left: 10px;
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
            padding: 10px;
            border-bottom: 1px solid #f2e8e5;
        }

        table.data td {
            padding: 10px;
            border-bottom: 1px solid #f2e8e5;
        }

        .total-row {
            font-weight: bold;
            background-color: #fdf8f6;
        }

        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN RIWAYAT PEMASOK</h1>
        <p>ARTIKA POS SYSTEM</p>
    </div>

    <div class="supplier-info">
        <div class="section-title">Informasi Pemasok</div>
        <table>
            <tr>
                <td width="20%">Nama Pemasok</td>
                <td width="80%">: <span class="supplier-name">{{ $supplier->name }}</span></td>
            </tr>
            <tr>
                <td>Telepon</td>
                <td>: {{ $supplier->phone ?: '-' }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $supplier->email ?: '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>: {{ $supplier->address ?: '-' }}</td>
            </tr>
            <tr>
                <td>Laporan Dibuat</td>
                <td>: {{ date('d F Y, H:i') }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">Riwayat Pasokan Barang</div>
    <table class="data">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga Beli</th>
                <th>Total</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php $totalValue = 0; @endphp
            @foreach($purchases as $purchase)
                @php $totalValue += $purchase->total_price; @endphp
                <tr>
                    <td>{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                    <td>
                        <strong>{{ $purchase->product->name }}</strong><br>
                        <small>{{ $purchase->product->barcode }}</small>
                    </td>
                    <td>{{ $purchase->quantity }}</td>
                    <td>Rp {{ number_format($purchase->purchase_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</td>
                    <td>{{ $purchase->notes ?: '-' }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">TOTAL NILAI TRANSAKSI:</td>
                <td colspan="2">Rp {{ number_format($totalValue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">Ringkasan Penjualan Produk</div>
    <table class="data">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Barcode</th>
                <th>Total Terjual</th>
                <th>Total Pendapatan</th>
                <th>Sisa Stok</th>
                <th>{{ __('admin.inventory_value') }}</th>
            </tr>
        </thead>
        <tbody>
            @if($salesPerformance->count() > 0)
                @foreach($salesPerformance as $sale)
                    <tr>
                        <td>{{ $sale->product->name }}</td>
                        <td>{{ $sale->product->barcode }}</td>
                        <td>{{ number_format($sale->total_sold, 0, ',', '.') }} unit</td>
                        <td>Rp {{ number_format($sale->total_revenue, 0, ',', '.') }}</td>
                        <td>{{ number_format($sale->product->stock->quantity ?? 0, 0, ',', '.') }} unit</td>
                        <td>Rp {{ number_format(($sale->product->stock->quantity ?? 0) * ($sale->product->cost_price ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" style="text-align: center;">Tidak ada data penjualan</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem ARTIKA POS pada {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>

</html>