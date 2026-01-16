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
            <h2 class="fw-bold mb-1" style="color: #6f5849;"><i class="fa-solid fa-box me-2"></i>Warehouse Dashboard</h2>
            <p class="text-muted mb-0">Stock monitoring and management</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <!-- Total Products -->
            <div class="col-md-4">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Total Products</p>
                                <h3 class="fw-bold mb-0">{{ $totalProducts }}</h3>
                                <small class="opacity-75">In catalog</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-box"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Value -->
            <div class="col-md-4">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Stock Value</p>
                                <h3 class="fw-bold mb-0">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</h3>
                                <small class="opacity-75">Total inventory</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-money-bill-wave"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="col-md-4">
                <div class="card stats-card shadow-sm"
                    style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                    <div class="card-body text-white">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-2 opacity-75 text-uppercase"
                                    style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Low Stock</p>
                                <h3 class="fw-bold mb-0">{{ $lowStockItems->count() }}</h3>
                                <small class="opacity-75">Items need restock</small>
                            </div>
                            <div class="stats-icon" style="background: rgba(255, 255, 255, 0.2);">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Low Stock Alerts Table -->
            <div class="col-md-8">
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-header bg-white"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-triangle-exclamation me-2"></i>Low Stock Alerts</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Product</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Category</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Stock</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Status</th>
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
                                                    {{ $stock->quantity }} units
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $stock->quantity < 10 ? 'bg-danger' : 'bg-warning' }}">
                                                    {{ $stock->quantity < 10 ? 'Critical' : 'Low' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div style="font-size: 3rem; opacity: 0.3;"><i class="fa-solid fa-circle-check"></i></div>
                                                <p class="text-muted mb-0">All stock levels are healthy!</p>
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
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-folder me-2"></i>Stock by Category</h5>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        @foreach($stockByCategory as $category)
                            <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold" style="color: #6f5849;">{{ $category['name'] }}</div>
                                    <span class="badge" style="background: #e0cec7; color: #6f5849;">
                                        {{ $category['products_count'] }} products
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">Total Stock:</small>
                                    <span class="fw-bold" style="color: #c17a5c;">{{ $category['total_stock'] }} units</span>
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
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i class="fa-solid fa-clipboard-list me-2"></i>Recent Products</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #fdf8f6;">
                                    <tr>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Product</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Category</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Price</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Total Stock</th>
                                        <th class="border-0 fw-semibold" style="color: #6f5849;">Added</th>
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
                                                    {{ $totalStock }} units
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