<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Penerimaan - {{ $preOrder->uuid }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-box {
            width: 48%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .total-section {
            float: right;
            width: 300px;
        }

        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .signature-box {
            width: 200px;
            border-top: 1px solid #333;
            padding-top: 5px;
            margin-top: 60px;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Faktur</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h1>Faktur Penerimaan Barang</h1>
        <p>{{ App\Models\Setting::get('system_name', 'ARTIKA POS') }}</p>
        <p>{{ App\Models\Setting::get('address', ) }}</p>
    </div>

    <div class="info-section">
        <div class="info-box">
            <strong>Diterima Dari:</strong><br>
            {{ $preOrder->supplier->name }}<br>
            {{ $preOrder->supplier->address ?: '-' }}<br>
            Telp: {{ $preOrder->supplier->phone ?: '-' }}
        </div>
        <div class="info-box text-right">
            <strong>Detail Faktur:</strong><br>
            No. Ref: {{ $preOrder->reference_number ?: '-' }}<br>
            No. UUID: {{ substr($preOrder->uuid, 0, 8) }}...<br>
            Tanggal: {{ $preOrder->updated_at->format('d/m/Y H:i') }}<br>
            Petugas: {{ $preOrder->user->name }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th class="text-right">Jumlah</th>
                <th>Satuan</th>
                <th class="text-right">Isi/Unit</th>
                <th class="text-right">Total (Pcs)</th>
                <th class="text-right">Harga (HPP)</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($preOrder->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td>{{ $item->unit_name }}</td>
                    <td class="text-right">{{ $item->pcs_per_unit }}</td>
                    <td class="text-right">{{ $item->quantity * $item->pcs_per_unit }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7" class="text-right"><strong>TOTAL PEMELIAN</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($preOrder->total_amount, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="info-section">
        <div style="width: 100%;">
            <strong>Catatan:</strong><br>
            {{ $preOrder->notes ?: 'Tidak ada catatan.' }}
        </div>
    </div>

    <div class="footer">
        <div>
            Pengirim (Supplier)
            <div class="signature-box"></div>
        </div>
        <div>
            Penerima (Toko)
            <div class="signature-box"></div>
        </div>
    </div>
</body>

</html>