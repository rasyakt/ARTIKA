<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>{{ __('pos.title') }}</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* SweetAlert2 Custom Theme ARTIKA */
        .artika-swal-popup {
            border-radius: 16px !important;
            padding: 1.5rem !important;
            border: 1px solid #f2e8e5 !important;
            font-family: 'Segoe UI', system-ui, sans-serif !important;
        }

        .artika-swal-title {
            color: #4b382f !important;
            font-weight: 700 !important;
            font-size: 1.25rem !important;
        }

        .artika-swal-confirm-btn {
            background: #6f5849 !important;
            border-radius: 10px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
            color: white !important;
        }

        .artika-swal-cancel-btn {
            background: #fdf8f6 !important;
            color: #6f5849 !important;
            border: 1px solid #f2e8e5 !important;
            border-radius: 10px !important;
            padding: 0.6rem 1.5rem !important;
            font-weight: 600 !important;
        }

        .artika-swal-toast {
            border-radius: 12px !important;
            background: #ffffff !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

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
            -webkit-tap-highlight-color: transparent;
        }

        /* Touch-friendly sizing */
        input,
        button,
        select,
        textarea {
            font-size: 16px;
            /* Prevents auto-zoom on iOS */
        }

        /* Smooth scrolling on mobile */
        .products-grid-container,
        .cart-items {
            -webkit-overflow-scrolling: touch;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--gray-100);
            overflow: hidden;
        }

        html,
        body {
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

        .profile-trigger {
            transition: all 0.2s;
            border-radius: 12px;
        }

        .profile-trigger:hover .profile-avatar {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.2s;
        }

        .dropdown-menu {
            border: 1px solid rgba(133, 105, 90, 0.1);
            animation: slideIn 0.2s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item:hover {
            background-color: var(--brown-50);
            color: var(--primary-dark);
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
            padding: 0.75rem 1rem;
            overflow-x: auto;
            border-bottom: 2px solid var(--gray-200);
            -webkit-overflow-scrolling: touch;
            position: sticky;
            top: 0;
            background: linear-gradient(to right, white 0%, white 95%, rgba(255, 255, 255, 0.8) 100%);
            z-index: 50;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            align-items: center;
        }

        .category-filter::-webkit-scrollbar {
            height: 5px;
        }

        .category-filter::-webkit-scrollbar-track {
            background: transparent;
        }

        .category-filter::-webkit-scrollbar-thumb {
            background: var(--primary-light);
            border-radius: 2px;
        }

        .category-filter::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        .category-btn {
            padding: 0.65rem 1.3rem;
            border: 2px solid var(--gray-300);
            background: white;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--gray-700);
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-user-select: none;
            user-select: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
            position: relative;
            overflow: hidden;
            flex-shrink: 0;
        }

        .category-btn:hover {
            border-color: var(--primary-light);
            color: var(--primary);
            box-shadow: 0 4px 12px rgba(133, 105, 90, 0.15);
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.95);
        }

        .category-btn.active {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-color: transparent;
            box-shadow: 0 6px 16px rgba(133, 105, 90, 0.25);
            font-weight: 700;
            transform: translateY(-1px);
        }

        .category-btn.active:hover {
            box-shadow: 0 8px 20px rgba(133, 105, 90, 0.3);
        }

        .category-btn:active:not(.active) {
            transform: scale(0.95);
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.12);
        }

        .category-btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.5), transparent);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .category-btn:active::after {
            animation: ripple 0.6s ease-out;
        }

        @keyframes ripple {
            0% {
                width: 0;
                height: 0;
            }

            100% {
                width: 300px;
                height: 300px;
            }
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
            min-height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            -webkit-user-select: none;
            user-select: none;
            position: relative;
        }

        .product-card:active {
            transform: scale(0.95);
            box-shadow: 0 4px 12px rgba(133, 105, 90, 0.2);
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
            width: 32px;
            height: 32px;
            cursor: pointer;
            font-weight: bold;
            color: var(--primary);
            transition: all 0.2s;
            font-size: 0.85rem;
            min-height: 32px;
            min-width: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
        }

        .qty-btn:active {
            background: var(--primary);
            color: white;
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
            padding: 0.75rem;
            border: 1px solid var(--gray-300);
            background: white;
            border-radius: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
            white-space: nowrap;
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
            padding: 0.75rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
            color: white;
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-user-select: none;
            user-select: none;
        }

        .btn-checkout:active:not(:disabled) {
            transform: scale(0.96);
        }

        .btn-checkout:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-cancel {
            background: var(--gray-300);
            color: var(--gray-700);
            border-radius: 10px;
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
            padding: 1rem;
            border: 1px solid var(--gray-200);
            background: white;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.1rem;
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

        /* TABLET - MEDIUM SCREENS */
        @media (max-width: 768px) {
            .pos-navbar {
                padding: 0.5rem 1rem;
                height: 50px;
            }

            .navbar-brand {
                font-size: 1rem;
            }

            .navbar-right {
                gap: 0.75rem;
            }

            .navbar-user {
                font-size: 0.75rem;
            }

            .btn-logout {
                padding: 0.3rem 0.6rem;
                font-size: 0.7rem;
            }

            .cart-section {
                width: 280px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
                gap: 0.75rem;
            }

            .product-icon {
                font-size: 1.5rem;
            }

            .category-filter {
                padding: 0.75rem 0.75rem;
                gap: 0.45rem;
                border-bottom: 2px solid var(--gray-200);
                background: linear-gradient(to right, white 0%, white 95%, rgba(255, 255, 255, 0.8) 100%);
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            }

            .category-btn {
                padding: 0.55rem 1rem;
                font-size: 0.8rem;
                min-height: 37px;
                border-radius: 9px;
                border: 2px solid var(--gray-300);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
            }

            .category-btn:hover {
                box-shadow: 0 4px 12px rgba(133, 105, 90, 0.15);
                border-color: var(--primary-light);
            }

            .category-btn.active {
                box-shadow: 0 6px 16px rgba(133, 105, 90, 0.25);
                border-color: transparent;
            }

            .cart-items::-webkit-scrollbar {
                width: 4px;
            }

            .payment-methods {
                gap: 0.3rem;
            }

            .payment-method-btn {
                padding: 0.4rem;
                font-size: 0.7rem;
            }

            .numeric-keypad {
                gap: 0.4rem;
            }

            .keypad-btn {
                padding: 0.6rem;
                font-size: 0.9rem;
            }
        }

        /* MOBILE - SMALL SCREENS */
        @media (max-width: 576px) {
            body {
                overflow: hidden !important;
            }

            html,
            body {
                height: 100%;
            }

            .pos-container {
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .pos-navbar {
                padding: 0 1rem;
                height: 52px;
                flex-shrink: 0;
                background: var(--primary);
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                position: relative;
            }

            .navbar-brand {
                font-size: 1rem;
                font-weight: 700;
            }

            .navbar-right {
                gap: 0.35rem;
            }

            .profile-trigger {
                transition: transform 0.2s ease;
            }

            .profile-trigger:active {
                transform: scale(0.92);
            }

            .profile-avatar {
                width: 38px;
                height: 38px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.4rem;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .dropdown-item {
                transition: all 0.2s ease;
            }

            .dropdown-item:active {
                background-color: var(--brown-50);
                color: var(--primary-dark);
            }

            /* Main layout stays flexible */
            .pos-main {
                flex-direction: column;
                flex: 1;
                overflow: hidden;
                position: relative;
            }

            .products-section {
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                border: none;
            }

            /* View toggling */
            #mobileCartView {
                display: none;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: white;
                z-index: 150;
                flex-direction: column;
            }

            #mobileCartView.active {
                display: flex;
            }

            .cart-section {
                width: 100%;
                height: 100%;
                border: none;
                display: flex;
                flex-direction: column;
            }

            .search-section {
                padding: 0.85rem 1rem;
                background: white;
                border-bottom: 1px solid var(--gray-100);
            }

            .search-input-group {
                background: #f1f3f5;
                border: 1px solid rgba(0, 0, 0, 0.05);
                border-radius: 50px;
                padding: 0 1rem;
                height: 44px;
                transition: all 0.2s ease;
            }

            .search-input-group:focus-within {
                background: white;
                border-color: var(--primary-light);
                box-shadow: 0 4px 12px rgba(133, 105, 90, 0.1);
            }

            .search-input {
                font-size: 1rem;
                height: 100%;
            }

            .category-filter {
                position: sticky;
                top: 0;
                z-index: 40;
                padding: 0.8rem 0.75rem;
                border-bottom: 1px solid var(--gray-200);
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(15px);
                display: flex;
                gap: 0.6rem;
                overflow-x: auto;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }

            .category-filter::-webkit-scrollbar {
                display: none;
            }

            .category-btn {
                padding: 0.5rem 1.25rem;
                font-size: 0.8rem;
                border-radius: 50px;
                border: 1px solid var(--gray-300);
                background: white;
                color: var(--gray-700);
                white-space: nowrap;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                font-weight: 500;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
                flex-shrink: 0;
            }

            .category-btn.active {
                background: var(--primary);
                color: white;
                border-color: var(--primary);
                box-shadow: 0 4px 10px rgba(133, 105, 90, 0.3);
            }

            .products-grid-container {
                padding: 1rem 0.75rem;
                flex: 1;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                background: #f8f9fa;
            }

            .products-grid {
                display: flex;
                flex-direction: column;
                gap: 0;
                background: white;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            }

            .product-card {
                padding: 1.15rem 1rem;
                min-height: auto;
                border-radius: 0;
                background: white;
                border: none;
                border-bottom: 1px solid var(--gray-100);
                box-shadow: none;
                transition: background 0.2s ease;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                text-align: left;
                position: relative;
                -webkit-tap-highlight-color: transparent;
            }

            .product-card:last-child {
                border-bottom: none;
            }

            .product-card:active {
                background: #fdfaf8;
                transform: none;
            }

            .product-icon,
            .stock-badge {
                display: none !important;
            }

            .product-name {
                font-size: 0.92rem;
                font-weight: 600;
                color: var(--gray-800);
                margin-bottom: 0;
                flex: 1;
                line-height: 1.3;
                padding-right: 1rem;
                display: block;
                overflow: visible;
                text-overflow: unset;
                -webkit-line-clamp: unset;
            }

            .product-price {
                font-size: 0.95rem;
                font-weight: 700;
                color: var(--primary);
                white-space: nowrap;
            }

            /* Bottom Navigation */
            .bottom-nav {
                display: flex;
                height: 65px;
                background: white;
                border-top: 1px solid var(--gray-200);
                padding-bottom: env(safe-area-inset-bottom);
                flex-shrink: 0;
                z-index: 200;
                box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.06);
            }

            .nav-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                color: var(--gray-700);
                text-decoration: none;
                font-size: 0.72rem;
                font-weight: 600;
                gap: 0.25rem;
                opacity: 0.55;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .nav-item.active {
                color: var(--primary);
                opacity: 1;
            }

            .nav-item i {
                font-size: 1.25rem;
            }

            /* Floating Cart Button */
            .fab-cart {
                position: absolute;
                bottom: 85px;
                right: 20px;
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
                color: white;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 6px 20px rgba(133, 105, 90, 0.45);
                z-index: 100;
                border: none;
                transition: transform 0.2s, background 0.2s;
            }

            .fab-cart:active {
                transform: scale(0.92);
            }

            .fab-badge {
                position: absolute;
                top: -5px;
                right: -5px;
                background: var(--danger);
                color: white;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                font-size: 0.7rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 700;
                border: 2px solid white;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .cart-items {
                flex: 1;
                max-height: none;
                overflow-y: auto;
            }

            .cart-footer {
                padding: 1rem;
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(15px);
                border-top: 1px solid var(--gray-200);
            }

            .btn-checkout {
                min-height: 54px;
                border-radius: 14px;
                font-size: 1.05rem;
            }

            .scanner-section {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: black;
                z-index: 1000;
                flex-direction: column;
            }

            .scanner-section.active {
                display: flex;
            }

            .scanner-header {
                padding: 1rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                color: white;
                background: rgba(0, 0, 0, 0.5);
            }

            #reader {
                flex: 1;
                width: 100% !important;
                border: none !important;
                border-radius: 0 !important;
            }
        }

        /* Responsive Visibility Helpers */
        @media (max-width: 576px) {
            .pos-main>.cart-section {
                display: none !important;
            }
        }

        @media (min-width: 577px) {

            .bottom-nav,
            .fab-cart,
            #mobileCartView {
                display: none !important;
            }

            .pos-main>.cart-section {
                display: flex !important;
            }
        }

        /* EXTRA SMALL SCREENS */
        @media (max-width: 380px) {
            .navbar-brand {
                font-size: 0.85rem;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(55px, 1fr));
                gap: 0.5rem;
            }

            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }

            .products-grid-container,
            .search-section {
                padding: 0.5rem;
            }

            .category-filter {
                padding: 0.6rem 0.5rem;
                gap: 0.35rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.07);
            }

            .category-btn {
                padding: 0.5rem 0.85rem;
                font-size: 0.72rem;
                min-height: 36px;
                border-radius: 7px;
                min-width: 60px;
            }
        }
    </style>
</head>

<body>
    <div class="pos-container">
        <!-- NAVBAR -->
        <div class="pos-navbar">
            <div class="navbar-brand">
                <i class="fas fa-shopping-cart"></i> {{ __('pos.brand') }}
            </div>
            <div class="navbar-right">
                <div class="dropdown">
                    <button class="btn p-0 border-0 profile-trigger d-flex align-items-center" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <span class="ms-2 fw-600 text-white d-none d-lg-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0 overflow-hidden"
                        style="min-width: 240px; border-radius: 16px;">
                        <li class="p-3 bg-light border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 42px; height: 42px; font-size: 1.2rem;">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h6 class="mb-0 fw-800 text-truncate">{{ Auth::user()->name }}</h6>
                                    <div class="small text-muted text-truncate">@ {{ Auth::user()->username }}</div>
                                    <div class="small fw-700 text-primary" style="font-size: 0.7rem;">NIS:
                                        {{ Auth::user()->nis ?? '-' }}</div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center"
                                href="{{ route('pos.history') }}">
                                <i class="fa-solid fa-clock-rotate-left me-3 text-primary opacity-75"></i>
                                <span class="fw-600">Riwayat Transaksi</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="{{ route('pos.logs') }}">
                                <i class="fa-solid fa-list-check me-3 text-primary opacity-75"></i>
                                <span class="fw-600">Log Aktivitas</span>
                            </a>
                        </li>
                        <li class="border-top mt-1">
                            <button type="button" class="dropdown-item py-3 px-3 d-flex align-items-center text-danger"
                                id="btnLogout">
                                <i class="fas fa-sign-out-alt me-3"></i>
                                <span class="fw-700">Keluar Sistem</span>
                            </button>
                        </li>
                    </ul>
                </div>
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
                        <input type="text" id="productSearch" class="search-input"
                            placeholder="{{ __('pos.search_placeholder') }}">
                    </div>
                </div>

                <!-- SCANNER TOGGLE -->
                <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--gray-200); text-align: right;">
                    <button id="openScannerBtn" class="btn-toggle-scanner">
                        <i class="fas fa-barcode "></i> {{ __('pos.open_scanner') }}
                    </button>
                </div>

                <!-- SCANNER -->
                <div class="scanner-section" id="scannerSection">
                    <div class="scanner-header">
                        <span class="scanner-title"><i class="fas fa-barcode"></i> {{ __('pos.scanner_title') }}</span>
                        <button class="btn-toggle-scanner" id="closeScannerBtn">{{ __('common.close') }}</button>
                    </div>
                    <div id="reader"></div>
                </div>

                <!-- CATEGORIES -->
                <div class="category-filter">
                    <button class="category-btn active" data-category="all">{{ __('common.all') }}</button>
                    @foreach($categories as $category)
                        <button class="category-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>

                <!-- PRODUCTS GRID -->
                <div class="products-grid-container">
                    <div class="products-grid" id="productsGrid">
                        @foreach($products as $product)
                            <div class="product-card" data-product-id="{{ $product->id }}"
                                data-category="{{ $product->category_id }}" data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}">
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
                    <div class="cart-title"><i class="fas fa-shopping-basket"></i> {{ __('pos.cart') }}</div>
                    <div class="summary-stats">
                        <div class="stat-item">
                            <div class="stat-label">{{ __('pos.items') }}</div>
                            <div class="stat-value cartItemCount">0</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label">{{ __('pos.qty') }}</div>
                            <div class="stat-value cartQtyCount">0</div>
                        </div>
                    </div>
                </div>

                <!-- CART ITEMS -->
                <div class="cart-items cartItemsContainer" id="cartItems">
                    <div class="cart-empty">{{ __('pos.cart_empty') }}</div>
                </div>

                <!-- FOOTER -->
                <div class="cart-footer">
                    <!-- TOTALS -->
                    <div class="totals-section">
                        <div class="total-row">
                            <span>{{ __('common.total') }}:</span>
                            <span class="totalDisplay" id="totalDisplay">Rp0</span>
                        </div>
                    </div>

                    <!-- PAYMENT -->
                    <div class="payment-section">
                        <label class="payment-label"><i class="fas fa-wallet"></i>
                            {{ __('pos.payment_method') }}</label>
                        <div class="payment-methods">
                            <button class="payment-method-btn paymentMethodBtn active"
                                data-method="cash">{{ __('pos.cash') }}</button>
                            <button class="payment-method-btn paymentMethodBtn"
                                data-method="non-cash">{{ __('pos.non_cash') }}</button>
                        </div>
                    </div>

                    <!-- BUTTONS -->
                    <div class="checkout-buttons">
                        <button class="btn-checkout btn-cancel clearBtn" id="clearBtn"><i
                                class="fas fa-trash"></i></button>
                        <button class="btn-checkout btn-finish checkoutBtn" id="checkoutBtn" onclick="checkout()"
                            disabled><i class="fas fa-check-circle"></i> {{ __('pos.checkout') }}</button>
                    </div>
                </div>
            </div>

            <!-- MOBILE CART VIEW (OVERLAY) -->
            <div id="mobileCartView">
                <div class="cart-header">
                    <div class="cart-title" id="closeCartBtn">
                        <i class="fas fa-arrow-left me-2"></i> {{ __('pos.cart') }}
                    </div>
                    <div class="summary-stats">
                        <div class="stat-item">
                            <div class="stat-label">{{ __('pos.items') }}</div>
                            <div class="stat-value cartItemCount">0</div>
                        </div>
                    </div>
                </div>
                <div class="cart-items cartItemsContainer" id="cartItemsMobile">
                    <div class="cart-empty">{{ __('pos.cart_empty') }}</div>
                </div>
                <div class="cart-footer">
                    <div class="totals-section">
                        <div class="total-row final">
                            <span>{{ __('common.total') }}:</span>
                            <span class="totalDisplay">Rp0</span>
                        </div>
                    </div>
                    <div class="payment-section">
                        <div class="payment-methods">
                            <button class="payment-method-btn paymentMethodBtn active"
                                data-method="cash">{{ __('pos.cash') }}</button>
                            <button class="payment-method-btn paymentMethodBtn"
                                data-method="non-cash">{{ __('pos.non_cash') }}</button>
                        </div>
                    </div>
                    <div class="checkout-buttons">
                        <button class="btn-checkout btn-cancel clearBtn"><i class="fas fa-trash"></i></button>
                        <button class="btn-checkout btn-finish checkoutBtn" disabled>
                            <i class="fas fa-check-circle me-2"></i> {{ __('pos.checkout') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAB for Mobile -->
        <button class="fab-cart" id="fabCart" style="display: none;">
            <i class="fas fa-shopping-cart"></i>
            <span class="fab-badge cartQtyCount">0</span>
        </button>

        <!-- BOTTOM NAV for Mobile -->
        <div class="bottom-nav">
            <a href="#" class="nav-item active" id="navShop">
                <i class="fas fa-th-large"></i>
                <span>Produk</span>
            </a>
            <a href="#" class="nav-item" id="navCart">
                <i class="fas fa-shopping-basket"></i>
                <span>Keranjang</span>
            </a>
            <a href="#" class="nav-item" id="navScannerMobile">
                <i class="fas fa-barcode"></i>
                <span>Scan</span>
            </a>
            <a href="{{ route('pos.history') }}" class="nav-item">
                <i class="fas fa-history"></i>
                <span>Riwayat</span>
            </a>
        </div>
    </div>

    <!-- KEYPAD MODAL -->
    <div class="modal fade" id="keypadModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 320px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">{{ __('pos.cash_amount') }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Cash Input Section -->
                    <div id="cashInputSection">
                        <input type="text" id="keypadDisplay" class="form-control"
                            style="font-size: 1.2rem; font-weight: bold; text-align: right; margin-bottom: 1rem; padding: 10px;"
                            placeholder="0" autocomplete="off" inputmode="decimal">
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
                            <button class="keypad-btn delete" id="keypadDelete"><i
                                    class="fas fa-backspace"></i></button>
                        </div>
                    </div>

                    <!-- Non-Cash Upload Section -->
                    <div id="nonCashInputSection" style="display: none;">
                        <div class="text-center mb-3">
                            <label class="form-label fw-bold">{{ __('pos.upload_proof') }} <span
                                    class="text-danger">*</span></label>

                            <!-- Preview Area -->
                            <div id="previewContainer" class="d-none mb-3">
                                <img id="imagePreview" src="" alt="Preview" class="img-fluid rounded mb-2"
                                    style="max-height: 200px;">
                                <button type="button" class="btn btn-sm btn-outline-danger w-100"
                                    id="removeImageBtn">{{ __('pos.retake_photo') }}</button>
                            </div>

                            <!-- Upload Buttons -->
                            <div id="uploadButtons" class="d-grid gap-2">
                                <button type="button" class="btn btn-primary" id="btnCamera">
                                    <i class="fas fa-camera me-2"></i> {{ __('pos.take_photo') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="btnGallery">
                                    <i class="fas fa-image me-2"></i> {{ __('pos.choose_from_gallery') }}
                                </button>
                            </div>

                            <!-- Hidden Inputs -->
                            <input type="file" id="inputCamera" class="d-none" accept="image/*" capture="environment">
                            <input type="file" id="inputGallery" class="d-none" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('pos.cancel') }}</button>
                    <button type="button" class="btn" style="background: var(--primary); color: white;"
                        id="keypadConfirm">{{ __('pos.confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])
    <script>
        // Professional Notification Helpers
        const ArtikaToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'artika-swal-toast' },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        function showToast(icon, title) {
            ArtikaToast.fire({ icon: icon, title: title });
        }

        function confirmAction(options = {}) {
            const defaults = {
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini tidak dapat dibatalkan!",
                icon: 'warning',
                confirmButtonText: 'Ya, Lanjutkan!',
                cancelButtonText: 'Batal'
            };
            const settings = { ...defaults, ...options };
            return Swal.fire({
                title: settings.title,
                text: settings.text,
                icon: settings.icon,
                showCancelButton: true,
                confirmButtonColor: '#6f5849',
                cancelButtonColor: '#f1f1f1',
                confirmButtonText: settings.confirmButtonText,
                cancelButtonText: settings.cancelButtonText,
                customClass: {
                    popup: 'artika-swal-popup',
                    title: 'artika-swal-title',
                    confirmButton: 'artika-swal-confirm-btn',
                    cancelButton: 'artika-swal-cancel-btn'
                },
                buttonsStyling: false
            });
        }

        let cart = [];
        let selectedPaymentMethod = null;
        let scanner = null;

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.paymentMethodBtn').forEach((btn, idx) => {
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

            document.querySelectorAll('.clearBtn').forEach(btn => {
                btn.addEventListener('click', clearCart);
            });

            document.querySelectorAll('.checkoutBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    console.log('Checkout button clicked');
                    checkout();
                });
            });

            document.querySelectorAll('.paymentMethodBtn').forEach(btn => {
                btn.addEventListener('click', () => selectPaymentMethod(btn.dataset.method));
            });

            document.getElementById('productSearch').addEventListener('keyup', searchProducts);

            // Scanner handlers
            document.getElementById('openScannerBtn').addEventListener('click', openScanner);
            document.getElementById('closeScannerBtn').addEventListener('click', closeScanner);
            document.getElementById('navScannerMobile').addEventListener('click', (e) => {
                e.preventDefault();
                openScanner();
            });

            // Mobile Navigation and Cart logic
            const mobileCartView = document.getElementById('mobileCartView');
            const fabCart = document.getElementById('fabCart');
            const navShop = document.getElementById('navShop');
            const navCart = document.getElementById('navCart');
            const closeCartBtn = document.getElementById('closeCartBtn');

            function toggleMobileCart(show) {
                if (show) {
                    mobileCartView.classList.add('active');
                    navCart.classList.add('active');
                    if (navShop) navShop.classList.remove('active');
                    fabCart.style.display = 'none';
                } else {
                    mobileCartView.classList.remove('active');
                    navCart.classList.remove('active');
                    if (navShop) navShop.classList.add('active');
                    if (cart.length > 0) fabCart.style.display = 'flex';
                }
            }

            if (navCart) navCart.addEventListener('click', (e) => {
                e.preventDefault();
                toggleMobileCart(true);
            });
            if (navShop) navShop.addEventListener('click', (e) => {
                e.preventDefault();
                toggleMobileCart(false);
            });
            if (fabCart) fabCart.addEventListener('click', () => toggleMobileCart(true));
            if (closeCartBtn) closeCartBtn.addEventListener('click', () => toggleMobileCart(false));

            // Auto-focus payment input when modal opens
            const keypadModal = document.getElementById('keypadModal');
            if (keypadModal) {
                keypadModal.addEventListener('shown.bs.modal', function () {
                    const display = document.getElementById('keypadDisplay');
                    const cashSection = document.getElementById('cashInputSection');
                    if (display && cashSection.style.display !== 'none') {
                        display.focus();
                    }
                });
            }

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

            // Show FAB on mobile if we're not in cart view
            if (window.innerWidth <= 576 && !document.getElementById('mobileCartView').classList.contains('active')) {
                document.getElementById('fabCart').style.display = 'flex';
            }
        }

        function updateCartDisplay() {
            const cartContainers = document.querySelectorAll('.cartItemsContainer');
            const checkoutBtns = document.querySelectorAll('.checkoutBtn');
            const fabCart = document.getElementById('fabCart');

            if (cart.length === 0) {
                cartContainers.forEach(c => c.innerHTML = '<div class="cart-empty">{{ __('pos.cart_empty') }}</div>');
                checkoutBtns.forEach(b => b.disabled = true);
                if (fabCart) fabCart.style.display = 'none';
            } else {
                const cartHtml = cart.map((item, index) => `
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

                cartContainers.forEach(c => c.innerHTML = cartHtml);
                checkoutBtns.forEach(b => b.disabled = false);
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
            if (cart.length > 0) {
                confirmAction({
                    text: '{{ __('pos.confirm_clear_cart') }}',
                    confirmButtonText: '{{ __('common.delete') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        cart = [];
                        updateCartDisplay();
                    }
                });
            }
        }

        function updateTotals() {
            const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
            const qtyCount = cart.reduce((sum, item) => sum + item.quantity, 0);

            document.querySelectorAll('.totalDisplay').forEach(el => el.textContent = 'Rp' + formatCurrency(subtotal));
            document.querySelectorAll('.cartItemCount').forEach(el => el.textContent = cart.length);
            document.querySelectorAll('.cartQtyCount').forEach(el => el.textContent = qtyCount);
        }

        function selectPaymentMethod(method) {
            document.querySelectorAll('.paymentMethodBtn').forEach(b => {
                b.dataset.method === method ? b.classList.add('active') : b.classList.remove('active');
            });
            selectedPaymentMethod = method;
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
            if (cart.length === 0) {
                showToast('warning', '{{ __('pos.cart_empty') }}');
                return;
            }

            if (!selectedPaymentMethod) {
                showToast('warning', '{{ __('pos.select_payment_method') }}');
                return;
            }

            const modalElement = document.getElementById('keypadModal');
            // Use getOrCreateInstance to prevent multiple instances/backdrops
            const modal = window.bootstrap.Modal.getOrCreateInstance(modalElement);
            const isCash = selectedPaymentMethod === 'cash';

            // Toggle sections
            document.getElementById('cashInputSection').style.display = isCash ? 'block' : 'none';
            document.getElementById('nonCashInputSection').style.display = isCash ? 'none' : 'block';
            document.querySelector('.modal-title').textContent = isCash ? '{{ __('pos.cash_amount') }}' : '{{ __('pos.non_cash') }}';

            if (isCash) {
                document.getElementById('keypadDisplay').value = '';
            } else {
                // Reset file input
                resetFileInput();
            }

            modal.show();

            // Setup confirm button
            document.getElementById('keypadConfirm').onclick = () => {
                const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
                const total = subtotal; // Logic for discount if needed

                if (isCash) {
                    const cashAmount = parseFloat(document.getElementById('keypadDisplay').value);
                    if (!cashAmount || cashAmount === 0) {
                        showToast('warning', '{{ __('pos.enter_cash_amount') }}');
                        return;
                    }
                    if (cashAmount < total) {
                        showToast('error', `{{ __('pos.insufficient_cash') }}Rp${formatCurrency(total - cashAmount)}`);
                        return;
                    }
                    modal.hide();
                    processCheckout(cart, subtotal, total, cashAmount, null);
                } else {
                    const inputCamera = document.getElementById('inputCamera');
                    const inputGallery = document.getElementById('inputGallery');
                    let file = null;

                    if (inputCamera.files.length > 0) {
                        file = inputCamera.files[0];
                    } else if (inputGallery.files.length > 0) {
                        file = inputGallery.files[0];
                    }

                    if (!file) {
                        showToast('warning', '{{ __('pos.payment_proof_required') }}');
                        return;
                    }
                    modal.hide();
                    processCheckout(cart, subtotal, total, total, file);
                }
            };
        }

        // File Upload Handlers
        document.getElementById('btnCamera').addEventListener('click', () => {
            document.getElementById('inputCamera').click();
        });

        document.getElementById('btnGallery').addEventListener('click', () => {
            document.getElementById('inputGallery').click();
        });

        document.getElementById('removeImageBtn').addEventListener('click', (e) => {
            e.stopPropagation();
            resetFileInput();
        });

        function handleFileSelect(input, otherInputId) {
            input.addEventListener('change', function (e) {
                if (this.files && this.files[0]) {
                    // Clear the other input
                    document.getElementById(otherInputId).value = '';

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('previewContainer').classList.remove('d-none');
                        document.getElementById('uploadButtons').classList.add('d-none');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }

        handleFileSelect(document.getElementById('inputCamera'), 'inputGallery');
        handleFileSelect(document.getElementById('inputGallery'), 'inputCamera');

        function resetFileInput() {
            document.getElementById('inputCamera').value = '';
            document.getElementById('inputGallery').value = '';
            document.getElementById('previewContainer').classList.add('d-none');
            document.getElementById('uploadButtons').classList.remove('d-none');
            document.getElementById('imagePreview').src = '';
        }

        function processCheckout(items, subtotal, total, cashAmount, paymentProofFile) {
            const checkoutBtn = document.getElementById('checkoutBtn');
            const originalText = checkoutBtn.innerHTML;
            checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> {{ __('pos.processing') }}';
            checkoutBtn.disabled = true;

            const change = cashAmount - total;
            const formData = new FormData();

            // Append items as JSON string because FormData doesn't handle array of objects well directly for Laravel validation
            // However, Laravel validation `items.*.product_id` expects an array.
            // Best approach for FormData array:
            items.forEach((item, index) => {
                formData.append(`items[${index}][product_id]`, item.product_id);
                formData.append(`items[${index}][quantity]`, item.quantity);
                formData.append(`items[${index}][price]`, item.price);
            });

            formData.append('subtotal', subtotal);
            formData.append('total_amount', total);
            formData.append('payment_method', selectedPaymentMethod);
            formData.append('cash_amount', cashAmount);
            formData.append('change_amount', change > 0 ? change : 0);

            if (paymentProofFile) {
                formData.append('payment_proof', paymentProofFile);
            }

            fetch('{{ route("pos.checkout") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                    // Content-Type not set to let browser set boundary
                },
                body: formData
            })
                .then(response => response.json())
                .then(result => {
                    checkoutBtn.innerHTML = originalText;
                    checkoutBtn.disabled = false;

                    if (result.success) {
                        const isCash = selectedPaymentMethod === 'cash';
                        let swalOptions = {
                            icon: 'success',
                            title: '{{ __('pos.transaction_success') }}',
                            confirmButtonText: '{{ __('common.ok') }} & Cetak Struk'
                        };

                        if (selectedPaymentMethod === 'cash') {
                            swalOptions.html = `
                                <div class="text-start mt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ __('pos.cash_received') }}:</span>
                                        <span class="fw-bold">Rp${formatCurrency(cashAmount)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between text-success h5 mb-0">
                                        <span>{{ __('pos.change') }}:</span>
                                        <span class="fw-bold">Rp${formatCurrency(change)}</span>
                                    </div>
                                </div>`;
                        }

                        Swal.fire({
                            ...swalOptions,
                            showCancelButton: true,
                            cancelButtonText: '{{ __('common.close') }}',
                            customClass: {
                                popup: 'artika-swal-popup',
                                title: 'artika-swal-title',
                                confirmButton: 'artika-swal-confirm-btn',
                                cancelButton: 'artika-swal-cancel-btn'
                            },
                            buttonsStyling: false
                        }).then((result_swal) => {
                            if (result_swal.isConfirmed && result.transaction_id) {
                                window.open('{{ url("pos/receipt") }}/' + result.transaction_id, '_blank');
                            }
                        });

                        cart = [];
                        updateCartDisplay();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('common.error') }}',
                            text: result.message || '{{ __('pos.transaction_failed') }}'
                        });
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    checkoutBtn.innerHTML = originalText;
                    checkoutBtn.disabled = false;
                    showToast('error', error.message);
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

                    // Allow Enter to confirm payment
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        document.getElementById('keypadConfirm').click();
                        return;
                    }

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
                showToast('error', '{{ __('pos.product_not_found') }} ' + decodedText);
            }
        }

        function onScanFailure(error) {
            // Suppress error logs for failed reads
        }

        // Handle logout confirmation
        document.getElementById('btnLogout').addEventListener('click', function () {
            confirmAction({
                text: "{{ __('pos.logout_confirmation_message') }}",
                confirmButtonText: "{{ __('pos.logout') }}",
                icon: 'question'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    </script>

    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>

</html>