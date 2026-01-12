<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS - ARTIKA</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        :root {
            --primary: #85695a;
            --primary-dark: #6f5849;
            --primary-light: #a18072;
            --secondary: #d2bab0;
            --accent-warm: #c17a5c;
            --brown-50: #fdf8f6;
            --brown-100: #f2e8e5;
            --gray-100: #f5f5f4;
            --gray-200: #e7e5e4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-100);
            overflow: hidden;
        }

        .pos-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .pos-navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0 1.5rem;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(133, 105, 90, 0.2);
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .navbar-info {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        /* Main Content */
        .pos-main {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Left Panel - Products */
        .products-panel {
            flex: 1;
            background: var(--brown-50);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .search-bar {
            padding: 1.5rem;
            background: white;
            border-bottom: 2px solid var(--brown-100);
        }

        .search-input-group {
            position: relative;
        }

        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-light);
            font-size: 1.2rem;
        }

        .search-input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(133, 105, 90, 0.1);
        }

        .scanner-section {
            padding: 1rem 1.5rem;
            background: white;
            margin: 0 1.5rem 1rem;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .scanner-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .scanner-title {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1rem;
        }

        .btn-toggle-scanner {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-toggle-scanner:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        #reader {
            border-radius: 12px;
            overflow: hidden;
        }

        .products-grid {
            flex: 1;
            overflow-y: auto;
            padding: 0 1.5rem 1.5rem;
        }

        .products-grid::-webkit-scrollbar {
            width: 8px;
        }

        .products-grid::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .products-grid::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 4px;
        }

        .products-row {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(133, 105, 90, 0.15);
            border-color: var(--primary);
        }

        .product-card:active {
            transform: translateY(-2px);
        }

        .product-icon {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 0.75rem;
            background: var(--brown-100);
            padding: 1rem;
            border-radius: 12px;
        }

        .product-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
            text-align: center;
            min-height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-price {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--accent-warm);
            text-align: center;
        }

        /* Right Panel - Cart */
        .cart-panel {
            width: 420px;
            background: white;
            display: flex;
            flex-direction: column;
            border-left: 3px solid var(--brown-100);
            box-shadow: -4px 0 12px rgba(0, 0, 0, 0.05);
        }

        .cart-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--brown-50) 0%, var(--brown-100) 100%);
            border-bottom: 2px solid var(--brown-100);
        }

        .cart-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin-bottom: 1rem;
        }

        .cart-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }

        .btn-cart-action {
            padding: 0.625rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-hold {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .btn-hold:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }

        .btn-clear {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .btn-clear:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .cart-items::-webkit-scrollbar {
            width: 6px;
        }

        .cart-items::-webkit-scrollbar-track {
            background: var(--gray-100);
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 3px;
        }

        .cart-empty {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--primary-light);
        }

        .cart-empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .cart-item {
            background: var(--brown-50);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border: 2px solid var(--brown-100);
            transition: all 0.3s;
        }

        .cart-item:hover {
            border-color: var(--primary-light);
        }

        .cart-item-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 0.75rem;
        }

        .cart-item-name {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 0.95rem;
            flex: 1;
        }

        .btn-remove-item {
            background: #ef4444;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-remove-item:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .cart-item-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            border-radius: 8px;
            padding: 0.25rem;
        }

        .btn-qty {
            background: var(--primary);
            color: white;
            border: none;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s;
        }

        .btn-qty:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }

        .qty-display {
            min-width: 40px;
            text-align: center;
            font-weight: 700;
            color: var(--primary-dark);
        }

        .cart-item-price {
            font-weight: 800;
            color: var(--accent-warm);
            font-size: 1.05rem;
        }

        .cart-footer {
            padding: 1.5rem;
            background: white;
            border-top: 3px solid var(--brown-100);
        }

        .cart-summary {
            margin-bottom: 1.5rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.95rem;
        }

        .summary-label {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .summary-value {
            font-weight: 700;
            color: var(--primary-dark);
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 2px solid var(--brown-100);
            margin-bottom: 0;
        }

        .summary-total .summary-label {
            font-size: 1.25rem;
            font-weight: 800;
        }

        .summary-total .summary-value {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--accent-warm);
        }

        .btn-checkout {
            width: 100%;
            padding: 1.125rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(133, 105, 90, 0.3);
            letter-spacing: 1px;
        }

        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(133, 105, 90, 0.4);
        }

        .btn-checkout:active {
            transform: translateY(0);
        }

        .btn-checkout:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Modal Styles */
        .modal-content {
            border-radius: 20px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.5rem;
            border: none;
        }

        .modal-title {
            font-weight: 800;
            font-size: 1.5rem;
        }

        .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.9;
        }

        .modal-body {
            padding: 2rem;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .payment-method-btn {
            padding: 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .payment-method-btn:hover {
            border-color: var(--primary);
            background: var(--brown-50);
        }

        .payment-method-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .form-control-lg {
            padding: 1rem;
            font-size: 1.25rem;
            border-radius: 12px;
            border: 2px solid var(--gray-200);
        }

        .form-control-lg:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(133, 105, 90, 0.1);
        }

        .change-display {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            font-weight: 800;
            font-size: 1.5rem;
        }

        .modal-footer {
            padding: 1.5rem 2rem;
            background: var(--gray-100);
            border: none;
        }

        .btn-process-payment {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            padding: 0.875rem 2.5rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.125rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-process-payment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(22, 163, 74, 0.3);
        }

        /* Keyboard Shortcuts Info */
        .shortcuts-info {
            position: fixed;
            bottom: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.95);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-size: 0.75rem;
            z-index: 50;
        }

        .shortcuts-info strong {
            color: var(--primary-dark);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .cart-panel {
                width: 360px;
            }

            .products-row {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }

            .product-card {
                padding: 1rem;
            }

            .product-name {
                font-size: 0.875rem;
                min-height: 2.25rem;
            }
        }

        @media (max-width: 768px) {
            .pos-main {
                flex-direction: column;
            }

            .cart-panel {
                width: 100%;
                max-height: 45vh;
                min-height: 300px;
            }

            .products-panel {
                max-height: 55vh;
            }

            .shortcuts-info {
                display: none;
            }

            .search-bar {
                padding: 1rem;
            }

            .scanner-section {
                margin: 0 1rem 0.75rem;
                padding: 0.875rem 1rem;
            }

            .products-grid {
                padding: 0 1rem 1rem;
            }

            .products-row {
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
                gap: 0.75rem;
            }

            .product-card {
                padding: 0.875rem;
            }

            .product-icon {
                font-size: 2rem;
                padding: 0.75rem;
                margin-bottom: 0.5rem;
            }

            .product-name {
                font-size: 0.85rem;
                min-height: 2rem;
            }

            .product-price {
                font-size: 1rem;
            }

            .cart-header {
                padding: 1.25rem 1rem;
            }

            .cart-footer {
                padding: 1.25rem 1rem;
            }

            .cart-title {
                font-size: 1.125rem;
            }

            .btn-checkout {
                font-size: 1.125rem;
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .products-row {
                grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
                gap: 0.625rem;
            }

            .product-card {
                padding: 0.75rem;
                border-radius: 12px;
            }

            .product-icon {
                font-size: 1.75rem;
                padding: 0.625rem;
            }

            .product-name {
                font-size: 0.8rem;
                min-height: 1.75rem;
            }

            .product-price {
                font-size: 0.95rem;
            }

            .search-input {
                padding: 0.75rem 1rem 0.75rem 2.75rem;
                font-size: 0.95rem;
            }

            .cart-item {
                padding: 0.875rem;
            }

            .btn-qty {
                width: 26px;
                height: 26px;
                font-size: 0.9rem;
            }

            .qty-display {
                min-width: 35px;
                font-size: 0.95rem;
            }
        }

        /* Mobile Scanner FAB */
        .mobile-scanner-fab {
            position: fixed;
            bottom: 6rem;
            right: 1.5rem;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            box-shadow: 0 4px 16px rgba(133, 105, 90, 0.4);
            cursor: pointer;
            transition: all 0.3s;
            z-index: 1000;
            text-decoration: none;
            border: 3px solid white;
        }

        .mobile-scanner-fab:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 8px 24px rgba(133, 105, 90, 0.5);
        }

        .mobile-scanner-fab:active {
            transform: translateY(-2px) scale(1.02);
        }

        @media (min-width: 769px) {
            .mobile-scanner-fab {
                display: none;
            }
        }

        /* Improved touch targets for mobile */
        @media (hover: none) and (pointer: coarse) {
            .product-card {
                min-height: 140px;
            }

            .btn-cart-action {
                min-height: 44px;
            }

            .btn-checkout {
                min-height: 52px;
            }

            .btn-qty {
                min-width: 32px;
                min-height: 32px;
            }

            .btn-remove-item {
                min-width: 28px;
                min-height: 28px;
            }

            .mobile-scanner-fab {
                width: 64px;
                height: 64px;
                font-size: 1.875rem;
            }
        }

        /* Landscape mobile adjustments */
        @media (max-width: 768px) and (orientation: landscape) {
            .pos-main {
                flex-direction: row;
            }

            .cart-panel {
                width: 360px;
                max-height: 100%;
                min-height: auto;
            }

            .products-panel {
                max-height: 100%;
            }

            .scanner-section {
                margin: 0 1rem 0.5rem;
                padding: 0.75rem;
            }

            .scanner-title {
                font-size: 0.9rem;
            }

            .btn-toggle-scanner {
                font-size: 0.8rem;
                padding: 0.4rem 0.875rem;
            }
        }
    </style>
