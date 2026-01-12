<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS System - ARTIKA</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        :root {
            --primary: #85695a;
            --primary-dark: #6f5849;
            --primary-light: #a18072;
            --success: #10b981;
            --danger: #ef4444;
            --brown-50: #fdf8f6;
            --gray-100: #f5f5f4;
            --gray-200: #e7e5e4;
            --gray-300: #d6d3d1;
            --gray-700: #374151;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--gray-100);
            overflow: hidden;
        }

        html, body {
            height: 100%;
        }

        .pos-container {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* NAVBAR */
        .pos-navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(133, 105, 90, 0.15);
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .navbar-user {
            font-size: 0.85rem;
            opacity: 0.95;
        }

        .btn-logout {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* MAIN LAYOUT */
        .pos-main {
            flex: 1;
            display: flex;
            gap: 0;
            overflow: hidden;
        }

        .products-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
            border-right: 1px solid var(--gray-300);
            overflow: hidden;
        }

        /* SEARCH */
        .search-section {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .search-input-group {
            display: flex;
            align-items: center;
            background: var(--gray-100);
            border-radius: 8px;
            padding: 0 0.75rem;
        }

        .search-icon {
            color: var(--gray-700);
            margin-right: 0.5rem;
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 0.6rem;
            font-size: 0.9rem;
            outline: none;
        }

        /* CATEGORIES */
        .category-filter {
            display: flex;
            gap: 0.5rem;
            padding: 1rem;
            overflow-x: auto;
            border-bottom: 1px solid var(--gray-200);
            -webkit-overflow-scrolling: touch;
        }

        .category-filter::-webkit-scrollbar {
            height: 4px;
        }

        .category-filter::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 2px;
        }

        .category-btn {
            padding: 0.5rem 1rem;
            border: 1px solid var(--gray-300);
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.2s;
            color: var(--gray-700);
        }

        .category-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .category-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* PRODUCTS */
        .products-grid-container {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 1rem;
        }

        .product-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .product-card:hover {
            border-color: var(--primary);
            box-shadow: 0 2px 8px rgba(133, 105, 90, 0.1);
            transform: translateY(-2px);
        }

        .product-icon {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 0.4rem;
        }

        .product-name {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--gray-700);
            margin-bottom: 0.3rem;
            word-break: break-word;
        }

        .product-price {
            font-weight: 700;
            color: var(--primary);
            font-size: 0.75rem;
        }

        /* CART */
        .cart-section {
            width: 340px;
            display: flex;
            flex-direction: column;
            background: white;
            border-left: 1px solid var(--gray-300);
            overflow: hidden;
        }

        .cart-header {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .cart-title {
            font-weight: 700;
            font-size: 0.95rem;
            margin-bottom: 0.75rem;
        }

        .summary-stats {
            display: flex;
            gap: 1rem;
        }

        .stat-item {
            flex: 1;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.4rem 0.6rem;
            border-radius: 6px;
            text-align: center;
        }

        .stat-label {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        .stat-value {
            font-weight: 700;
            font-size: 0.9rem;
        }

        /* CART ITEMS */
        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 0.75rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .cart-items::-webkit-scrollbar {
            width: 5px;
        }

        .cart-items::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 3px;
        }

        .cart-item {
            background: var(--brown-50);
            padding: 0.6rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            border-left: 3px solid var(--primary);
        }

        .cart-item-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.3rem;
        }

        .cart-item-name {
            font-weight: 600;
            font-size: 0.8rem;
            color: var(--primary-dark);
        }

        .cart-item-price {
            font-weight: 700;
            color: #c17a5c;
            font-size: 0.8rem;
        }

        .cart-item-details {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.75rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.2rem;
            background: white;
            border-radius: 4px;
            padding: 0.1rem;
        }

        .qty-btn {
            background: none;
            border: none;
            width: 20px;
            height: 20px;
            cursor: pointer;
            font-weight: bold;
            color: var(--primary);
            transition: all 0.2s;
            font-size: 0.7rem;
        }

        .qty-btn:hover {
            background: var(--gray-200);
            border-radius: 3px;
        }

        .qty-display {
            width: 25px;
            text-align: center;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .cart-item-remove {
            background: var(--danger);
            color: white;
            border: none;
            width: 20px;
            height: 20px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 0.7rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-item-remove:hover {
            background: #dc2626;
        }

        .cart-empty {
            text-align: center;
            color: var(--gray-700);
            padding: 2rem 1rem;
            font-size: 0.85rem;
        }

        /* FOOTER */
        .cart-footer {
            padding: 1rem;
            background: var(--brown-50);
            border-top: 1px solid var(--gray-200);
        }

        .totals-section {
            margin-bottom: 1rem;
            background: white;
            padding: 0.75rem;
            border-radius: 6px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            padding: 0.4rem 0;
            border-bottom: 1px solid var(--gray-200);
        }

        .total-row:last-child {
            border-bottom: none;
        }

        .total-row.final {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--primary);
            padding-top: 0.5rem;
        }

        /* PAYMENT */
        .payment-section {
            margin-bottom: 1rem;
        }

        .payment-label {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.8rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.4rem;
        }

        .payment-method-btn {
            padding: 0.5rem;
            border: 1px solid var(--gray-300);
            background: white;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .payment-method-btn:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        .payment-method-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        /* BUTTONS */
        .checkout-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-checkout {
            flex: 1;
            padding: 0.6rem;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.2s;
            color: white;
        }

        .btn-checkout:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-cancel {
            background: var(--gray-300);
            color: var(--gray-700);
        }

        .btn-cancel:hover:not(:disabled) {
            background: var(--gray-200);
            transform: translateY(-2px);
        }

        .btn-finish {
            background: linear-gradient(135deg, var(--success) 0%, #059669 100%);
        }

        .btn-finish:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* MODAL */
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            border-radius: 10px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-radius: 10px 10px 0 0;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-title {
            font-weight: 700;
        }

        /* KEYPAD */
        .numeric-keypad {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .keypad-btn {
            padding: 0.75rem;
            border: 1px solid var(--gray-200);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .keypad-btn:hover {
            background: var(--gray-100);
            border-color: var(--primary);
        }

        .keypad-btn.delete {
            background: var(--danger);
            color: white;
            border-color: var(--danger);
        }

        .keypad-btn.delete:hover {
            background: #dc2626;
        }

        /* SCANNER */
        .scanner-section {
            display: none;
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            background: var(--brown-50);
        }

        .scanner-section.active {
            display: block;
        }

        .scanner-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .scanner-title {
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.85rem;
        }

        .btn-toggle-scanner {
            padding: 0.4rem 0.8rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.75rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-toggle-scanner:hover {
            background: var(--primary-dark);
        }

        #reader {
            width: 100%;
            max-height: 250px;
            border-radius: 6px;
            overflow: hidden;
        }

        @media (max-width: 1024px) {
            .cart-section {
                width: 300px;
            }
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(75px, 1fr));
            }
        }
    </style>
</head>

<body>
    <div class="pos-container">
        <!-- NAVBAR -->
        <div class="pos-navbar">
            <div class="navbar-brand">
                <i class="fas fa-shopping-cart"></i> ARTIKA POS
            </div>
            <div class="navbar-right">
                <div class="navbar-user">
                    {{ Auth::user()->name }}
                </div>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- MAIN -->
        <div class="pos-main">
            <!-- PRODUCTS -->
            <div class="products-section">
                <!-- SEARCH -->
                <div class="search-section">
                    <div class="search-input-group">
                        <span class="search-icon"><i class="fas fa-search"></i></span>
                        <input type="text" id="productSearch" class="search-input" placeholder="Cari produk...">
                    </div>
                </div>

                <!-- SCANNER TOGGLE -->
                <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200); text-align: right;">
                    <button id="openScannerBtn" class="btn-toggle-scanner">
                        <i class="fas fa-barcode"></i> Buka Scanner
                    </button>
                </div>

                <!-- SCANNER -->
                <div class="scanner-section" id="scannerSection">
                    <div class="scanner-header">
                        <span class="scanner-title"><i class="fas fa-barcode"></i> Barcode Scanner</span>
                        <button class="btn-toggle-scanner" id="closeScannerBtn">Tutup</button>
                    </div>
                    <div id="reader"></div>
                </div>

                <!-- CATEGORIES -->
                <div class="category-filter">
                    <button class="category-btn active" data-category="all">Semua</button>
                    @foreach($categories as $category)
                    <button class="category-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>

                <!-- PRODUCTS GRID -->
                <div class="products-grid-container">
                    <div class="products-grid" id="productsGrid">
                        @foreach($products as $product)
                        <div class="product-card" data-product-id="{{ $product->id }}" data-category="{{ $product->category_id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}">
                            <div class="product-icon"><i class="fas fa-box"></i></div>
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- CART -->
            <div class="cart-section">
                <!-- CART HEADER -->
                <div class="cart-header">
                    <div class="cart-title"><i class="fas fa-shopping-basket"></i> Keranjang</div>
                    <div class="summary-stats">
                        <div class="stat-item">
                            <div class="stat-label">Items</div>
                            <div class="stat-value" id="cartItemCount">0</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">Qty</div>
                            <div class="stat-value" id="cartQtyCount">0</div>
                        </div>
                    </div>
                </div>

                <!-- CART ITEMS -->
                <div class="cart-items" id="cartItems">
                    <div class="cart-empty">Keranjang kosong</div>
                </div>

                <!-- FOOTER -->
                <div class="cart-footer">
                    <!-- TOTALS -->
                    <div class="totals-section">
                        <div class="total-row">
                            <span>Total:</span>
                            <span id="totalDisplay">Rp0</span>
                        </div>
                    </div>

                    <!-- PAYMENT -->
                    <div class="payment-section">
                        <label class="payment-label"><i class="fas fa-wallet"></i> Metode</label>
                        <div class="payment-methods">
                            @foreach($paymentMethods as $method)
                            <button class="payment-method-btn" data-method="{{ $method->name }}">{{ $method->name }}</button>
                            @endforeach
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="checkout-buttons">
                        <button class="btn-checkout btn-cancel" id="clearBtn"><i class="fas fa-trash"></i></button>
                        <button class="btn-checkout btn-finish" id="checkoutBtn" onclick="checkout()" disabled><i class="fas fa-check-circle"></i> Selesaikan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KEYPAD MODAL -->
    <div class="modal fade" id="keypadModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 320px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Jumlah Uang</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="keypadDisplay" class="form-control" style="font-size: 1.2rem; font-weight: bold; text-align: right; margin-bottom: 1rem; padding: 10px;" placeholder="0" autocomplete="off" inputmode="decimal">
                    <div class="numeric-keypad">
                        <button class="keypad-btn" data-key="1">1</button>
                        <button class="keypad-btn" data-key="2">2</button>
                        <button class="keypad-btn" data-key="3">3</button>
                        <button class="keypad-btn" data-key="4">4</button>
                        <button class="keypad-btn" data-key="5">5</button>
                        <button class="keypad-btn" data-key="6">6</button>
                        <button class="keypad-btn" data-key="7">7</button>
                        <button class="keypad-btn" data-key="8">8</button>
                        <button class="keypad-btn" data-key="9">9</button>
                        <button class="keypad-btn" data-key="0" style="grid-column: 1 / 3;">0</button>
                        <button class="keypad-btn delete" id="keypadDelete"><i class="fas fa-backspace"></i></button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn" style="background: var(--primary); color: white;" id="keypadConfirm">Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script>
        let cart = [];
        let selectedPaymentMethod = null;
        let scanner = null;

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.payment-method-btn').forEach((btn, idx) => {
                if (idx === 0) {
                    btn.classList.add('active');
                    selectedPaymentMethod = btn.dataset.method;
                }
            });

            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', () => addToCart(card));
            });

            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.addEventListener('click', () => filterByCategory(btn));
            });

            document.getElementById('clearBtn').addEventListener('click', clearCart);
            document.getElementById('checkoutBtn').addEventListener('click', () => {
                console.log('Checkout button clicked');
                console.log('Cart items:', cart.length);
                console.log('Selected payment method:', selectedPaymentMethod);
                checkout();
            });
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                btn.addEventListener('click', () => selectPaymentMethod(btn));
            });

            document.getElementById('productSearch').addEventListener('keyup', searchProducts);
            
            // Scanner handlers
            document.getElementById('openScannerBtn').addEventListener('click', openScanner);
            document.getElementById('closeScannerBtn').addEventListener('click', closeScanner);
            
            initializeKeypad();
        });

        function addToCart(productCard) {
            const productId = productCard.dataset.productId;
            const productName = productCard.dataset.name;
            const productPrice = parseFloat(productCard.dataset.price);

            const existingItem = cart.find(item => item.product_id == productId);

            if (existingItem) {
                existingItem.quantity += 1;
                existingItem.subtotal = existingItem.quantity * existingItem.price;
            } else {
                cart.push({
                    product_id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1,
                    subtotal: productPrice
                });
            }

            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartItemsContainer = document.getElementById('cartItems');

            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<div class="cart-empty">Keranjang kosong</div>';
                document.getElementById('checkoutBtn').disabled = true;
            } else {
                cartItemsContainer.innerHTML = cart.map((item, index) => `
                    <div class="cart-item">
                        <div class="cart-item-header">
                            <span class="cart-item-name">${item.name}</span>
                            <span class="cart-item-price">Rp${formatCurrency(item.subtotal)}</span>
                        </div>
                        <div class="cart-item-details">
                            <div class="quantity-control">
                                <button class="qty-btn" onclick="decreaseQuantity(${index})">-</button>
                                <span class="qty-display">${item.quantity}</span>
                                <button class="qty-btn" onclick="increaseQuantity(${index})">+</button>
                            </div>
                            <button class="cart-item-remove" onclick="removeFromCart(${index})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `).join('');

                document.getElementById('checkoutBtn').disabled = false;
            }

            updateTotals();
        }

        function increaseQuantity(index) {
            cart[index].quantity += 1;
            cart[index].subtotal = cart[index].quantity * cart[index].price;
            updateCartDisplay();
        }

        function decreaseQuantity(index) {
            if (cart[index].quantity > 1) {
                cart[index].quantity -= 1;
                cart[index].subtotal = cart[index].quantity * cart[index].price;
            } else {
                removeFromCart(index);
            }
            updateCartDisplay();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartDisplay();
        }

        function clearCart() {
            if (cart.length > 0 && confirm('Yakin ingin menghapus semua item?')) {
                cart = [];
                updateCartDisplay();
            }
        }

        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
            document.getElementById('totalDisplay').textContent = 'Rp' + formatCurrency(subtotal);
            document.getElementById('cartItemCount').textContent = cart.length;
            document.getElementById('cartQtyCount').textContent = cart.reduce((sum, item) => sum + item.quantity, 0);
        }

        function selectPaymentMethod(btn) {
            document.querySelectorAll('.payment-method-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            selectedPaymentMethod = btn.dataset.method;
        }

        function filterByCategory(btn) {
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const categoryId = btn.dataset.category;
            document.querySelectorAll('.product-card').forEach(card => {
                if (categoryId === 'all' || card.dataset.category === categoryId) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        function searchProducts() {
            const searchTerm = document.getElementById('productSearch').value.toLowerCase();
            document.querySelectorAll('.product-card').forEach(card => {
                const name = card.dataset.name.toLowerCase();
                card.style.display = name.includes(searchTerm) ? '' : 'none';
            });
        }

        function checkout() {
            console.log('=== CHECKOUT FUNCTION CALLED ===');
            
            if (cart.length === 0) {
                alert('Keranjang kosong');
                console.log('Cart is empty');
                return;
            }

            const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
            const total = subtotal;

            console.log('Subtotal:', subtotal);
            console.log('Total:', total);
            console.log('Payment Method:', selectedPaymentMethod);

            if (!selectedPaymentMethod) {
                alert('Pilih metode pembayaran');
                return;
            }

            // Check if payment method is Cash
            const isCash = selectedPaymentMethod && selectedPaymentMethod.toLowerCase() === 'cash';

            if (isCash) {
                console.log('Opening cash input modal...');
                
                const modalElement = document.getElementById('keypadModal');
                if (!modalElement) {
                    console.error('keypadModal element not found!');
                    alert('Error: Modal tidak ditemukan');
                    return;
                }
                
                // Show cash input modal
                try {
                    const modal = new window.bootstrap.Modal(modalElement);
                    document.getElementById('keypadDisplay').value = '';
                    modal.show();

                    // Override confirm button for cash payment
                    const confirmBtn = document.getElementById('keypadConfirm');
                    if (!confirmBtn) {
                        console.error('keypadConfirm button not found!');
                        return;
                    }
                    
                    confirmBtn.onclick = () => {
                        console.log('Cash amount confirmed');
                        const cashAmount = parseFloat(document.getElementById('keypadDisplay').value);
                        
                        if (!cashAmount || cashAmount === 0) {
                            alert('Masukkan jumlah uang');
                            return;
                        }

                        if (cashAmount < total) {
                            alert(`Uang tidak cukup!\nKurang: Rp${formatCurrency(total - cashAmount)}`);
                            return;
                        }

                        modal.hide();
                        processCheckout(cart, subtotal, total, cashAmount);
                    };
                } catch (err) {
                    console.error('Error creating modal:', err);
                    alert('Error: Gagal membuka modal: ' + err.message);
                }
            } else {
                // Non-cash payment (QRIS, Card, E-Wallet, etc.)
                console.log('Processing non-cash payment...');
                processCheckout(cart, subtotal, total, total);
            }
        }

        function processCheckout(items, subtotal, total, cashAmount) {
            console.log('=== PROCESS CHECKOUT ===');
            console.log('Items:', items.length);
            console.log('Subtotal:', subtotal);
            console.log('Total:', total);
            console.log('Cash Amount:', cashAmount);
            console.log('Payment Method:', selectedPaymentMethod);

            const change = cashAmount - total;

            const data = {
                items: items,
                subtotal: subtotal,
                discount: 0,
                total_amount: total,
                payment_method: selectedPaymentMethod,
                cash_amount: cashAmount,
                change_amount: change > 0 ? change : 0
            };

            console.log('Request data:', JSON.stringify(data));

            // Show loading
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (!checkoutBtn) {
                console.error('Checkout button not found!');
                return;
            }

            const originalText = checkoutBtn.innerHTML;
            checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            checkoutBtn.disabled = true;

            const url = '{{ route("pos.checkout") }}';
            console.log('Posting to:', url);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                return response.text().then(text => {
                    console.log('Response text:', text);
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Failed to parse JSON:', e);
                        throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                    }
                });
            })
            .then(result => {
                console.log('Parsed result:', result);
                checkoutBtn.innerHTML = originalText;
                checkoutBtn.disabled = false;

                if (result.success) {
                    const isCash = selectedPaymentMethod && selectedPaymentMethod.toLowerCase() === 'cash';
                    
                    if (isCash && change > 0) {
                        alert(`✓ Transaksi Berhasil!\n\nInvoice: ${result.invoice_no}\nUang Diterima: Rp${formatCurrency(cashAmount)}\nKembalian: Rp${formatCurrency(change)}`);
                    } else {
                        alert(`✓ Transaksi Berhasil!\n\nInvoice: ${result.invoice_no}`);
                    }

                    // Clear cart
                    cart = [];
                    updateCartDisplay();

                    // Open receipt
                    if (result.transaction_id) {
                        console.log('Opening receipt:', result.transaction_id);
                        window.open('{{ url("pos/receipt") }}/' + result.transaction_id, '_blank');
                    }
                } else {
                    alert('❌ Error: ' + (result.message || 'Transaksi gagal'));
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                checkoutBtn.innerHTML = originalText;
                checkoutBtn.disabled = false;
                alert('❌ Error: ' + error.message);
            });
        }

        function initializeKeypad() {
            console.log('Initializing keypad...');
            
            const display = document.getElementById('keypadDisplay');
            const keypadBtns = document.querySelectorAll('.keypad-btn[data-key]');
            console.log('Found keypad buttons:', keypadBtns.length);
            
            // Button click handlers
            keypadBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    if (display) {
                        display.value += btn.dataset.key;
                        display.focus();
                    }
                });
            });

            // Delete button handler
            const deleteBtn = document.getElementById('keypadDelete');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', () => {
                    if (display) {
                        display.value = display.value.slice(0, -1);
                        display.focus();
                    }
                });
            }
            
            // Keyboard input handler - only accept numbers and backspace
            if (display) {
                display.addEventListener('keydown', (e) => {
                    console.log('Keypress:', e.key);
                    
                    // Allow: numbers (0-9), backspace, delete
                    if (!/^[0-9]$/.test(e.key) && 
                        e.key !== 'Backspace' && 
                        e.key !== 'Delete' && 
                        e.key !== 'ArrowLeft' && 
                        e.key !== 'ArrowRight' && 
                        e.key !== 'Home' && 
                        e.key !== 'End') {
                        e.preventDefault();
                    }
                });
                
                // Prevent pasting non-numeric content
                display.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const numericOnly = pastedText.replace(/[^0-9]/g, '');
                    if (numericOnly) {
                        display.value += numericOnly;
                    }
                });
            }
            
            console.log('Keypad initialized');
        }

        function formatCurrency(value) {
            return Math.round(value).toLocaleString('id-ID');
        }

        // SCANNER FUNCTIONS
        function openScanner() {
            const scannerSection = document.getElementById('scannerSection');
            scannerSection.classList.add('active');

            if (!scanner) {
                scanner = new Html5Qrcode("reader");
                scanner.start(
                    { facingMode: "environment" },
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    onScanSuccess,
                    onScanFailure
                ).catch(err => console.log("Camera error:", err));
            }
        }

        function closeScanner() {
            const scannerSection = document.getElementById('scannerSection');
            scannerSection.classList.remove('active');

            if (scanner) {
                scanner.stop().then(() => {
                    scanner = null;
                }).catch(err => console.log("Stop scanner error:", err));
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            const product = document.querySelector(`[data-product-id="${decodedText}"]`) ||
                          document.querySelector(`[data-barcode="${decodedText}"]`);
            
            if (product) {
                addToCart(product);
                console.log('Scanned:', decodedText);
            } else {
                // Try to find by barcode in data attribute
                const allProducts = document.querySelectorAll('.product-card');
                for (let prod of allProducts) {
                    if (prod.dataset.productId === decodedText) {
                        addToCart(prod);
                        return;
                    }
                }
                alert('Produk tidak ditemukan: ' + decodedText);
            }
        }

        function onScanFailure(error) {
            // Suppress error logs for failed reads
        }
    </script>
</body>

</html>
