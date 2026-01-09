@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="mb-4">
                    <a href="{{ route('admin.products') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                        ← Back to Products
                    </a>
                    <h2 class="fw-bold mb-1" style="color: #6f5849;">✏️ Edit Product</h2>
                    <p class="text-muted mb-0">Update product details</p>
                </div>

                <!-- Form Card -->
                <div class="card shadow-sm" style="border-radius: 16px; border: none;">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Product Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold" style="color: #6f5849;">Product Name
                                    *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $product->name) }}" placeholder="e.g., Chitato Lite"
                                    required style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Barcode -->
                            <div class="mb-4">
                                <label for="barcode" class="form-label fw-semibold" style="color: #6f5849;">Barcode
                                    *</label>
                                <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode"
                                    name="barcode" value="{{ old('barcode', $product->barcode) }}"
                                    placeholder="e.g., 899999911111" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                @error('barcode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Unique barcode for this product</small>
                            </div>

                            <!-- Category -->
                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-semibold" style="color: #6f5849;">Category
                                    *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prices Row -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="cost_price" class="form-label fw-semibold" style="color: #6f5849;">Cost
                                        Price (Rp) *</label>
                                    <input type="number" class="form-control @error('cost_price') is-invalid @enderror"
                                        id="cost_price" name="cost_price"
                                        value="{{ old('cost_price', $product->cost_price) }}" placeholder="e.g., 12000"
                                        min="0" step="100" required
                                        style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                    @error('cost_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Purchase price from supplier</small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="price" class="form-label fw-semibold" style="color: #6f5849;">Selling Price
                                        (Rp) *</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price', $product->price) }}"
                                        placeholder="e.g., 15000" min="0" step="100" required
                                        style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Price for customers</small>
                                </div>
                            </div>

                            <!-- Margin Preview -->
                            <div class="mb-4 p-3"
                                style="background: #fdf8f6; border-radius: 12px; border: 2px dashed #e0cec7;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold" style="color: #6f5849;">Profit Margin:</span>
                                    <span class="fw-bold fs-5" style="color: #16a34a;" id="marginPreview">
                                        {{ number_format($product->profit_margin, 1) }}%
                                    </span>
                                </div>
                                <small class="text-muted">Calculated automatically based on cost and selling price</small>
                            </div>

                            <!-- Description (Optional) -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold" style="color: #6f5849;">Description
                                    (Optional)</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Product description..."
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary"
                                    style="border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600;">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary shadow-sm"
                                    style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px; padding: 0.75rem 2rem; font-weight: 600;">
                                    💾 Update Product
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Calculate margin preview
        const costInput = document.getElementById('cost_price');
        const priceInput = document.getElementById('price');
        const marginPreview = document.getElementById('marginPreview');

        function updateMargin() {
            const cost = parseFloat(costInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;

            if (cost > 0) {
                const margin = ((price - cost) / cost) * 100;
                marginPreview.textContent = margin.toFixed(1) + '%';

                // Color based on margin
                if (margin > 30) {
                    marginPreview.style.color = '#16a34a'; // Green
                } else if (margin > 15) {
                    marginPreview.style.color = '#ea580c'; // Orange
                } else {
                    marginPreview.style.color = '#dc2626'; // Red
                }
            } else {
                marginPreview.textContent = '0%';
                marginPreview.style.color = '#78716c'; // Gray
            }
        }

        costInput.addEventListener('input', updateMargin);
        priceInput.addEventListener('input', updateMargin);

        // Initial calculation
        updateMargin();
    </script>
@endsection