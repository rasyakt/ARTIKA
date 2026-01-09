@extends('layouts.app')

@section('content')
    <style>
        .product-table-card {
            border-radius: 16px;
            border: none;
            overflow: hidden;
        }

        .table-actions .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;">📦 Product Management</h2>
                <p class="text-muted mb-0">Manage your product catalog</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm"
                style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600;">
                <span style="font-size: 1.25rem;">+</span> Add New Product
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" style="border-radius: 12px; border: none;"
                role="alert">
                <strong>✅ Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Products Table -->
        <div class="card product-table-card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="ps-4 border-0 fw-semibold" style="color: #6f5849;">Product</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Category</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Barcode</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Cost Price</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Sell Price</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Margin</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Stock</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3"
                                                    style="width: 45px; height: 45px; background: linear-gradient(135deg, #f2e8e5 0%, #e0cec7 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                                    📦
                                                </div>
                                                <div>
                                                    <div class="fw-bold" style="color: #6f5849;">{{ $product->name }}</div>
                                                    <small class="text-muted">ID: {{ $product->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge"
                                                style="background: #e0cec7; color: #6f5849; padding: 0.5rem 0.75rem; border-radius: 8px; font-weight: 600;">
                                                {{ $product->category->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <code
                                                style="background: #fdf8f6; padding: 0.25rem 0.5rem; border-radius: 6px; color: #85695a; font-weight: 600;">{{ $product->barcode }}</code>
                                        </td>
                                        <td class="text-muted">Rp {{ number_format($product->cost_price, 0, ',', '.') }}</td>
                                        <td class="fw-bold" style="color: #c17a5c;">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td>
                                @php
                                    $margin = $product->cost_price > 0 ? (($product->price - $product->cost_price) / $product->cost_price) * 100 : 0;
                                @endphp
                                            <span
                                                class="badge {{ $margin > 30 ? 'bg-success' : ($margin > 15 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ number_format($margin, 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $totalStock = $product->stocks->sum('quantity');
                                            @endphp
                                            <span
                                                class="badge {{ $totalStock > 50 ? 'bg-success' : ($totalStock > 20 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $totalStock }} units
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown text-center">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    style="border-radius: 8px; border: 1px solid #e0cec7; font-size: 1.2rem; line-height: 1; padding: 0.25rem 0.5rem;">
                                                    ⋮
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end"
                                                    style="border-radius: 12px; border: 1px solid #e0cec7; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.products.edit', $product->id) }}"
                                                            style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                            ✏️ Edit Product
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.products.delete', $product->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                                🗑️ Delete Product
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div style="font-size: 4rem; opacity: 0.2;">📦</div>
                                        <p class="text-muted mb-0">No products found.</p>
                                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mt-3">Add Your
                                            First Product</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($products->hasPages())
                <div class="card-footer bg-white border-0" style="border-radius: 0 0 16px 16px;">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection