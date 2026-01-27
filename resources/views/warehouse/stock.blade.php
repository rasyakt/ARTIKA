@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: #6f5849;"><i
                    class="fa-solid fa-box me-2"></i>{{ __('warehouse.stock_management') }}</h2>
            <p class="text-muted mb-0">{{ __('warehouse.manage_product_stock') }}</p>
        </div>

        <!-- Stock Table -->
        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i
                        class="fa-solid fa-clipboard-list me-2"></i>{{ __('warehouse.stock_levels') }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">{{ __('common.product') }}
                                </th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.category') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.current_stock') }}
                                </th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.min_stock') }}
                                </th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.status') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('common.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td class="ps-4">
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
                                            class="fw-bold {{ $stock->quantity < 10 ? 'text-danger' : ($stock->quantity < 20 ? 'text-warning' : 'text-success') }}">
                                            {{ $stock->quantity }} {{ __('warehouse.units') }}
                                        </span>
                                    </td>
                                    <td>{{ $stock->min_stock }}</td>
                                    <td>
                                        @if($stock->quantity < 10)
                                            <span class="badge bg-danger">{{ __('warehouse.critical') }}</span>
                                        @elseif($stock->quantity < 20)
                                            <span class="badge bg-warning">{{ __('warehouse.low') }}</span>
                                        @else
                                            <span class="badge bg-success">{{ __('warehouse.good') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#adjustStockModal" data-stock-id="{{ $stock->id }}"
                                            data-product-id="{{ $stock->product_id }}"
                                            data-product-name="{{ $stock->product->name }}"
                                            data-current-qty="{{ $stock->quantity }}" style="border-radius: 8px;">
                                            <i class="fa-solid fa-gear me-1"></i> {{ __('common.adjust') }}
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($stocks->hasPages())
                <div class="card-footer bg-white border-0 d-flex justify-content-end py-3">
                    {{ $stocks->links('vendor.pagination.no-prevnext') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Adjust Stock Modal -->
    <div class="modal fade" id="adjustStockModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i class="fa-solid fa-gear me-1"></i>
                        {{ __('warehouse.adjust_stock') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.product') }}</label>
                        <input type="text" class="form-control" id="product_name" readonly
                            style="border-radius: 12px; background: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"
                            style="color: #6f5849;">{{ __('warehouse.current_stock') }}</label>
                        <input type="text" class="form-control" id="current_stock" readonly
                            style="border-radius: 12px; background: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"
                            style="color: #6f5849;">{{ __('warehouse.adjustment_type') }}</label>
                        <select class="form-select" id="adjustment_type"
                            style="border-radius: 12px; border: 2px solid #e0cec7;">
                            <option value="add">{{ __('warehouse.add_stock') }}</option>
                            <option value="subtract">{{ __('warehouse.subtract_stock') }}</option>
                            <option value="set">{{ __('warehouse.set_stock') }}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('warehouse.quantity') }}</label>
                        <input type="number" class="form-control" id="adjustment_qty" min="0" step="1"
                            style="border-radius: 12px; border: 2px solid #e0cec7;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold"
                            style="color: #6f5849;">{{ __('warehouse.reason_optional') }}</label>
                        <input type="text" class="form-control" id="adjustment_reason"
                            placeholder="{{ __('warehouse.reason_placeholder') }}"
                            style="border-radius: 12px; border: 2px solid #e0cec7;">
                    </div>
                    <div class="alert alert-info" style="border-radius: 12px;">
                        <strong>{{ __('common.note') }}:</strong> {{ __('warehouse.adjustment_note') }}
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        style="border-radius: 12px;">{{ __('common.cancel') }}</button>
                    <button type="button" class="btn btn-primary" onclick="saveAdjustment()"
                        style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">
                        <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('warehouse.save_adjustment') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStockId = null;
        let currentProductId = null;

        document.addEventListener('DOMContentLoaded', function () {
            var adjustStockModal = document.getElementById('adjustStockModal');
            if (adjustStockModal) {
                adjustStockModal.addEventListener('show.bs.modal', function (event) {
                    // Button that triggered the modal
                    var button = event.relatedTarget;

                    // Extract info from data-* attributes
                    currentStockId = button.getAttribute('data-stock-id');
                    currentProductId = button.getAttribute('data-product-id');
                    var productName = button.getAttribute('data-product-name');
                    var currentQty = button.getAttribute('data-current-qty');

                    // Update the modal's content.
                    document.getElementById('product_name').value = productName;
                    document.getElementById('current_stock').value = currentQty + ' {{ __('warehouse.units') }}';

                    // Reset fields
                    document.getElementById('adjustment_qty').value = '';
                    document.getElementById('adjustment_reason').value = '';
                });
            }
        });

        function saveAdjustment() {
            const type = document.getElementById('adjustment_type').value;
            const quantity = parseInt(document.getElementById('adjustment_qty').value);
            const reason = document.getElementById('adjustment_reason').value;

            if (!quantity || quantity <= 0) {
                showToast('warning', '{{ __('warehouse.enter_valid_quantity') }}');
                return;
            }

            // Disable button to prevent double submission
            const saveBtn = event.target;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fa-solid fa-hourglass-half me-1"></i> {{ __('warehouse.saving') }}';

            fetch('{{ route("warehouse.stock.adjust") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: currentProductId,
                    quantity: quantity,
                    type: type,
                    reason: reason
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('common.success') }}',
                            text: data.message + '\n{{ __('warehouse.new_quantity') }}: ' + data.new_quantity + ' {{ __('warehouse.units') }}',
                            customClass: {
                                popup: 'artika-swal-popup',
                                title: 'artika-swal-title',
                                confirmButton: 'artika-swal-confirm-btn'
                            },
                            buttonsStyling: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('common.error') }}',
                            text: data.message
                        });
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> {{ __('warehouse.save_adjustment') }}';
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('common.error') }}',
                        text: error.message
                    });
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> {{ __('warehouse.save_adjustment') }}';
                });
        }
    </script>
@endsection