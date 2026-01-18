<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - ARTIKA POS</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #85695a;
            --primary-dark: #6f5849;
            --primary-light: #a68b7c;
            --brown-50: #fdfaf8;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-700: #495057;
            --gray-800: #343a40;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Inter', sans-serif;
            color: var(--gray-800);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            height: 60px;
            box-shadow: 0 4px 12px rgba(133, 105, 90, 0.15);
            z-index: 1050;
            position: relative;
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            background: white;
        }

        .card-revenue {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            position: relative;
            z-index: 1;
        }

        .card-revenue::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: -1;
        }

        .form-control {
            border-radius: 10px;
            border: 1px solid var(--gray-200);
            padding: 0.6rem 0.85rem;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.2rem rgba(133, 105, 90, 0.1);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .table thead th {
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: var(--gray-700);
            background: var(--brown-50);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 1.5rem;
        }

        .table tbody td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-100);
            font-size: 0.9rem;
        }

        /* Transaction Details Card */
        .transaction-details {
            background: #fafbfc;
            border-radius: 12px;
            margin: 1.5rem;
            padding: 1.5rem;
            border: 1px solid var(--gray-200);
        }

        /* Mobile specific styles */
        @media (max-width: 768px) {
            .transaction-table-desktop {
                display: none;
            }

            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .card-summary {
                margin-bottom: 1rem;
            }
        }

        /* Responsive Mobile Cards */
        .transaction-card-mobile {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.02);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
        }

        .t-card-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .t-card-invoice {
            font-weight: 700;
            color: var(--primary-dark);
            font-size: 1rem;
        }

        .t-card-date {
            font-size: 0.75rem;
            color: var(--gray-700);
        }

        .t-card-price {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--gray-800);
        }

        .payment-badge {
            font-size: 0.65rem;
            padding: 0.3rem 0.6rem;
            border-radius: 50px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .badge-cash {
            background: #e6fcf5;
            color: #0ca678;
        }

        .badge-non-cash {
            background: #e7f5ff;
            color: #228be6;
        }

        .btn-action-mobile {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            background: var(--gray-100);
            color: var(--gray-700);
            border: none;
        }

        .t-card-body {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 0.5rem;
        }

        .transaction-list-mobile {
            display: none;
        }

        @media (max-width: 768px) {
            .transaction-list-mobile {
                display: block;
            }
        }

        /* Utility Classes */
        .fw-500 {
            font-weight: 500;
        }

        .fw-600 {
            font-weight: 600;
        }

        .fw-700 {
            font-weight: 700;
        }

        .fw-800 {
            font-weight: 800;
        }

        .rounded-16 {
            border-radius: 16px;
        }

        .profile-trigger {
            transition: all 0.2s;
            border-radius: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .profile-trigger:hover .profile-avatar {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
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
            transition: all 0.2s;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

        .dropdown-item {
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: var(--brown-50);
            color: var(--primary-dark);
        }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <nav class="navbar navbar-dark mb-4">
        <div class="container-fluid px-4">
            <span class="navbar-brand fw-bold mb-0">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>History
            </span>
            <div class="d-flex align-items-center">
                <a href="{{ route('pos.index') }}" class="btn me-3 d-none d-sm-flex align-items-center"
                    style="border-radius: 12px; padding: 0.5rem 1rem; background: rgba(255, 255, 255, 0.2); border: none; color: white; font-weight: 600; font-size: 0.85rem;">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back to POS
                </a>

                <div class="dropdown">
                    <button class="btn p-0 border-0 profile-trigger" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
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
    </nav>

    <div class="container">
        <div class="row g-4 mb-4">
            <!-- Summary Stats Mini Cards -->
            <div class="col-md-4">
                <div class="card card-revenue text-white h-100 p-4 border-0">
                    <div class="d-flex flex-column h-100 justify-content-center">
                        <span class="text-uppercase small fw-800 opacity-75 mb-1"
                            style="letter-spacing: 0.05em">{{ __('pos.total_revenue') }}</span>
                        <h2 class="fw-800 mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h2>
                        <div class="mt-3 small opacity-75 d-flex align-items-center">
                            <i class="fa-solid fa-calendar-check me-2"></i> {{ __('pos.filtered_period') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Filter -->
            <div class="col-md-8">
                <div class="card h-100 p-2">
                    <div class="card-body">
                        <form action="{{ route('pos.history') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-6 col-md-4">
                                <label class="form-label text-muted small fw-700">{{ __('admin.from_date') }}</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label text-muted small fw-700">{{ __('admin.to_date') }}</label>
                                <input type="date" name="end_date" class="form-control"
                                    value="{{ request('end_date') }}">
                            </div>
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="fa-solid fa-filter me-2"></i>{{ __('pos.filter_report') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sold Items Summary Slider/List -->
        @if(isset($soldItems) && $soldItems->isNotEmpty())
            <div class="card mb-4 border-0">
                <div class="card-header bg-white border-bottom py-3 px-4">
                    <h6 class="mb-0 fw-700 text-primary-dark">
                        <i class="fa-solid fa-fire me-2 text-warning"></i>Top Sold Items
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 250px;">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Item Name</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end pe-4">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach($soldItems as $item)
                                    <tr>
                                        <td class="ps-4 text-dark fw-500">{{ $item->name }}</td>
                                        <td class="text-center fw-700 text-primary">{{ $item->total_qty }}</td>
                                        <td class="text-end pe-4 fw-600">Rp {{ number_format($item->total_sales, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Transaction Content -->
        <div class="transaction-content">
            <!-- DESKTOP TABLE -->
            <div class="card border-0 transaction-table-desktop">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-4">Invoice</th>
                                <th>Date & Time</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Method</th>
                                <th class="text-center">Items</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="ps-4 fw-800 text-primary-dark">{{ $transaction->invoice_no }}</td>
                                    <td class="text-muted">{{ $transaction->created_at->format('d M Y, H:i') }}</td>
                                    <td class="text-end fw-800">Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="payment-badge {{ strtolower($transaction->payment_method) === 'cash' ? 'badge-cash' : 'badge-non-cash' }}">
                                            {{ $transaction->payment_method }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge rounded-pill bg-light text-dark border">{{ $transaction->items->count() }}</span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button class="btn btn-sm btn-link text-primary p-1" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#details-{{ $transaction->id }}">
                                            <i class="fa-solid fa-chevron-down"></i>
                                        </button>
                                        <button class="btn btn-sm btn-link text-secondary p-1 ms-2"
                                            onclick="openReceipt('{{ route('pos.receipt', $transaction->id) }}')">
                                            <i class="fa-solid fa-print"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="p-0 border-0">
                                        <div class="collapse" id="details-{{ $transaction->id }}">
                                            <div class="transaction-details">
                                                <div class="row mb-4">
                                                    <div class="col-md-6">
                                                        <h6 class="fw-800 mb-2">Order Details</h6>
                                                        <p class="mb-1 small text-muted">Customer: <span
                                                                class="text-dark fw-600">Walk-in Customer</span></p>
                                                        <p class="mb-1 small text-muted">Cashier: <span
                                                                class="text-dark fw-600">{{ $transaction->user->name ?? 'System' }}</span>
                                                        </p>
                                                        <p class="mb-0 small text-muted">Status: <span
                                                                class="badge bg-success">Completed</span></p>
                                                    </div>
                                                    <div class="col-md-6 text-md-end">
                                                        <h6 class="fw-800 mb-2">Payment Summary</h6>
                                                        <p class="mb-1 small text-muted">Subtotal: <span
                                                                class="text-dark">Rp
                                                                {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                                                        </p>
                                                        <p class="mb-0 small text-muted">Total: <span
                                                                class="text-primary fw-800 fs-5">Rp
                                                                {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <table class="table table-sm border-0">
                                                    <thead class="bg-light">
                                                        <tr>
                                                            <th class="ps-3 border-0">Product</th>
                                                            <th class="text-center border-0">Qty</th>
                                                            <th class="text-end pe-3 border-0">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="border-top-0">
                                                        @foreach($transaction->items as $item)
                                                            <tr>
                                                                <td class="ps-3 border-0">{{ $item->product->name }}</td>
                                                                <td class="text-center border-0 fw-600">{{ $item->quantity }}
                                                                </td>
                                                                <td class="text-end pe-3 border-0 fw-700">Rp
                                                                    {{ number_format($item->subtotal, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png"
                                            alt="Empty" style="width: 150px; opacity: 0.5;">
                                        <p class="mt-3 text-muted">No transactions found for this period.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MOBILE CARDS -->
            <div class="transaction-list-mobile">
                @forelse($transactions as $transaction)
                    <div class="transaction-card-mobile" data-bs-toggle="collapse"
                        data-bs-target="#m-details-{{ $transaction->id }}">
                        <div class="t-card-header">
                            <div>
                                <div class="t-card-invoice">{{ $transaction->invoice_no }}</div>
                                <div class="t-card-date">{{ $transaction->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            <span
                                class="payment-badge {{ strtolower($transaction->payment_method) === 'cash' ? 'badge-cash' : 'badge-non-cash' }}">
                                {{ $transaction->payment_method }}
                            </span>
                        </div>
                        <div class="t-card-body">
                            <div>
                                <div class="text-muted small">Total Amount</div>
                                <div class="t-card-price">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button class="btn-action-mobile"
                                    onclick="event.stopPropagation(); openReceipt('{{ route('pos.receipt', $transaction->id) }}')">
                                    <i class="fa-solid fa-print"></i>
                                </button>
                                <div class="btn-action-mobile">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile Context Detail -->
                        <div class="collapse mt-3 pt-3 border-top" id="m-details-{{ $transaction->id }}">
                            <div class="small mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Cashier:</span>
                                    <span class="fw-600">{{ $transaction->user->name ?? 'System' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Items ({{ $transaction->items->count() }})</span>
                                    <span class="fw-700">Subtotal: Rp
                                        {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <ul class="list-unstyled mb-0">
                                    @foreach($transaction->items as $item)
                                        <li class="d-flex justify-content-between mb-1">
                                            <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                                            <span class="text-muted">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 bg-white rounded-16 shadow-sm">
                        <i class="fa-solid fa-receipt fa-3x mb-3 text-muted opacity-25"></i>
                        <p class="text-muted">No transactions found.</p>
                    </div>
                @endforelse
            </div>

            @if($transactions->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $transactions->links('vendor.pagination.no-prevnext') }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function openReceipt(url) {
            const width = 400;
            const height = 600;
            const left = (window.screen.width / 2) - (width / 2);
            const top = (window.screen.height / 2) - (height / 2);

            window.open(url, 'Receipt', `width=${width},height=${height},top=${top},left=${left},scrollbars=yes`);
        }

        // Logout confirmation handler
        document.getElementById('btnLogout')?.addEventListener('click', function () {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: "Apakah Anda yakin ingin keluar dari sistem?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#6f5849',
                cancelButtonColor: '#f1f1f1',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'btn btn-primary px-4 py-2 me-3',
                    cancelButton: 'btn btn-light px-4 py-2'
                }
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