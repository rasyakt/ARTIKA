@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Header -->
                <div class="mb-4">
                    <a href="{{ route('admin.products') }}" class="text-decoration-none text-muted mb-2 d-inline-block">
                        <i class="fa-solid fa-arrow-left me-1"></i> {{ __('common.back_to_list') }}
                    </a>
                    <h2 class="fw-bold mb-1" style="color: #6f5849;"><i class="fa-solid fa-plus me-2"></i>{{ __('admin.add_product') }}</h2>
                    <p class="text-muted mb-0">{{ __('common.fill_details') }}</p>
                </div>

                <!-- Form Card -->
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.products.store') }}" method="POST">
                            @csrf

                            <!-- Product Name -->
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.product_name') }}
                                    *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="{{ __('common.name_placeholder') }}" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Barcode -->
                            <div class="mb-4">
                                <label for="barcode" class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.barcode') }}
                                    *</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode"
                                        name="barcode" value="{{ old('barcode') }}" placeholder="{{ __('common.barcode_placeholder') }}" required
                                        style="border-radius: {{ App\Models\Setting::get('admin_enable_camera', true) ? '12px 0 0 12px' : '12px' }}; border: 2px solid #e0cec7; padding: 0.75rem 1rem; {{ App\Models\Setting::get('admin_enable_camera', true) ? 'border-right: none;' : '' }}">
                                    @if(App\Models\Setting::get('admin_enable_camera', true))
                                        <button class="btn btn-outline-secondary" type="button" id="btnScanner"
                                            style="border: 2px solid #e0cec7; border-left: none; border-radius: 0 12px 12px 0; background: #fdf8f6; color: #6f5849;">
                                            <i class="fa-solid fa-camera"></i>
                                        </button>
                                    @endif
                                </div>
                                @error('barcode')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">{{ __('common.barcode_help') }}</small>
                            </div>

                            <!-- Category -->
                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.category') }}
                                    *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                    <option value="">{{ __('common.select_category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <label for="cost_price" class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.cost_price') }} (Rp) *</label>
                                    <input type="number" class="form-control @error('cost_price') is-invalid @enderror"
                                        id="cost_price" name="cost_price" value="{{ old('cost_price') }}"
                                        placeholder="{{ __('common.cost_price_placeholder') }}" min="0" step="1" required
                                        style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                    @error('cost_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">{{ __('common.cost_price_help') }}</small>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="price" class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.sell_price') }}
                                        (Rp) *</label>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror"
                                        id="price" name="price" value="{{ old('price') }}" placeholder="{{ __('common.sell_price_placeholder') }}" min="0"
                                        step="1" required
                                        style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">{{ __('common.sell_price_help') }}</small>
                                </div>
                            </div>

                            <!-- Margin Preview -->
                            <div class="mb-4 p-3"
                                style="background: #fdf8f6; border-radius: 12px; border: 2px dashed #e0cec7;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold" style="color: #6f5849;">{{ __('common.profit_margin') }}:</span>
                                    <span class="fw-bold fs-5" style="color: #16a34a;" id="marginPreview">0%</span>
                                </div>
                                <small class="text-muted">{{ __('common.margin_calc_help') }}</small>
                            </div>

                            <!-- Description (Optional) -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.description') }}
                                    ({{ __('common.optional') }})</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="{{ __('common.description_placeholder') }}"
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">{{ old('description') }}</textarea>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-3 justify-content-end pt-3 border-top">
                                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary"
                                    style="border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600;">
                                    {{ __('common.cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary shadow-sm"
                                    style="background: #6f5849; border: none; border-radius: 12px; padding: 0.75rem 2rem; font-weight: 600;">
                                    <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('common.save') }} {{ __('common.product') }}
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

        // Scanner Integration
        const scannerBtn = document.getElementById('btnScanner');
        if (scannerBtn) {
            scannerBtn.addEventListener('click', function() {
                startArtikaScanner(function(barcode) {
                    document.getElementById('barcode').value = barcode;
                    // Optional: Provide visual feedback or sound
                    ArtikaToast.fire({
                        icon: 'success',
                        title: '{{ __("pos.barcode_scanned") ?? "Barcode Scanned!" }}'
                    });
                });
            });
        }
    </script>
@endsection