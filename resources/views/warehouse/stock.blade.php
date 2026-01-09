@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: #6f5849;">📦 Stock Management</h2>
            <p class="text-muted mb-0">Manage product stock across all branches</p>
        </div>

        <!-- Stock Table -->
        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;">📋 Stock Levels</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">Product</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Category</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Branch</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Current Stock</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Min Stock</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Status</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">Actions</th>
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
                                    <td>{{ $stock->branch->name }}</td>
                                    <td>
                                        <span
                                            class="fw-bold {{ $stock->quantity < 10 ? 'text-danger' : ($stock->quantity < 20 ? 'text-warning' : 'text-success') }}">
                                            {{ $stock->quantity }} units
                                        </span>
                                    </td>
                                    <td>{{ $stock->min_stock }}</td>
                                    <td>
                                        @if($stock->quantity < 10)
                                            <span class="badge bg-danger">Critical</span>
                                        @elseif($stock->quantity < 20)
                                            <span class="badge bg-warning">Low</span>
                                        @else
                                            <span class="badge bg-success">Good</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="adjustStock({{ $stock->id }}, {{ $stock->product_id }}, {{ $stock->branch_id }}, '{{ $stock->product->name }}', {{ $stock->quantity }})"
                                            style="border-radius: 8px;">
                                            ⚙️ Adjust
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Adjust Stock Modal -->
    <div class="modal fade" id="adjustStockModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;">⚙️ Adjust Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">Product</label>
                        <input type="text" class="form-control" id="product_name" readonly
                            style="border-radius: 12px; background: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">Current Stock</label>
                        <input type="text" class="form-control" id="current_stock" readonly
                            style="border-radius: 12px; background: #f8f9fa;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">Adjustment Type</label>
                        <select class="form-select" id="adjustment_type"
                            style="border-radius: 12px; border: 2px solid #e0cec7;">
                            <option value="add">Add Stock (+)</option>
                            <option value="subtract">Subtract Stock (-)</option>
                            <option value="set">Set Stock (=)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">Quantity</label>
                        <input type="number" class="form-control" id="adjustment_qty" min="0"
                            style="border-radius: 12px; border: 2px solid #e0cec7;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color: #6f5849;">Reason (Optional)</label>
                        <input type="text" class="form-control" id="adjustment_reason"
                            placeholder="e.g., Restock, Damaged goods, etc."
                            style="border-radius: 12px; border: 2px solid #e0cec7;">
                    </div>
                    <div class="alert alert-info" style="border-radius: 12px;">
                        <strong>Note:</strong> Stock adjustments will be recorded for audit purposes.
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        style="border-radius: 12px;">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveAdjustment()"
                        style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">
                        💾 Save Adjustment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStockId = null;
        let currentProductId = null;
        let currentBranchId = null;

        function adjustStock(stockId, productId, branchId, productName, currentQty) {
            currentStockId = stockId;
            currentProductId = productId;
            currentBranchId = branchId;
            document.getElementById('product_name').value = productName;
            document.getElementById('current_stock').value = currentQty + ' units';
            document.getElementById('adjustment_qty').value = '';
            document.getElementById('adjustment_reason').value = '';
            new bootstrap.Modal(document.getElementById('adjustStockModal')).show();
        }

        function saveAdjustment() {
            const type = document.getElementById('adjustment_type').value;
            const quantity = parseInt(document.getElementById('adjustment_qty').value);
            const reason = document.getElementById('adjustment_reason').value;

            if (!quantity || quantity <= 0) {
                alert('Please enter a valid quantity');
                return;
            }

            // Disable button to prevent double submission
            const saveBtn = event.target;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '⏳ Saving...';

            fetch('{{ route("warehouse.stock.adjust") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: currentProductId,
                    branch_id: currentBranchId,
                    quantity: quantity,
                    type: type,
                    reason: reason
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ ' + data.message + '\nNew quantity: ' + data.new_quantity + ' units');
                    location.reload();
                } else {
                    alert('❌ Error: ' + data.message);
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '💾 Save Adjustment';
                }
            })
            .catch(error => {
                alert('❌ Error: ' + error.message);
                saveBtn.disabled = false;
                saveBtn.innerHTML = '💾 Save Adjustment';
            });
        }
    </script>
@endsection