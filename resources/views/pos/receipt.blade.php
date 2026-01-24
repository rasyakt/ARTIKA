<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('pos.receipt') }} - {{ $transaction->invoice_no }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            size: 58mm auto;
            margin: 0;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            width: 58mm;
            margin: 0 auto;
            padding: 5px;
            font-size: 11px;
            line-height: 1.2;
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
            font-size: 14px;
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
            font-size: 13px;
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

        .print-button,
        .back-button {
            position: fixed;
            top: 10px;
            padding: 10px 20px;
            background: #85695a;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
        }

        .print-button {
            right: 10px;
        }

        .back-button {
            left: 10px;
            background: #6c757d;
        }

        .print-button:hover {
            background: #6f5849;
        }

        .back-button:hover {
            background: #5a6268;
        }

        .print-button:hover {
            background: #6f5849;
        }
    </style>
</head>

<body>
    <a href="{{ route('pos.index') }}" class="back-button no-print">
        <i class="fas fa-arrow-left"></i> Kembali ke POS
    </a>
    <button class="print-button no-print" onclick="window.print()">{{ __('pos.print_receipt') }}</button>

    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="store-name">ARTIKA MINIMARKET</div>
            <div class="store-info">Jl. Jendral Sudirman 269A</div>
            <div class="store-info">Telp./Fax. (0265) 771204 Ciamis</div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div>
                <span>{{ __('pos.invoice') }}</span>
                <span><strong>{{ $transaction->invoice_no }}</strong></span>
            </div>
            <div>
                <span>{{ __('pos.date') }}:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span>{{ __('pos.cashier') }}:</span>
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
                <span>{{ __('pos.subtotal') }}:</span>
                <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount > 0)
                <div class="total-row">
                    <span>{{ __('pos.discount') }}:</span>
                    <span>- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row grand-total">
                <span>{{ __('pos.total') }}:</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-row">
                <span>{{ __('pos.payment_method_label') }}:</span>
                <span>{{ strtoupper($transaction->payment_method) }}</span>
            </div>
            @if(strtolower($transaction->payment_method) === 'cash')
                <div class="total-row">
                    <span>{{ __('pos.cash_received_label') }}:</span>
                    <span>Rp {{ number_format($transaction->cash_amount, 0, ',', '.') }}</span>
                </div>
                <div class="total-row"
                    style="border-top: 1px solid #000; padding-top: 5px; margin-top: 5px; font-weight: bold; font-size: 13px;">
                    <span>{{ __('pos.change_label') }}:</span>
                    <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 5px 0;">{{ __('pos.thank_you') }}</p>
            <p style="margin: 5px 0;">{{ __('pos.come_again') }}</p>
            <p style="margin: 10px 0 5px 0;">{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('auto_print')) {
                window.print();

                // Optional: Close tab after printing
                window.onafterprint = function () {
                    window.close();
                };

                // Fallback for browsers that don't support onafterprint or if printing is cancelled
                setTimeout(() => {
                    // Only close if it seems to be an automated window
                    if (window.opener) {
                        // window.close(); 
                    }
                }, 3000);
            }
        }
    </script>
</body>

</html>