@php
    $paperSize = $paperSize ?? \App\Models\Setting::get('receipt_paper_size', '58mm');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ __('pos.receipt') }} - {{ $transaction->invoice_no }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --receipt-width:
                {{ $paperSize === '80mm' ? '80mm' : '58mm' }}
            ;
            --receipt-padding:
                {{ $paperSize === '80mm' ? '6mm' : '5mm' }}
            ;
            --font-size-base:
                {{ $paperSize === '80mm' ? '11px' : '9.5px' }}
            ;
            --font-size-store:
                {{ $paperSize === '80mm' ? '16px' : '14px' }}
            ;
            --font-size-total:
                {{ $paperSize === '80mm' ? '15px' : '14px' }}
            ;
            --font-size-change:
                {{ $paperSize === '80mm' ? '14px' : '13px' }}
            ;
            --font-size-details:
                {{ $paperSize === '80mm' ? '10px' : '9px' }}
            ;
            --font-size-summary:
                {{ $paperSize === '80mm' ? '10px' : '9px' }}
            ;
            --logo-width:
                {{ $paperSize === '80mm' ? '120px' : '100px' }}
            ;
        }

        @page {
            margin: 0;
            size: var(--receipt-width) auto;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            width: 100%;
            max-width: var(--receipt-width);
            margin: 0 auto;
            padding: 20px 0;
            font-size: var(--font-size-base);
            font-weight: 600;
            line-height: 1.2;
            background: #f0f1f2;
            overflow-x: hidden;
            word-break: break-word;
            color: #000;
        }

        .receipt {
            width: 100%;
            background: white;
            padding: 8mm var(--receipt-padding);
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
            font-size: var(--font-size-store);
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .store-info {
            font-size: calc(var(--font-size-base) + 0.5px);
            margin-bottom: 2px;
        }

        .transaction-info {
            margin: 10px 0;
            font-size: calc(var(--font-size-base) + 1.5px);
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
            font-size: var(--font-size-details);
            color: #000;
            font-weight: 600;
            margin-top: 1px;
        }

        .divider {
            border-top: 1.5px dashed #000;
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
            font-size: var(--font-size-total);
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
            font-size: var(--font-size-details);
            font-weight: 700;
        }

        /* Action Buttons Area */
        .action-buttons {
            position: fixed;
            top: 10px;
            right: 10px;
            display: flex;
            gap: 8px;
            z-index: 100;
        }

        .action-buttons .btn-action {
            padding: 10px 16px;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-print {
            background: #85695a;
        }

        .btn-print:hover {
            background: #6f5849;
        }

        .btn-share {
            background: #2196F3;
        }

        .btn-share:hover {
            background: #1976D2;
        }

        .btn-download {
            background: #4CAF50;
        }

        .btn-download:hover {
            background: #388E3C;
        }

        .back-button {
            position: fixed;
            top: 10px;
            left: 10px;
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            font-size: 13px;
            z-index: 100;
        }

        .back-button:hover {
            background: #5a6268;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 5px;
        }

        .logo {
            max-width: var(--logo-width);
            height: auto;
            filter: grayscale(100%);
        }

        /* Paper size indicator */
        .paper-size-badge {
            position: fixed;
            bottom: 10px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-family: system-ui, sans-serif;
            z-index: 100;
        }

        /* Processing overlay */
        .processing-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            justify-content: center;
            align-items: center;
        }

        .processing-overlay.active {
            display: flex;
        }

        .processing-overlay .spinner {
            background: white;
            padding: 24px 32px;
            border-radius: 12px;
            font-family: system-ui, sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            text-align: center;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                width: 100% !important;
                max-width: 100% !important;
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

        /* Mobile responsive adjustments */
        @media screen and (max-width: 480px) {
            .action-buttons {
                top: auto;
                bottom: 0;
                left: 0;
                right: 0;
                position: fixed;
                flex-direction: row;
                justify-content: center;
                background: rgba(255, 255, 255, 0.95);
                padding: 12px;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                border-radius: 0;
            }

            .action-buttons .btn-action {
                flex: 1;
                justify-content: center;
                padding: 12px 10px;
                font-size: 12px;
            }

            .back-button {
                position: relative;
                display: block;
                width: calc(100% - 20px);
                margin: 10px auto;
                text-align: center;
            }

            body {
                padding-bottom: 80px;
            }

            .paper-size-badge {
                bottom: 75px;
            }
        }
    </style>
</head>

<body>
    <a href="{{ route('pos.index') }}" class="back-button no-print">
        <i class="fas fa-arrow-left"></i> {{ __('pos.back_to_pos') }}
    </a>

    <div class="action-buttons no-print">
        <button class="btn-action btn-print" onclick="window.print()" id="btnPrint">
            <i class="fas fa-print"></i> <span>{{ __('pos.print_receipt') }}</span>
        </button>
        <button class="btn-action btn-share" onclick="shareReceipt()" id="btnShare" style="display:none;">
            <i class="fas fa-share-alt"></i> <span>Share</span>
        </button>
        <button class="btn-action btn-download" onclick="downloadReceipt()" id="btnDownload" style="display:none;">
            <i class="fas fa-download"></i> <span>Download</span>
        </button>
    </div>

    <div class="paper-size-badge no-print">
        <i class="fas fa-ruler-horizontal"></i> {{ $paperSize }}
    </div>

    <div class="processing-overlay no-print" id="processingOverlay">
        <div class="spinner">
            <i class="fas fa-spinner fa-spin fa-2x" style="margin-bottom: 10px; display: block;"></i>
            Preparing receipt...
        </div>
    </div>

    <div class="receipt" id="receiptContent">
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
        <div style="margin: 5px 0; font-size: var(--font-size-summary); font-weight: 700;">
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
                <div class="total-row" style="font-weight: bold; font-size: var(--font-size-change); margin-top: 5px;">
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

    <!-- html2canvas for image generation (loaded from CDN) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        window.onload = function () {
            // Show share/download buttons on mobile
            if (isMobile) {
                const btnShare = document.getElementById('btnShare');
                const btnDownload = document.getElementById('btnDownload');

                if (navigator.share && navigator.canShare) {
                    btnShare.style.display = 'inline-flex';
                }
                btnDownload.style.display = 'inline-flex';
            }

            // Auto-print on desktop (when requested)
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('auto_print') && !isMobile) {
                setTimeout(() => {
                    window.print();
                }, 500);

                window.onafterprint = function () {
                    window.close();
                };
            }
        }

        /**
         * Capture the receipt element as a canvas/blob
         */
        async function captureReceipt() {
            const overlay = document.getElementById('processingOverlay');
            overlay.classList.add('active');

            try {
                const receiptEl = document.getElementById('receiptContent');
                const canvas = await html2canvas(receiptEl, {
                    scale: 3,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                });

                overlay.classList.remove('active');
                return canvas;
            } catch (err) {
                overlay.classList.remove('active');
                throw err;
            }
        }

        /**
         * Share receipt using Web Share API (mobile)
         * Works with Bluetooth printer apps like RawBT, PrinterShare, etc.
         */
        async function shareReceipt() {
            try {
                const canvas = await captureReceipt();

                canvas.toBlob(async (blob) => {
                    const file = new File([blob], 'receipt-{{ $transaction->invoice_no }}.png', {
                        type: 'image/png'
                    });

                    if (navigator.canShare && navigator.canShare({ files: [file] })) {
                        await navigator.share({
                            title: 'Receipt {{ $transaction->invoice_no }}',
                            text: 'Receipt from ARTIKA Minimarket',
                            files: [file]
                        });
                    } else {
                        // Fallback: download instead
                        downloadFromCanvas(canvas);
                    }
                }, 'image/png');
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Share failed:', err);
                    alert('Share failed. Try using Download instead.');
                }
            }
        }

        /**
         * Download receipt as image file
         */
        async function downloadReceipt() {
            try {
                const canvas = await captureReceipt();
                downloadFromCanvas(canvas);
            } catch (err) {
                console.error('Download failed:', err);
                alert('Download failed. Please try again.');
            }
        }

        function downloadFromCanvas(canvas) {
            const link = document.createElement('a');
            link.download = 'receipt-{{ $transaction->invoice_no }}.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }
    </script>
</body>

</html>