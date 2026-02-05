<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('pos.receipt') }} - {{ $transaction->invoice_no }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @page {
            margin: 0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            width: 100%;
            max-width: 58mm;
            margin: 0 auto;
            padding: 20px 0;
            font-size: 9.5px;
            font-weight: 600;
            /* Increased base weight */
            line-height: 1.2;
            background: #f0f1f2;
            overflow-x: hidden;
            word-break: break-word;
            color: #000;
        }

        .receipt {
            width: 100%;
            background: white;
            padding: 8mm 5mm;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            min-height: 100vh;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }

        .store-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .store-info {
            font-size: 10px;
            margin-bottom: 2px;
        }

        .transaction-info {
            margin: 10px 0;
            font-size: 11px;
            font-weight: 700;
            border-bottom: 1.5px dashed #000;
            padding-bottom: 8px;
        }

        .transaction-info div {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .items-table {
            width: 100%;
            margin: 10px 0;
        }

        .item-row {
            margin-bottom: 8px;
            width: 100%;
        }

        .item-main {
            display: flex;
            justify-content: space-between;
            font-weight: 800;
            /* Extra bold for items */
            width: 100%;
        }

        .item-name {
            flex: 1;
            padding-right: 5px;
        }

        .item-subtotal {
            white-space: nowrap;
        }

        .item-details {
            font-size: 9px;
            color: #000;
            font-weight: 600;
            margin-top: 1px;
        }

        .divider {
            border-top: 1.5px dashed #000;
            /* Thicker dashes */
            margin: 10px 0;
            width: 100%;
        }

        .totals {
            margin: 8px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }

        .total-row.grand-total {
            font-size: 14px;
            font-weight: 800;
            border-top: 1.5px solid #000;
            padding-top: 6px;
            margin-top: 6px;
        }

        .payment-info {
            margin: 10px 0;
            padding-top: 5px;
            font-weight: 700;
        }

        .footer {
            text-align: center;
            margin-top: 15px;
            border-top: 1.5px dashed #000;
            padding-top: 10px;
            font-size: 9px;
            font-weight: 700;
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

        .logo-container {
            text-align: center;
            margin-bottom: 5px;
        }

        .logo {
            max-width: 100px;
            height: auto;
            filter: grayscale(100%);
        }

        .back-button:hover {
            background: #5a6268;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
            }

            .receipt {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                box-shadow: none !important;
                padding: 2mm 0 !important;
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
                background: white !important;
                color: #000 !important;
            }

            * {
                color: #000 !important;
                background: transparent !important;
                box-shadow: none !important;
            }
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
            <div class="logo-container">
                <img src="{{ asset('img/logo2.png') }}" alt="Logo" class="logo">
            </div>
            <div class="store-name">ARTIKA MINIMARKET</div>
            <div class="store-info">Jl. Jendral Sudirman 269A</div>
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
                    <div class="item-main" style="text-transform: uppercase;">
                        <span>{{ $item->product->name }}</span>
                    </div>
                    <div class="item-details" style="display: flex; justify-content: space-between;">
                        <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span>Rp{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="divider"></div>

        <!-- Totals -->
        <div class="totals">
            <div class="total-row">
                <span>{{ __('pos.subtotal') }}:</span>
                <span>Rp{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($transaction->discount > 0)
                <div class="total-row" style="color: #000;">
                    <span>{{ __('pos.discount') }}:</span>
                    <span>-Rp{{ number_format($transaction->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row grand-total">
                <span>{{ __('pos.total') }}:</span>
                <span>Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Summary -->
        <div style="margin: 5px 0; font-size: 9px; font-weight: 700;">
            <div class="total-row">
                <span>Total Item:</span>
                <span>{{ $transaction->items->count() }}</span>
            </div>
            <div class="total-row">
                <span>Total Qty:</span>
                <span>{{ $transaction->items->sum('quantity') }}</span>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="total-row">
                <span>{{ __('pos.payment_method_label') }}:</span>
                <span>{{ strtoupper($transaction->payment_method) }}</span>
            </div>
            @if(strtolower($transaction->payment_method) === 'cash')
                <div class="total-row">
                    <span>{{ __('pos.cash_received_label') }}:</span>
                    <span>Rp{{ number_format($transaction->cash_amount, 0, ',', '.') }}</span>
                </div>
                <div class="total-row" style="font-weight: bold; font-size: 13px; margin-top: 5px;">
                    <span>{{ __('pos.change_label') }}:</span>
                    <span>Rp{{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
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
                // [FIX] Add a small delay to ensure rendering and logo loading
                setTimeout(() => {
                    window.print();
                }, 500);

                // Optional: Close tab after printing
                window.onafterprint = function () {
                    window.close();
                };
            }
        }
    </script>
</body>

</html>