</head>

<body>

    <script>
        const products = @json($products);
        const paymentMethods = @json($paymentMethods);
    </script>

    <div class="pos-container">
        <!-- Navbar -->
        <nav class="pos-navbar">
            <div class="navbar-brand">ARTIKA POS</div>
            <div class="navbar-info">
                <div class="navbar-user d-flex align-items-center">
                    <i class="fa-solid fa-user me-2"></i>
                    <span style="font-weight: 600;">{{ Auth::user()->name }}</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn-logout">Logout</button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="pos-main">
            <!-- Left Panel - Products -->
            <div class="products-panel">
                <!-- Search Bar -->
                <div class="search-bar">
                    <div class="search-input-group">
                        <span class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" class="search-input" id="searchInput"
                            placeholder="Search products by name or barcode...">
                    </div>
                </div>

                <!-- Scanner Section -->
                <div class="scanner-section" id="scannerSection">
                    <div class="scanner-header">
                        <div class="scanner-title"><i class="fa-solid fa-qrcode me-2"></i>Barcode Scanner</div>
                        <button class="btn-toggle-scanner" id="toggleScanner">Show Scanner</button>
                    </div>
                    <div id="reader" style="display: none;"></div>
                </div>

                <!-- Products Grid -->
                <div class="products-grid">
                    <div class="products-row" id="productsGrid">
                        @foreach($products as $product)
                            <div class="product-card" onclick="addToCart({{ $product->id }})"
                                data-name="{{ strtolower($product->name) }}" data-barcode="{{ $product->barcode }}">
                                <div class="product-icon"><i class="fa-solid fa-box"></i></div>
                                <div class="product-name">{{ $product->name }}</div>
                                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Panel - Cart -->
            <div class="cart-panel">
                <div class="cart-header">
                    <div class="cart-title"><i class="fa-solid fa-cart-shopping me-2"></i>Current Order</div>
                    <div class="cart-actions">
                        <button class="btn-cart-action btn-hold" onclick="holdTransaction()">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Hold (F4)
                        </button>
                        <button class="btn-cart-action btn-clear" onclick="clearCart()">
                            <i class="fa-solid fa-trash me-1"></i> Clear (F8)
                        </button>
                    </div>
                </div>

                <div class="cart-items" id="cartItems">
                    <div class="cart-empty">
                        <div class="cart-empty-icon">🛍️</div>
                        <div style="font-weight: 600;">Cart is empty</div>
                        <div style="font-size: 0.85rem; margin-top: 0.5rem;">Scan or click products to add</div>
                    </div>
                </div>

                <div class="cart-footer">
                    <div class="cart-summary">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value" id="subtotal">Rp 0</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label" style="color: #16a34a;">Discount</span>
                            <span class="summary-value" style="color: #16a34a;" id="discount">- Rp 0</span>
                        </div>
                        <div class="summary-row summary-total">
                            <span class="summary-label">TOTAL</span>
                            <span class="summary-value" id="total">Rp 0</span>
                        </div>
                    </div>
                    <button class="btn-checkout" id="btnCheckout" onclick="openCheckout()" disabled>
                        PAY NOW (F2)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">💳 Checkout Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Total Amount</label>
                        <input type="text" class="form-control form-control-lg fw-bold text-end" id="modalTotal"
                            readonly style="font-size: 1.75rem; color: var(--accent-warm);">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Payment Method</label>
                        <div class="payment-methods" id="paymentMethodsContainer">
                            @foreach($paymentMethods as $pm)
                                <button type="button" class="payment-method-btn {{ $loop->first ? 'active' : '' }}"
                                    data-method="{{ $pm->slug }}" onclick="selectPaymentMethod('{{ $pm->slug }}')">
                                    {{ $pm->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4" id="cashInputGroup">
                        <label class="form-label fw-bold">Cash Received</label>
                        <input type="number" class="form-control form-control-lg text-end" id="cashReceived"
                            placeholder="0" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Change</label>
                        <input type="text" class="form-control form-control-lg change-display text-end"
                            id="returnChange" readonly value="Rp 0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-process-payment" onclick="submitTransaction()">
                        Process Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Scanner FAB (Floating Action Button) -->
    <a href="{{ route('pos.scanner') }}" class="mobile-scanner-fab" title="Open Mobile Scanner">
        📱
    </a>

    <!-- Keyboard Shortcuts Info -->
    <div class="shortcuts-info">
        <strong>Shortcuts:</strong> F2: Pay | F4: Hold | F8: Clear | Esc: Cancel
    </div>

    <script>
        let cart = [];
        let currentPaymentMethod = 'cash';
        let checkoutModal;
        let scannerActive = false;
        let html5QrcodeScanner;
        let barcodeBuffer = ''; // Buffer untuk USB barcode scanner
        let barcodeTimeout;

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function () {
            checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));

            // Initialize scanner (hidden by default)
            initScanner();

            // Load scanned items from mobile scanner (if any)
            loadScannedItemsFromStorage();

            // Keyboard shortcuts
            document.addEventListener('keydown', handleKeyboardShortcuts);

            // USB Barcode Scanner listener
            document.addEventListener('keypress', handleBarcodeScanner);

            // Search functionality
            document.getElementById('searchInput').addEventListener('input', filterProducts);

            // Cash input change calculation
            document.getElementById('cashReceived').addEventListener('input', calculateChange);
        });

        // Load scanned items from mobile scanner session storage
        function loadScannedItemsFromStorage() {
            const saved = sessionStorage.getItem('scannedItems');
            if (saved) {
                try {
                    const scannedItems = JSON.parse(saved);
                    if (scannedItems && scannedItems.length > 0) {
                        // Add each scanned item to cart
                        scannedItems.forEach(item => {
                            const existingItem = cart.find(cartItem => cartItem.product_id === item.id);
                            if (existingItem) {
                                existingItem.quantity += item.quantity;
                            } else {
                                cart.push({
                                    product_id: item.id,
                                    name: item.name,
                                    price: parseFloat(item.price),
                                    quantity: item.quantity
                                });
                            }
                        });
                        renderCart();
                        
                        // Clear session storage after loading
                        sessionStorage.removeItem('scannedItems');
                        
                        // Show notification
                        const searchInput = document.getElementById('searchInput');
                        searchInput.value = `Loaded ${scannedItems.length} items from scanner`;
                        searchInput.style.backgroundColor = '#d1fae5';
                        setTimeout(() => {
                            searchInput.value = '';
                            searchInput.style.backgroundColor = '';
                        }, 2000);
                    }
                } catch (e) {
                    console.error('Failed to load scanned items:', e);
                }
            }
        }

        // Initialize barcode scanner
        function initScanner() {
            html5QrcodeScanner = new Html5Qrcode("reader");
        }

        // Toggle scanner visibility
        document.getElementById('toggleScanner').addEventListener('click', function () {
            const reader = document.getElementById('reader');
            const btn = this;

            if (scannerActive) {
                html5QrcodeScanner.stop().then(() => {
                    reader.style.display = 'none';
                    btn.textContent = 'Show Scanner';
                    scannerActive = false;
                }).catch(err => {
                    console.error('Failed to stop scanner:', err);
                });
            } else {
                reader.style.display = 'block';

                // Start scanning with back camera
                html5QrcodeScanner.start(
                    { facingMode: "environment" }, // Use back camera
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    onScanSuccess,
                    onScanError
                ).then(() => {
                    btn.textContent = 'Hide Scanner';
                    scannerActive = true;
                }).catch(err => {
                    console.error('Failed to start scanner:', err);
                    alert('Failed to access camera. Please check camera permissions.');
                    reader.style.display = 'none';
                });
            }
        });

        // Barcode scan success handler (Camera)
        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Camera scan result: ${decodedText}`);
            processBarcode(decodedText);
        }

        // Barcode scan error handler (Camera)
        function onScanError(errorMessage) {
            // Suppress error messages in console (normal behavior during scanning)
            // console.warn(errorMessage);
        }

        // USB Barcode Scanner handler
        function handleBarcodeScanner(e) {
            // Ignore if typing in input fields
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }

            // Clear timeout if exists
            if (barcodeTimeout) {
                clearTimeout(barcodeTimeout);
            }

            // Enter key means barcode scan is complete
            if (e.key === 'Enter' && barcodeBuffer.length > 0) {
                e.preventDefault();
                console.log(`USB scan result: ${barcodeBuffer}`);
                processBarcode(barcodeBuffer);
                barcodeBuffer = '';
            } else if (e.key.length === 1) {
                // Accumulate barcode characters
                barcodeBuffer += e.key;

                // Auto-clear buffer after 100ms of inactivity
                barcodeTimeout = setTimeout(() => {
                    barcodeBuffer = '';
                }, 100);
            }
        }

        // Process barcode (common function for both camera and USB scanner)
        function processBarcode(barcode) {
            const product = products.find(p => p.barcode === barcode);
            if (product) {
                addToCart(product.id);
                // Visual feedback
                const searchInput = document.getElementById('searchInput');
                searchInput.value = product.name;
                searchInput.style.backgroundColor = '#d1fae5';
                setTimeout(() => {
                    searchInput.value = '';
                    searchInput.style.backgroundColor = '';
                }, 1000);
            } else {
                // Visual feedback for not found
                const searchInput = document.getElementById('searchInput');
                searchInput.value = `Not found: ${barcode}`;
                searchInput.style.backgroundColor = '#fee2e2';
                setTimeout(() => {
                    searchInput.value = '';
                    searchInput.style.backgroundColor = '';
                }, 2000);
            }
        }

        // Add product to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;

            const existingItem = cart.find(item => item.product_id === productId);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    product_id: product.id,
                    name: product.name,
                    price: parseFloat(product.price),
                    quantity: 1
                });
            }
            renderCart();
        }

        // Remove item from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.product_id !== productId);
            renderCart();
        }

        // Update quantity
        function updateQuantity(productId, delta) {
            const item = cart.find(item => item.product_id === productId);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) {
                    removeFromCart(productId);
                } else {
                    renderCart();
                }
            }
        }

        // Render cart
        function renderCart() {
            const cartItemsEl = document.getElementById('cartItems');
            const subtotalEl = document.getElementById('subtotal');
            const totalEl = document.getElementById('total');
            const btnCheckout = document.getElementById('btnCheckout');

            if (cart.length === 0) {
                cartItemsEl.innerHTML = `
                    <div class="cart-empty">
                        <div class="cart-empty-icon">🛍️</div>
                        <div style="font-weight: 600;">Cart is empty</div>
                        <div style="font-size: 0.85rem; margin-top: 0.5rem;">Scan or click products to add</div>
                    </div>
                `;
                subtotalEl.textContent = 'Rp 0';
                totalEl.textContent = 'Rp 0';
                btnCheckout.disabled = true;
                return;
            }

            let html = '';
            let total = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                html += `
                    <div class="cart-item">
                        <div class="cart-item-header">
                            <div class="cart-item-name">${item.name}</div>
                            <button class="btn-remove-item" onclick="removeFromCart(${item.product_id})">×</button>
                        </div>
                        <div class="cart-item-controls">
                            <div class="quantity-controls">
                                <button class="btn-qty" onclick="updateQuantity(${item.product_id}, -1)">−</button>
                                <div class="qty-display">${item.quantity}</div>
                                <button class="btn-qty" onclick="updateQuantity(${item.product_id}, 1)">+</button>
                            </div>
                            <div class="cart-item-price">Rp ${itemTotal.toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                `;
            });

            cartItemsEl.innerHTML = html;
            subtotalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            totalEl.textContent = 'Rp ' + total.toLocaleString('id-ID');
            btnCheckout.disabled = false;
        }

        // Clear cart
        function clearCart() {
            if (cart.length === 0) return;
            if (confirm('Clear all items from cart?')) {
                cart = [];
                renderCart();
            }
        }

        // Hold transaction
        function holdTransaction() {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }

            // TODO: Implement hold transaction API call
            alert('Hold transaction feature - Coming soon!');
        }

        // Open checkout modal
        function openCheckout() {
            if (cart.length === 0) {
                alert('Cart is empty!');
                return;
            }

            const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('modalTotal').value = 'Rp ' + totalAmount.toLocaleString('id-ID');
            document.getElementById('cashReceived').value = '';
            document.getElementById('returnChange').value = 'Rp 0';

            checkoutModal.show();
            setTimeout(() => document.getElementById('cashReceived').focus(), 500);
        }

        // Select payment method
        function selectPaymentMethod(method) {
            currentPaymentMethod = method;

            // Update UI
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            document.querySelector(`[data-method="${method}"]`).classList.add('active');

            // Show/hide cash input
            const cashInputGroup = document.getElementById('cashInputGroup');
            if (method === 'cash') {
                cashInputGroup.style.display = 'block';
            } else {
                cashInputGroup.style.display = 'none';
            }
        }

        // Calculate change
        function calculateChange() {
            const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const cash = parseFloat(document.getElementById('cashReceived').value) || 0;
            const change = cash - totalAmount;
            document.getElementById('returnChange').value = 'Rp ' + (change > 0 ? change.toLocaleString('id-ID') : 0);
        }

        // Submit transaction
        async function submitTransaction() {
            const totalAmount = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const cash = parseFloat(document.getElementById('cashReceived').value) || 0;

            if (currentPaymentMethod === 'cash' && cash < totalAmount) {
                alert('Cash received is less than total amount!');
                return;
            }

            try {
                const response = await fetch("{{ route('pos.checkout') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        items: cart,
                        subtotal: totalAmount,
                        discount: 0,
                        total_amount: totalAmount,
                        cash_amount: cash,
                        payment_method: currentPaymentMethod
                    })
                });

                const result = await response.json();
                if (result.success) {
                    // Show success message
                    alert(`Transaction Successful!\n\nInvoice: ${result.transaction_id}\nTotal: Rp ${totalAmount.toLocaleString('id-ID')}\nChange: Rp ${result.change.toLocaleString('id-ID')}`);

                    // Open print receipt in new window
                    const receiptUrl = `/pos/receipt/${result.transaction_id}`;
                    window.open(receiptUrl, '_blank', 'width=400,height=600');

                    // Clear cart and close modal
                    cart = [];
                    renderCart();
                    checkoutModal.hide();
                } else {
                    alert('Transaction Failed: ' + result.message);
                }
            } catch (error) {
                console.error(error);
                alert('Error processing transaction');
            }
        }

        // Filter products
        function filterProducts() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const productCards = document.querySelectorAll('.product-card');

            productCards.forEach(card => {
                const name = card.getAttribute('data-name');
                const barcode = card.getAttribute('data-barcode');

                if (name.includes(searchTerm) || barcode.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Keyboard shortcuts
        function handleKeyboardShortcuts(e) {
            // F2 - Pay
            if (e.key === 'F2') {
                e.preventDefault();
                openCheckout();
            }
            // F4 - Hold
            else if (e.key === 'F4') {
                e.preventDefault();
                holdTransaction();
            }
            // F8 - Clear
            else if (e.key === 'F8') {
                e.preventDefault();
                clearCart();
            }
            // Esc - Cancel/Close modal
            else if (e.key === 'Escape') {
                if (checkoutModal._isShown) {
                    checkoutModal.hide();
                }
            }
        }
    </script>

</body>

</html>