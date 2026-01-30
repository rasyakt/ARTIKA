<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Transaction {{ $transaction->invoice_no }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }

        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #4b382f;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            vertical-align: top;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table th {
            background: #f8f9fa;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .text-end {
            text-align: right;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 10px;
        }

        .total-row td {
            font-weight: bold;
            font-size: 14px;
            padding-top: 15px;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            color: white;
        }

        .bg-success {
            background-color: #28a745;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="invoice-title">ARTIKA POS</div>
        <div>Sistem Point of Sale</div>
        <div style="margin-top: 10px;">{{ $transaction->created_at->format('d M Y H:i') }}</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <strong>Metode Pembayaran:</strong> {{ ucfirst($transaction->payment_method) }}<br>
                <strong>Kasir:</strong> {{ $transaction->user->name ?? 'System' }}
            </td>
            <td width="50%" class="text-end">
                <strong>No. Invoice:</strong><br>
                <span style="font-size: 16px; color: #007bff;">{{ $transaction->invoice_no }}</span>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-end">Harga</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaction->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name ?? 'Produk Dihapus' }}<br>
                        <small style="color: #888;">{{ $item->product->code ?? '-' }}</small>
                    </td>
                    <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-end">TOTAL</td>
                <td class="text-end">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if($transaction->note)
        <div style="background: #f8f9fa; padding: 10px; border-left: 3px solid #85695a; margin-top: 20px;">
            <strong>Catatan:</strong><br>
            {{ $transaction->note }}
        </div>
    @endif

    <div class="footer">
        Terima kasih telah berbelanja di ARTIKA.<br>
        Simpan struk ini sebagai bukti transaksi yang sah.
    </div>
</body>

</html>