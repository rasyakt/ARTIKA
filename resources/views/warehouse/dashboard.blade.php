@extends('layouts.app')

@section('content')
    <style>
        .stats-card {
            border-radius: 16px;
            border: none;
            overflow: hidden;
            transition: all 0.3s;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(133, 105, 90, 0.15) !important;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: #6f5849;"><i class="fa-solid fa-box me-2"></i>{{ __('warehouse.dashboard') }}</h2>
            <p class="text-muted mb-0">{{ __('warehouse.stock_monitoring') }}</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Products -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('warehouse.total_products') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">{{ $totalProducts }}</h3>
                                <small class="text-muted">{{ __('warehouse.in_catalog') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Value -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('warehouse.stock_value') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</h3>
                                <small class="text-muted">{{ __('warehouse.total_inventory') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 text-muted text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">{{ __('warehouse.low_stock') }}</p>
                                <h3 class="fw-bold mb-0" style="color: #4b382f;">{{ $lowStockItems->count() }}</h3>
                                <small class="text-muted">{{ __('warehouse.items_need_restock') }}</small>
                            </div>
                            <div class="icon-box-premium bg-brown-soft">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Low Stock & Expiry Alerts -->
            <div class="col-md-8">
                <!-- Expired Items -->
                @if($expiredItems->count() > 0)
                <div class="card shadow-sm border-danger mb-4" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-danger text-white"
                        style="border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-calendar-xmark me-2"></i>{{ __('warehouse.expired_items') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fff5f5;">
                                    <tr>
                                        <th class="border-0 fw-semibold text-danger">{{ __('common.product') }}</th>
                                        <th class="border-0 fw-semibold text-danger">{{ __('common.expiry_date') }}</th>
                                        <th class="border-0 fw-semibold text-danger">{{ __('common.stock') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiredItems as $stock)
                                        <tr class="table-danger">
                                            <td>
                                                <div class="fw-bold">{{ $stock->product->name }}</div>
                                                <small class="text-muted">{{ $stock->product->barcode }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $stock->expired_at->format('d M Y') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">{{ $stock->quantity }} {{ __('warehouse.units') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Expiring Soon Items -->
                @if($expiringSoonItems->count() > 0)
                <div class="card shadow-sm border-warning mb-4" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-warning"
                        style="border-radius: 16px 16px 0 0; color: #4b382f;">
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-clock me-2"></i>{{ __('warehouse.expiring_soon_alerts') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fffbeb;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.product') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.expiry_date') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.stock') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($expiringSoonItems as $stock)
                                        <tr>
                                            <td>
                                                <div class="fw-bold" style="color: #6f5849;">{{ $stock->product->name }}</div>
                                                <small class="text-muted">{{ $stock->product->barcode }}</small>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-warning">{{ $stock->expired_at->format('d M Y') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark">{{ $stock->quantity }} {{ __('warehouse.units') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ __('warehouse.low_stock_alerts') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.product') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.category') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.stock') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockItems as $stock)
                                        <tr>
                                            <td>
                                                <div class="fw-bold" style="color: #6f5849;">{{ $stock->product->name }}</div>
                                                <small class="text-muted">{{ $stock->product->barcode }}</small>
                                            </td>
                                            <td>
                                                <span class="badge" style="background: #e0cec7; color: #6f5849;">
                                                    {{ $stock->product->category->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-bold {{ $stock->quantity < 10 ? 'text-danger' : 'text-warning' }}">
                                                    {{ $stock->quantity }} {{ __('warehouse.units') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $stock->quantity < 10 ? 'bg-danger' : 'bg-warning' }}">
                                                    {{ $stock->quantity < 10 ? __('warehouse.critical') : __('warehouse.low') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-circle-check"></i></div>
                                                <p class="text-muted mb-0">{{ __('warehouse.all_stock_healthy') }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock by Category -->
            <div class="col-md-4">
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-folder me-2"></i>{{ __('warehouse.stock_by_category') }}</h5>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        @foreach($stockByCategory as $category)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold" style="color: #6f5849;">{{ $category['name'] }}</div>
                                    <span class="badge" style="background: #e0cec7; color: #6f5849;">
                                        {{ $category['products_count'] }} {{ __('common.products') }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ __('warehouse.total_stock') }}:</small>
                                    <span class="fw-bold" style="color: #c17a5c;">{{ $category['total_stock'] }} {{ __('warehouse.units') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-clipboard-list me-2"></i>{{ __('warehouse.recent_products') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.product') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.category') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.price') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.total_stock') }}</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.added') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentProducts as $product)
                                        <tr>
                                            <td>
                                                <div class="fw-bold" style="color: #6f5849;">{{ $product->name }}</div>
                                                <small class="text-muted">{{ $product->barcode }}</small>
                                            </td>
                                            <td>
                                                <span class="badge" style="background: #e0cec7; color: #6f5849;">
                                                    {{ $product->category->name }}
                                                </span>
                                            </td>
                                            <td class="fw-bold" style="color: #c17a5c;">Rp
                                                {{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td>
                                                @php
                                                    $totalStock = $product->stocks->sum('quantity');
                                                @endphp
                                                <span
                                                    class="badge {{ $totalStock > 50 ? 'bg-success' : ($totalStock > 20 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $totalStock }} {{ __('warehouse.units') }}
                                                </span>
                                            </td>
                                            <td class="text-muted">{{ $product->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection