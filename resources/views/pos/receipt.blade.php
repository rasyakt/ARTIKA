<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $transaction->invoice_no }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            width: 80mm;
            margin: 0 auto;
            padding: 10px;
            font-size: 12px;
            line-height: 1.4;
        }

        .receipt {
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
        }

        .store-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .store-info {
            font-size: 10px;
            margin: 2px 0;
        }

        .transaction-info {
            margin: 10px 0;
            font-size: 11px;
        }

        .transaction-info div {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .items-table {
            width: 100%;
            margin: 10px 0;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 10px 0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .item-name {
            flex: 1;
            font-weight: bold;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
            margin-left: 10px;
        }

        .totals {
            margin: 10px 0;
            border-top: 2px solid #000;
            padding-top: 10px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }

        .total-row.grand-total {
            font-size: 16px;
            font-weight: bold;
            border-top: 2px solid #000;
            padding-top: 8px;
            margin-top: 8px;
        }

        .payment-info {
            margin: 10px 0;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            border-top: 2px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }

        .print-button {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #85695a;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .print-button:hover {
            background: #6f5849;
        }
    </style>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">🖨️ Print Receipt</button>

    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="store-name">ARTIKA MINIMARKET</div>
            <div class="store-info">{{ $transaction->branch->address ?? 'Jl. Utama No. 1' }}</div>
            <div class="store-info">Telp: (021) 1234-5678</div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div>
                <span>Invoice:</span>
                <span><strong>{{ $transaction->invoice_no }}</strong></span>
            </div>
            <div>
                <span>Date:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span>Cashier:</span>
                <span>{{ $transaction->user->name }}</span>
            </div>
            {{-- Customer information removed (customers feature deprecated). --}}
        </div>

        <!-- Items -->
        <div class="items-table">
            @foreach($transaction->items as $item)
                <div class="item-row">
                    <div style="flex: 1;">
                        <div class="item-name">{{ $item->product->name }}</div>
                        <div class="item-details">
                            <span>{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            <span>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount > 0)
                <div class="total-row">
                    <span>Discount:</span>
                    <span>- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row grand-total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-row">
                <span>Payment Method:</span>
                <span>{{ strtoupper($transaction->payment_method) }}</span>
            </div>
            @if(strtolower($transaction->payment_method) === 'cash')
                <div class="total-row">
                    <span>Uang Diterima:</span>
                    <span>Rp {{ number_format($transaction->cash_amount, 0, ',', '.') }}</span>
                </div>
                <div class="total-row" style="border-top: 1px solid #000; padding-top: 5px; margin-top: 5px; font-weight: bold; font-size: 13px;">
                    <span>Kembalian:</span>
                    <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 5px 0;">Thank you for shopping!</p>
            <p style="margin: 5px 0;">Please come again</p>
            <p style="margin: 10px 0 5px 0;">{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>

</html>