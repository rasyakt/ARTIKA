@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers') }}" style="color: #85695a;">{{ __('admin.supplier_management') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $supplier->name }}</li>
                    </ol>
                </nav>
                <h4 class="fw-bold mb-0" style="color: #6f5849;">
                    <i class="fa-solid fa-truck me-2"></i>{{ $supplier->name }}
                </h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.suppliers.pdf', $supplier->id) }}" class="btn btn-outline-brown shadow-sm" style="border-radius: 10px; font-weight: 600;">
                    <i class="fa-solid fa-file-pdf me-1"></i> {{ __('admin.download_pdf') }}
                </a>
                <button class="btn btn-brown shadow-sm" data-bs-toggle="modal" data-bs-target="#addPurchaseModal"
                    style="border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600;">
                    <i class="fa-solid fa-plus me-1"></i> {{ __('admin.add_supply') }}
                </button>
            </div>
        </div>

        <div class="row g-4">
            <!-- Supplier Info Card -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: #6f5849;">{{ __('admin.supplier_info') }}</h5>
                        
                        <div class="mb-3">
                            <label class="small text-muted d-block mb-1">{{ __('admin.phone') }}</label>
                            <div class="fw-semibold text-dark">{{ $supplier->phone ?: '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted d-block mb-1">Email</label>
                            <div class="fw-semibold text-dark">{{ $supplier->email ?: '-' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted d-block mb-1">{{ __('admin.address') }}</label>
                            <div class="fw-semibold text-dark">{{ $supplier->address ?: '-' }}</div>
                        </div>
                        <div class="mb-0">
                            <label class="small text-muted d-block mb-1">{{ __('admin.last_purchase') }}</label>
                            <div class="fw-semibold text-dark">
                                @if($supplier->last_purchase_at)
                                    <span class="badge" style="background: #fdf8f6; color: #85695a; border: 1px solid #f2e8e5;">
                                        {{ $supplier->last_purchase_at->format('d M Y') }}
                                    </span>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats/Metrics Cards -->
            <div class="col-md-8">
                <div class="row g-3 h-100">
                    <div class="col-sm-6">
                        <div class="card shadow-sm ">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="bg-white shadow-sm rounded-circle p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; color: #85695a;">
                                    <i class="fa-solid fa-clipboard-list fa-lg"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">{{ __('admin.total_supplies') }}</div>
                                    <div class="h4 fw-bold mb-0" style="color: #6f5849;">{{ $supplier->purchases()->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card shadow-sm h-100">
                            <div class="card-body p-4 d-flex align-items-center">
                                <div class="bg-white shadow-sm rounded-circle p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; color: #85695a;">
                                    <i class="fa-solid fa-money-bill-transfer fa-lg"></i>
                                </div>
                                <div>
                                    <div class="small text-muted">{{ __('admin.total_transaction_value') }}</div>
                                    <div class="h4 fw-bold mb-0" style="color: #6f5849;">Rp {{ number_format($supplier->purchases()->sum('total_price'), 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase History -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header border-0 py-4 px-4">
                        <h5 class="fw-bold mb-0" style="color: #6f5849;">{{ __('admin.purchase_history') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        @if($purchases->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead style="background-color: #fdf8f6;">
                                        <tr>
                                            <th class="px-4 py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.date') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.product') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.quantity') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.price') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">Total</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.notes') }}</th>
                                            <th class="py-3 border-0 text-muted small" style="font-weight: 500;">{{ __('admin.added_by') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchases as $purchase)
                                            <tr style="border-bottom: 1px solid #f2e8e5;">
                                                <td class="px-4 py-3">
                                                    <div class="small text-muted">{{ $purchase->purchase_date->format('d M Y') }}</div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="fw-bold text-dark">{{ $purchase->product->name }}</div>
                                                    <div class="small text-muted">{{ $purchase->product->barcode }}</div>
                                                </td>
                                                <td class="py-3">
                                                    <span class="badge rounded-pill" style="background: #fdf8f6; color: #85695a; border: 1px solid #f2e8e5;">
                                                        {{ $purchase->quantity }}
                                                    </span>
                                                </td>
                                                <td class="py-3">
                                                    Rp {{ number_format($purchase->purchase_price, 0, ',', '.') }}
                                                </td>
                                                <td class="py-3">
                                                    <div class="fw-bold" style="color: #6f5849;">Rp {{ number_format($purchase->total_price, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="small text-muted">{{ $purchase->notes ?: '-' }}</div>
                                                </td>
                                                <td class="py-3 text-muted small">
                                                    {{ $purchase->user->name }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3" style="font-size: 4rem; opacity: 0.15; color: #6f5849;">
                                    <i class="fa-solid fa-receipt"></i>
                                </div>
                                <h5 class="text-muted">{{ __('admin.no_purchase_history') }}</h5>
                            </div>
                        @endif
                    </div>
                    @if($purchases->hasPages())
                        <div class="card-footer border-0 d-flex justify-content-end py-3 px-4">
                            {{ $purchases->links('vendor.pagination.no-prevnext') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sales Performance Section -->
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0" style="color: #6f5849;">{{ __('admin.sales_performance') }}</h5>
                        <div class="badge" style="background: #fdf8f6; color: #85695a; border: 1px solid #f2e8e5;">
                            {{ __('admin.product_sales_summary') }}
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($salesPerformance->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead style="background-color: #fdf8f6;">
                                        <tr>
                                            <th class="px-4 py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.product') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('common.barcode') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.total_sold') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.revenue') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.remaining_stock') }}</th>
                                            <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.inventory_value') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($salesPerformance as $sale)
                                            <tr style="border-bottom: 1px solid #f2e8e5;">
                                                <td class="px-4 py-3">
                                                    <div class="fw-bold text-dark">{{ $sale->product->name }}</div>
                                                </td>
                                                <td class="py-3">
                                                    <span class="text-muted">{{ $sale->product->barcode }}</span>
                                                </td>
                                                <td class="py-3">
                                                    <span class="badge rounded-pill px-3" style="background-color: #e7f5ef; color: #0d6832; border: 1px solid #d1e7dd;">
                                                        {{ number_format($sale->total_sold, 0, ',', '.') }} {{ __('common.units') }}
                                                    </span>
                                                </td>
                                                <td class="py-3">
                                                    <div class="fw-bold" style="color: #6f5849;">Rp {{ number_format($sale->total_revenue, 0, ',', '.') }}</div>
                                                </td>
                                                <td class="py-3">
                                                    @php
                                                        $stockQty = $sale->product->stock->quantity ?? 0;
                                                        $minStock = $sale->product->stock->min_stock ?? 0;
                                                    @endphp
                                                    <span class="badge rounded-pill px-3" 
                                                        style="{{ $stockQty > $minStock 
                                                            ? 'background-color: #f8f9fa; color: #343a40; border: 1px solid #dee2e6;' 
                                                            : 'background-color: #fff5f5; color: #e03131; border: 1px solid #ffa8a8;' }}">
                                                        {{ number_format($stockQty, 0, ',', '.') }} {{ __('common.units') }}
                                                    </span>
                                                </td>
                                                <td class="py-3">
                                                    <div class="fw-bold text-muted">Rp {{ number_format(($sale->product->stock->quantity ?? 0) * ($sale->product->cost_price ?? 0), 0, ',', '.') }}</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3" style="font-size: 4rem; opacity: 0.15; color: #6f5849;">
                                    <i class="fa-solid fa-chart-line"></i>
                                </div>
                                <h5 class="text-muted">{{ __('admin.no_sales_data') }}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Purchase Modal -->
    <div class="modal fade" id="addPurchaseModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;">
                        <i class="fa-solid fa-circle-plus me-2"></i>{{ __('admin.add_supply') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.supplier-purchases.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                    <div class="modal-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.date') }}</label>
                                <input type="date" name="purchase_date" class="form-control custom-input" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless align-middle" id="items-table">
                                <thead class="text-muted small text-uppercase">
                                    <tr>
                                        <th style="width: 40%;">{{ __('admin.product') }}</th>
                                        <th style="width: 15%;">{{ __('admin.quantity') }}</th>
                                        <th style="width: 20%;">{{ __('admin.purchase_price') }}</th>
                                        <th>{{ __('admin.notes') }}</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="items-container">
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][product_id]" class="form-select custom-input select-product" required>
                                                <option value="" disabled selected>{{ __('admin.select_product') }}</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->barcode }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control custom-input input-quantity" min="1" value="1" required>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][purchase_price]" class="form-control custom-input input-price" min="0" step="0.01" required>
                                        </td>
                                        <td>
                                            <input type="text" name="items[0][notes]" class="form-control custom-input" placeholder="{{ __('admin.notes_placeholder') }}">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-outline-brown btn-sm mt-2" id="add-item-btn" style="border-radius: 8px;">
                            <i class="fa-solid fa-plus me-1"></i> {{ __('admin.add_product') }}
                        </button>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                            <div class="text-end">
                                <div class="text-muted small text-uppercase">{{ __('admin.total_transaction_value') }}</div>
                                <div class="h3 fw-bold mb-0" style="color: #6f5849;">Rp <span id="grand-total">0</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4 d-flex gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-brown px-4 shadow-sm" style="border-radius: 10px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let rowCount = 1;
            const container = document.getElementById('items-container');
            const addBtn = document.getElementById('add-item-btn');
            const grandTotalEl = document.getElementById('grand-total');

            function calculateGrandTotal() {
                let total = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const qty = parseFloat(row.querySelector('.input-quantity').value) || 0;
                    const price = parseFloat(row.querySelector('.input-price').value) || 0;
                    total += qty * price;
                });
                grandTotalEl.textContent = new Intl.NumberFormat('id-ID').format(total);
            }

            addBtn.addEventListener('click', function() {
                const newRow = document.createElement('tr');
                newRow.className = 'item-row';
                newRow.innerHTML = `
                    <td>
                        <select name="items[${rowCount}][product_id]" class="form-select custom-input select-product" required>
                            <option value="" disabled selected>{{ __('admin.select_product') }}</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->barcode }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[${rowCount}][quantity]" class="form-control custom-input input-quantity" min="1" value="1" required>
                    </td>
                    <td>
                        <input type="number" name="items[${rowCount}][purchase_price]" class="form-control custom-input input-price" min="0" step="0.01" required>
                    </td>
                    <td>
                        <input type="text" name="items[${rowCount}][notes]" class="form-control custom-input" placeholder="{{ __('admin.notes_placeholder') }}">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-link text-danger p-0 delete-row-btn">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                `;
                container.appendChild(newRow);
                rowCount++;

                // Add event listeners to new row
                newRow.querySelector('.input-quantity').addEventListener('input', calculateGrandTotal);
                newRow.querySelector('.input-price').addEventListener('input', calculateGrandTotal);
                newRow.querySelector('.delete-row-btn').addEventListener('click', function() {
                    newRow.remove();
                    calculateGrandTotal();
                });
            });

            // Initial event listeners
            document.querySelector('.input-quantity').addEventListener('input', calculateGrandTotal);
            document.querySelector('.input-price').addEventListener('input', calculateGrandTotal);

            // Re-calculate if validation error occurs and old data is populated (though not implemented here yet)
            calculateGrandTotal();
        });
    </script>

    <style>
        .btn-brown {
            background: #6f5849;
            color: white;
            border: none;
        }

        .btn-brown:hover {
            color: white;
            opacity: 0.9;
        }

        .btn-outline-brown {
            border: 2px solid #85695a;
            color: #85695a;
        }

        .btn-outline-brown:hover {
            background: #85695a;
            color: white;
        }

        .custom-input {
            border-radius: 12px;
            border: 2px solid #f2e8e5;
            padding: 0.6rem 1rem;
        }

        .custom-input:focus {
            border-color: #d4c4bb;
            box-shadow: none;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "\f105";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            font-size: 0.75rem;
            color: #d4c4bb;
        }

        #items-table thead th {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
    </style>
@endsection
