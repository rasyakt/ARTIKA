<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('pos.mobile_scanner_title') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        :root {
            --primary: #85695a;
            --primary-dark: #6f5849;
            --primary-light: #a18072;
            --success: #16a34a;
            --success-dark: #15803d;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #000;
            overflow: hidden;
            position: fixed;
            width: 100%;
            height: 100%;
        }

        /* Header */
        .scanner-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 100%);
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            backdrop-filter: blur(10px);
        }

        .header-title {
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-header {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-header:hover,
        .btn-header:active {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Scanner Container */
        .scanner-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #000;
        }

        #reader {
            width: 100%;
            height: 100%;
            position: relative;
        }

        /* Scan Guide Overlay */
        .scan-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 100;
        }

        .scan-frame {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 280px;
            height: 280px;
            border: 3px solid rgba(255, 255, 255, 0.5);
            border-radius: 24px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
        }

        .scan-corner {
            position: absolute;
            width: 40px;
            height: 40px;
            border: 4px solid var(--success);
        }

        .scan-corner.top-left {
            top: -4px;
            left: -4px;
            border-right: none;
            border-bottom: none;
            border-radius: 24px 0 0 0;
        }

        .scan-corner.top-right {
            top: -4px;
            right: -4px;
            border-left: none;
            border-bottom: none;
            border-radius: 0 24px 0 0;
        }

        .scan-corner.bottom-left {
            bottom: -4px;
            left: -4px;
            border-right: none;
            border-top: none;
            border-radius: 0 0 0 24px;
        }

        .scan-corner.bottom-right {
            bottom: -4px;
            right: -4px;
            border-left: none;
            border-top: none;
            border-radius: 0 0 24px 0;
        }

        .scan-line {
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--success), transparent);
            animation: scan 2s linear infinite;
        }

        @keyframes scan {
            0% {
                top: 0;
            }

            50% {
                top: 100%;
            }

            100% {
                top: 0;
            }
        }

        .scan-instruction {
            position: absolute;
            bottom: -60px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            white-space: nowrap;
        }

        /* Bottom Controls */
        .scanner-controls {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 200;
            background: linear-gradient(0deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.6) 100%);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .control-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-control {
            flex: 1;
            max-width: 200px;
            padding: 1rem;
            border: none;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-switch-camera {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-switch-camera:active {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0.95);
        }

        .btn-flash {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-flash.active {
            background: var(--warning);
            color: white;
        }

        /* Scanned Items Preview */
        .scanned-items {
            background: rgba(22, 163, 74, 0.9);
            backdrop-filter: blur(10px);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            color: white;
            max-height: 150px;
            overflow-y: auto;
        }

        .scanned-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .item-count {
            background: rgba(255, 255, 255, 0.3);
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .scanned-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .scanned-item {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }

        .item-name {
            font-weight: 600;
        }

        .item-qty {
            background: rgba(255, 255, 255, 0.3);
            padding: 0.125rem 0.5rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 700;
        }

        /* Success Flash */
        .success-flash {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--success);
            opacity: 0;
            pointer-events: none;
            z-index: 999;
            transition: opacity 0.2s;
        }

        .success-flash.active {
            opacity: 0.5;
        }

        /* Product Found Toast */
        .product-toast {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1001;
            opacity: 0;
            transition: all 0.3s;
            max-width: 320px;
            width: 90%;
        }

        .product-toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .toast-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .toast-icon {
            font-size: 2.5rem;
        }

        .toast-info {
            flex: 1;
        }

        .toast-name {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .toast-price {
            font-weight: 800;
            color: var(--success);
            font-size: 1.125rem;
        }

        /* Error Toast */
        .error-toast {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            background: var(--danger);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            z-index: 1001;
            opacity: 0;
            transition: all 0.3s;
            text-align: center;
            font-weight: 700;
        }

        .error-toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* Loading State */
        .scanner-loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            z-index: 50;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsive adjustments */
        @media (max-height: 700px) {
            .scan-frame {
                width: 220px;
                height: 220px;
            }

            .scanner-controls {
                padding: 1rem;
            }

            .scanned-items {
                max-height: 100px;
            }
        }

        @media (orientation: landscape) {
            .scan-frame {
                width: 200px;
                height: 200px;
            }

            .scanner-controls {
                padding: 1rem;
                flex-direction: row;
                align-items: center;
            }

            .control-buttons {
                order: 2;
            }

            .scanned-items {
                order: 1;
                flex: 1;
                max-height: none;
            }

            .scanner-header {
                padding: 0.75rem 1rem;
            }

            .header-title {
                font-size: 1.1rem;
            }

            .btn-header {
                padding: 0.4rem 0.9rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 360px) {
            .scan-frame {
                width: 240px;
                height: 240px;
            }

            .header-title {
                font-size: 1rem;
            }

            .btn-header {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }

            .btn-control {
                padding: 0.875rem;
                font-size: 0.95rem;
            }
        }

        /* Error Message Styles */
        .error-message {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 16px;
            margin: 1rem;
            max-width: 400px;
            box-shadow: 0 8px 24px rgba(220, 38, 38, 0.3);
        }

        .error-icon {
            font-size: 3rem;
            text-align: center;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 1.25rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            text-align: center;
        }

        .error-description {
            font-size: 0.95rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            opacity: 0.95;
        }

        .error-steps {
            background: rgba(0, 0, 0, 0.2);
            padding: 1rem;
            border-radius: 12px;
            margin-top: 1rem;
        }

        .error-steps ol {
            margin: 0;
            padding-left: 1.25rem;
        }

        .error-steps li {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .error-back-btn {
            display: block;
            width: 100%;
            padding: 0.875rem;
            background: white;
            color: #dc2626;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .error-back-btn:active {
            transform: scale(0.98);
            background: rgba(255, 255, 255, 0.9);
        }

        /* Improved touch targets for mobile */
        @media (hover: none) and (pointer: coarse) {
            .btn-control {
                min-height: 50px;
                padding: 1.125rem;
            }

            .btn-header {
                min-height: 44px;
                padding: 0.625rem 1.125rem;
            }

            .scan-corner {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>

<body>
    <script>
        const products = @json($products);
    </script>

    <!-- Success Flash -->
    <div class="success-flash" id="successFlash"></div>

    <!-- Header -->
    <div class="scanner-header">
        <div class="header-title">
            <span>📱</span>
            <span>{{ __('pos.mobile_scanner') }}</span>
        </div>
        <a href="{{ route('pos.index') }}" class="btn-header d-flex align-items-center" id="btnGoToCart">
            <i class="fa-solid fa-cart-shopping me-2"></i>
            <span class="btn-go-text">{{ __('pos.go_to_pos') }}</span>
            <span class="btn-cart-count ms-2"></span>
        </a>
    </div>

    <!-- Scanner Container -->
    <div class="scanner-container">
        <div id="reader"></div>

        <!-- Loading State -->
        <div class="scanner-loading" id="loadingState">
            <div class="loading-spinner"></div>
            <div>{{ __('pos.starting_camera') }}</div>
        </div>
    </div>

    <!-- Scan Overlay -->
    <div class="scan-overlay">
        <div class="scan-frame">
            <div class="scan-corner top-left"></div>
            <div class="scan-corner top-right"></div>
            <div class="scan-corner bottom-left"></div>
            <div class="scan-corner bottom-right"></div>
            <div class="scan-line"></div>
            <div class="scan-instruction">{{ __('pos.aim_barcode') }}</div>
        </div>
    </div>

    <!-- Product Toast -->
    <div class="product-toast" id="productToast">
        <div class="toast-content">
            <div class="toast-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div class="toast-info">
                <div class="toast-name" id="toastName"></div>
                <div class="toast-price" id="toastPrice"></div>
            </div>
        </div>
    </div>

    <!-- Error Toast -->
    <div class="error-toast" id="errorToast">
        {{ __('pos.product_not_found_exclamation') }}
    </div>

    <!-- Bottom Controls -->
    <div class="scanner-controls">
        <!-- Scanned Items Preview -->
        <div class="scanned-items" id="scannedItemsContainer" style="display: none;">
            <div class="scanned-header">
                <span>{{ __('pos.scanned_items') }}</span>
                <span class="item-count" id="itemCount">0</span>
            </div>
            <div class="scanned-list" id="scannedList"></div>
        </div>

        <!-- Control Buttons -->
        <div class="control-buttons">
            <button class="btn-control btn-switch-camera" id="btnSwitchCamera">
                <i class="fa-solid fa-camera-rotate me-2"></i> {{ __('pos.switch_camera') }}
            </button>
        </div>
    </div>

    <script>
        let html5QrcodeScanner;
        let currentCamera = 'environment'; // 'environment' or 'user'
        let scannedItems = [];
        let lastScannedBarcode = '';
        let lastScanTime = 0;
        const SCAN_COOLDOWN = 1000; // 1 second cooldown to prevent duplicate scans

        // Initialize scanner on page load
        document.addEventListener('DOMContentLoaded', function () {
            initializeScanner();
        });

        // Initialize the scanner
        function initializeScanner() {
            html5QrcodeScanner = new Html5Qrcode("reader");
            startScanning();
        }

        // Start scanning with current camera
        function startScanning() {
            const loadingState = document.getElementById('loadingState');
            loadingState.style.display = 'block';

            // Check if HTTPS (required for camera access on mobile)
            const isSecure = window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

            html5QrcodeScanner.start(
                { facingMode: currentCamera },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    aspectRatio: 1.0
                },
                onScanSuccess,
                onScanError
            ).then(() => {
                loadingState.style.display = 'none';
                console.log('Scanner started successfully');
            }).catch(err => {
                console.error('Failed to start scanner:', err);

                // Show detailed error message
                let errorHTML = '<div class="error-message">';
                errorHTML += '<div class="error-icon"><i class="fa-solid fa-ban"></i></div>';
                errorHTML += '<div class="error-title">{{ __('pos.camera_access_failed') }}</div>';

                if (!isSecure) {
                    errorHTML += '<div class="error-description">';
                    errorHTML += '<i class="fa-solid fa-triangle-exclamation"></i> <strong>{{ __('pos.https_required') }}</strong><br>';
                    errorHTML += '{{ __('pos.https_message') }}';
                    errorHTML += '</div>';
                    errorHTML += '<div class="error-steps">';
                    errorHTML += '<strong>{{ __('pos.solutions') }}</strong>';
                    errorHTML += '<ol>';
                    errorHTML += '<li>Use <code>localhost</code> or <code>127.0.0.1</code></li>';
                    errorHTML += '<li>Setup HTTPS for your server</li>';
                    errorHTML += '<li>Use Chrome flag: <code>chrome://flags/#unsafely-treat-insecure-origin-as-secure</code></li>';
                    errorHTML += '</ol>';
                    errorHTML += '</div>';
                } else {
                    errorHTML += '<div class="error-description">';
                    errorHTML += '{{ __('pos.camera_permission_msg') }}';
                    errorHTML += '</div>';
                    errorHTML += '<div class="error-steps">';
                    errorHTML += '<strong>{{ __('pos.steps') }}</strong>';
                    errorHTML += '<ol>';
                    errorHTML += '<li>{{ __('pos.camera_step_1') }}</li>';
                    errorHTML += '<li>{{ __('pos.camera_step_2') }}</li>';
                    errorHTML += '<li>{{ __('pos.camera_step_3') }}</li>';
                    errorHTML += '</ol>';
                    errorHTML += '</div>';
                }

                errorHTML += '<button class="error-back-btn" onclick="window.location.href=\'' + "{{ route('pos.index') }}" + '\'">';
                errorHTML += '<i class="fa-solid fa-arrow-left me-1"></i> {{ __('pos.back_to_pos') }}';
                errorHTML += '</button>';
                errorHTML += '</div>';

                loadingState.innerHTML = errorHTML;
            });
        }

        // Handle successful scan
        function onScanSuccess(decodedText, decodedResult) {
            const currentTime = Date.now();

            // Prevent duplicate scans
            if (decodedText === lastScannedBarcode && (currentTime - lastScanTime) < SCAN_COOLDOWN) {
                return;
            }

            lastScannedBarcode = decodedText;
            lastScanTime = currentTime;

            console.log(`Scanned: ${decodedText}`);
            processBarcode(decodedText);
        }

        // Handle scan errors (suppress console spam)
        function onScanError(errorMessage) {
            // Silent - normal behavior during scanning
        }

        // Process scanned barcode
        function processBarcode(barcode) {
            const product = products.find(p => p.barcode === barcode);

            if (product) {
                addScannedItem(product);
                showProductToast(product);
                triggerSuccessEffects();
            } else {
                showErrorToast();
                triggerErrorEffects();
            }
        }

        // Add item to scanned list
        function addScannedItem(product) {
            const existingItem = scannedItems.find(item => item.id === product.id);

            if (existingItem) {
                existingItem.quantity++;
            } else {
                scannedItems.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    barcode: product.barcode,
                    quantity: 1
                });
            }

            updateScannedItemsUI();
            saveToSessionStorage();
        }

        // Update scanned items UI
        function updateScannedItemsUI() {
            const container = document.getElementById('scannedItemsContainer');
            const list = document.getElementById('scannedList');
            const count = document.getElementById('itemCount');

            if (scannedItems.length === 0) {
                container.style.display = 'none';
                return;
            }

            container.style.display = 'block';
            count.textContent = scannedItems.length;

            let html = '';
            scannedItems.forEach(item => {
                html += `
                    <div class="scanned-item">
                        <span class="item-name">${item.name}</span>
                        <span class="item-qty">×${item.quantity}</span>
                    </div>
                `;
            });

            list.innerHTML = html;
        }

        // Show product found toast
        function showProductToast(product) {
            const toast = document.getElementById('productToast');
            const name = document.getElementById('toastName');
            const price = document.getElementById('toastPrice');

            name.textContent = product.name;
            price.textContent = 'Rp ' + parseInt(product.price).toLocaleString('id-ID');

            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2000);
        }

        // Show error toast
        function showErrorToast() {
            const toast = document.getElementById('errorToast');
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2000);
        }

        // Trigger success effects (flash + vibration)
        function triggerSuccessEffects() {
            // Flash effect
            const flash = document.getElementById('successFlash');
            flash.classList.add('active');
            setTimeout(() => {
                flash.classList.remove('active');
            }, 200);

            // Haptic feedback (vibration) - Android
            if ('vibrate' in navigator) {
                navigator.vibrate(100); // Vibrate for 100ms
            }

            // Audio feedback (optional - can be enabled)
            // playBeep();
        }

        // Trigger error effects
        function triggerErrorEffects() {
            // Vibration pattern for error
            if ('vibrate' in navigator) {
                navigator.vibrate([50, 50, 50]); // Short triple vibration
            }
        }

        // Switch camera
        document.getElementById('btnSwitchCamera').addEventListener('click', function () {
            html5QrcodeScanner.stop().then(() => {
                currentCamera = currentCamera === 'environment' ? 'user' : 'environment';
                startScanning();
            }).catch(err => {
                console.error('Failed to switch camera:', err);
            });
        });

        // Save to session storage for POS page
        function saveToSessionStorage() {
            sessionStorage.setItem('scannedItems', JSON.stringify(scannedItems));
        }

        // Load from session storage on init
        function loadFromSessionStorage() {
            const saved = sessionStorage.getItem('scannedItems');
            if (saved) {
                scannedItems = JSON.parse(saved);
                updateScannedItemsUI();
            }
        }

        // Load saved items on page load
        loadFromSessionStorage();

        // Update "Go to POS" button with item count badge
        function updateCartButton() {
            const btn = document.getElementById('btnGoToCart');
            const totalItems = scannedItems.reduce((sum, item) => sum + item.quantity, 0);

            const countEl = btn.querySelector('.btn-cart-count');
            if (totalItems > 0) {
                countEl.textContent = `(${totalItems})`;
            } else {
                countEl.textContent = '';
            }
        }

        // Update cart button when items change
        setInterval(updateCartButton, 500);

        // Prevent page refresh
        window.addEventListener('beforeunload', function (e) {
            if (scannedItems.length > 0) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>

</html>