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
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.batch_no') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.expired_at') }}
                                </th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('warehouse.current_stock') }}
                                </th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('common.status') }}
                                </th>
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
                                        <span class="badge ms-1"
                                            style="background: #e0cec7; color: #6f5849; font-size: 0.7rem;">
                                            {{ $stock->product->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <code class="text-muted">{{ $stock->batch_no ?? '-' }}</code>
                                    </td>
                                    <td>
                                        @if($stock->expired_at)
                                            <div class="d-flex flex-column">
                                                <span
                                                    class="fw-semibold {{ $stock->expired_at->isPast() ? 'text-danger' : ($stock->expired_at->diffInDays(now()->startOfDay()) < 30 ? 'text-warning' : '') }}">
                                                    {{ $stock->expired_at->format('d M Y') }}
                                                </span>
                                                @if($stock->expired_at->isPast())
                                                    <small class="text-danger fw-bold"
                                                        style="font-size: 0.7rem;">{{ __('warehouse.expired') }}</small>
                                                @elseif($stock->expired_at->diffInDays(now()->startOfDay()) < 30)
                                                    <small class="text-warning fw-bold"
                                                        style="font-size: 0.7rem;">{{ (int) $stock->expired_at->diffInDays(now()->startOfDay()) }}
                                                        {{ __('warehouse.days_left') }}</small>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="fw-bold {{ $stock->quantity < 10 ? 'text-danger' : ($stock->quantity < 20 ? 'text-warning' : 'text-success') }}">
                                            {{ $stock->quantity }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($stock->quantity <= 0)
                                            <span class="badge bg-secondary">{{ __('warehouse.out_of_stock') }}</span>
                                        @elseif($stock->expired_at && $stock->expired_at->isPast())
                                            <span class="badge bg-danger">{{ __('warehouse.expired') }}</span>
                                        @elseif($stock->quantity < $stock->min_stock)
                                            <span class="badge bg-danger">{{ __('warehouse.critical') }}</span>
                                        @elseif($stock->quantity < 20)
                                            <span class="badge bg-warning">{{ __('warehouse.low') }}</span>
                                        @else
                                            <span class="badge bg-success">{{ __('warehouse.good') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                style="border-radius: 10px; padding: 0.35rem 0.65rem; border: 1px solid #f2e8e5;">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                                                style="border-radius: 12px; font-size: 0.875rem;">
                                                <li>
                                                    <a class="dropdown-item py-2 px-3" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#adjustStockModal" data-stock-id="{{ $stock->id }}"
                                                        data-product-id="{{ $stock->product_id }}"
                                                        data-product-name="{{ $stock->product->name }}"
                                                        data-current-qty="{{ $stock->quantity }}"
                                                        data-batch-no="{{ $stock->batch_no }}"
                                                        data-expired-at="{{ $stock->expired_at ? $stock->expired_at->format('Y-m-d') : '' }}">
                                                        <i class="fa-solid fa-gear me-2 text-primary"></i>
                                                        {{ __('common.adjust') }}
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider mx-3 my-1" style="opacity: 0.05;">
                                                </li>
                                                <li>
                                                    <a class="dropdown-item py-2 px-3 text-danger" href="#"
                                                        onclick="scrapStock({{ $stock->id }})">
                                                        <i class="fa-solid fa-trash-can me-2"></i>
                                                        {{ __('common.delete') }}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($stocks->hasPages())
                <div class="card-footer bg-white border-0 d-flex justify-content-end py-3">
                    {{ $stocks->links('vendor.pagination.custom-brown') }}
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

                    <div class="row mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold"
                                style="color: #6f5849;">{{ __('warehouse.current_stock') }}</label>
                            <input type="text" class="form-control" id="current_stock" readonly
                                style="border-radius: 12px; background: #f8f9fa;">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold"
                                style="color: #6f5849;">{{ __('warehouse.adjustment_type') }}</label>
                            <select class="form-select" id="adjustment_type" onchange="toggleBatchFields()"
                                style="border-radius: 12px; border: 2px solid #e0cec7;">
                                <option value="add">{{ __('warehouse.add_stock') }}</option>
                                <option value="subtract">{{ __('warehouse.subtract_stock') }}</option>
                                <option value="set">{{ __('warehouse.set_stock') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('warehouse.quantity') }}</label>
                        <input type="number" class="form-control" id="adjustment_qty" min="0" step="1"
                            style="border-radius: 20px; border: 2px solid #e0cec7; font-size: 1.25rem; text-align: center; font-weight: bold;">
                    </div>

                    <!-- Batch Info Fields (Shown for 'add') -->
                    <div id="batch_fields">
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold"
                                    style="color: #6f5849;">{{ __('warehouse.batch_no') }}</label>
                                <input type="text" class="form-control" id="batch_no" placeholder="ABC-123"
                                    style="border-radius: 12px; border: 2px solid #e0cec7;">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold"
                                    style="color: #6f5849;">{{ __('warehouse.expired_at') }}</label>
                                <input type="date" class="form-control" id="expired_at"
                                    style="border-radius: 12px; border: 2px solid #e0cec7;">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"
                            style="color: #6f5849;">{{ __('warehouse.reason_optional') }}</label>
                        <input type="text" class="form-control" id="adjustment_reason"
                            placeholder="{{ __('warehouse.reason_placeholder') }}"
                            style="border-radius: 12px; border: 2px solid #e0cec7;">
                    </div>

                    <div class="alert alert-info py-2 ps-3 mb-0"
                        style="border-radius: 12px; border: none; background: #f2e8e5; color: #6f5849; font-size: 0.85rem;">
                        <i class="fa-solid fa-circle-info me-2"></i> {{ __('warehouse.adjustment_note') }}
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        style="border-radius: 12px; font-weight: 600;">{{ __('common.cancel') }}</button>
                    <button type="button" id="saveAdjustmentBtn" class="btn btn-primary" onclick="saveAdjustment()"
                        style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px; padding: 0.6rem 2rem; font-weight: 600;">
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
                    var button = event.relatedTarget;

                    currentStockId = button.getAttribute('data-stock-id');
                    currentProductId = button.getAttribute('data-product-id');
                    var productName = button.getAttribute('data-product-name');
                    var currentQty = button.getAttribute('data-current-qty');
                    var batchNo = button.getAttribute('data-batch-no');
                    var expiredAt = button.getAttribute('data-expired-at');

                    document.getElementById('product_name').value = productName;
                    document.getElementById('current_stock').value = currentQty + ' {{ __('warehouse.units') }}';

                    // Pre-fill existing batch info if adjusting specific record
                    document.getElementById('batch_no').value = batchNo || '';
                    document.getElementById('expired_at').value = expiredAt || '';

                    // Reset fields
                    document.getElementById('adjustment_qty').value = '';
                    document.getElementById('adjustment_reason').value = '';
                    document.getElementById('adjustment_type').value = 'add';

                    toggleBatchFields();
                });
            }

            // Auto-trigger adjustment modal if stock_id or product_id is in URL
            const urlParams = new URLSearchParams(window.location.search);
            const stockId = urlParams.get('stock_id');
            const productId = urlParams.get('product_id');

            if (stockId || productId) {
                setTimeout(() => {
                    let targetButton = null;
                    if (stockId) {
                        targetButton = document.querySelector(`[data-stock-id="${stockId}"]`);
                    } else if (productId) {
                        targetButton = document.querySelector(`[data-product-id="${productId}"]`);
                    }

                    if (targetButton) {
                        // Scroll to the button slowly then click it
                        targetButton.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        setTimeout(() => targetButton.click(), 500);
                    }
                }, 300);
            }
        });

        function toggleBatchFields() {
            const type = document.getElementById('adjustment_type').value;
            const batchFields = document.getElementById('batch_fields');

            // Only show/allow editing batch/expiry for "add" type
            if (type === 'add') {
                batchFields.style.opacity = '1';
                batchFields.style.pointerEvents = 'auto';
            } else {
                batchFields.style.opacity = '0.5';
                batchFields.style.pointerEvents = 'none';
            }
        }

        function saveAdjustment() {
            const type = document.getElementById('adjustment_type').value;
            const quantity = parseInt(document.getElementById('adjustment_qty').value);
            const reason = document.getElementById('adjustment_reason').value;
            const expired_at = document.getElementById('expired_at').value;
            const batch_no = document.getElementById('batch_no').value;

            if (!quantity || quantity <= 0) {
                Swal.fire({
                    icon: 'warning',
                    text: '{{ __('warehouse.enter_valid_quantity') }}',
                    customClass: { popup: 'artika-swal-popup' }
                });
                return;
            }

            const saveBtn = document.getElementById('saveAdjustmentBtn');
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
                    stock_id: currentStockId,
                    quantity: quantity,
                    type: type,
                    reason: reason,
                    expired_at: expired_at,
                    batch_no: batch_no
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
                            text: data.message,
                            customClass: { popup: 'artika-swal-popup' }
                        });
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> {{ __('warehouse.save_adjustment') }}';
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('common.error') }}',
                        text: error.message,
                        customClass: { popup: 'artika-swal-popup' }
                    });
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fa-solid fa-floppy-disk me-1"></i> {{ __('warehouse.save_adjustment') }}';
                });
        }

        function scrapStock(id) {
            Swal.fire({
                title: '{{ __('warehouse.scrap_stock') }}',
                text: '{{ __('warehouse.confirm_scrap') }}',
                icon: 'warning',
                input: 'text',
                inputPlaceholder: '{{ __('warehouse.scrap_reason') }}',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6f5849',
                confirmButtonText: '{{ __('common.delete') }}',
                cancelButtonText: '{{ __('common.cancel') }}',
                customClass: {
                    popup: 'artika-swal-popup',
                    title: 'artika-swal-title',
                    confirmButton: 'artika-swal-confirm-btn bg-danger border-0',
                    cancelButton: 'artika-swal-cancel-btn'
                },
                inputValidator: (value) => {
                    if (!value) {
                        return '{{ __('warehouse.scrap_reason') }}'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('warehouse/stock') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            reason: result.value
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __('common.success') }}',
                                    text: data.message,
                                    customClass: { popup: 'artika-swal-popup' }
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __('common.error') }}',
                                    text: data.message,
                                    customClass: { popup: 'artika-swal-popup' }
                                });
                            }
                        });
                }
            });
        }
    </script>
@endsection