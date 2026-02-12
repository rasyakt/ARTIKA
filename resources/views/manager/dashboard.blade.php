@extends('layouts.app')

@section('title', __('admin.manager_dashboard'))

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;"><i
                        class="fa-solid fa-user-tie me-2"></i>{{ __('admin.manager_dashboard') }}</h2>
                <p class="text-muted mb-0">{{ __('admin.manage_store_oversight') }}</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark p-2" style="border-radius: 8px;">
                    <i class="fa-solid fa-calendar me-1"></i> {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-5">
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100"
                    style="border-radius: 20px; background: primary-dark; color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-brown-soft bg-opacity-20 p-3 rounded-circle" style="backdrop-filter: blur(5px);">
                                <i class="fa-solid fa-money-bill-trend-up fa-xl" style="color: #6f5849;"></i>
                            </div>
                        </div>
                        <h6 class="text-muted text-opacity-75 fw-bold mb-1">{{ __('admin.today_sales') }}</h6>
                        <h3 class="fw-bold mb-0" style="color: #2c3e50;">Rp{{ number_format($stats['today_sales'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: white;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-brown-soft bg-opacity-20 p-3 rounded-circle" style="backdrop-filter: blur(5px);">
                                <i class="fa-solid fa-receipt fa-xl" style="color: #6f5849;"></i>
                            </div>
                        </div>
                        <h6 class="text-muted fw-bold mb-1">{{ __('admin.total_transactions') }}</h6>
                        <h3 class="fw-bold mb-0" style="color: #2c3e50;">{{ number_format($stats['today_count']) }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-xl-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 20px; background: white;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg- brown-soft bg-opacity-20 p-3 rounded-circle" style="backdrop-filter: blur(5px);">
                                <i class="fa-solid fa-scale-balanced fa-xl" style="color: #6f5849;"></i>
                            </div>
                        </div>
                        <h6 class="text-muted fw-bold mb-1">{{ __('admin.avg_transaction') }}</h6>
                        <h3 class="fw-bold mb-0" style="color: #2c3e50;">
                            Rp{{ number_format($stats['today_avg'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-header bg-white py-4 px-4 border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0" style="color: #6f5849;"><i
                            class="fa-solid fa-clock-rotate-left me-2"></i>{{ __('admin.recent_transactions') }}</h5>
                    <a href="{{ route('manager.reports.cashier') }}" class="btn btn-sm btn-outline-brown"
                        style="border-radius: 10px; border-color: #6f5849; color: #6f5849;">
                        {{ __('admin.view_details') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 border-0 py-3 text-muted small text-uppercase fw-bold">
                                    {{ __('admin.invoice') }}</th>
                                <th class="border-0 py-3 text-muted small text-uppercase fw-bold">{{ __('admin.cashier') }}
                                </th>
                                <th class="border-0 py-3 text-muted small text-uppercase fw-bold">{{ __('admin.amount') }}
                                </th>
                                <th class="border-0 py-3 text-muted small text-uppercase fw-bold">
                                    {{ __('admin.payment_method') }}</th>
                                <th class="border-0 py-3 text-muted small text-uppercase fw-bold">{{ __('admin.date') }}
                                </th>
                                <th class="border-0 py-3 text-muted small text-uppercase fw-bold text-end pe-4">
                                    {{ __('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $transaction)
                                <tr>
                                    <td class="ps-4">
                                        <span class="fw-bold" style="color: #6f5849;">#{{ $transaction->invoice_no }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-2"
                                                style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa-solid fa-user text-muted small"></i>
                                            </div>
                                            <span class="small fw-semibold">{{ $transaction->user->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="fw-bold">Rp{{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $transaction->payment_method === 'cash' ? 'bg-success-soft' : 'bg-primary-soft' }}"
                                            style="border-radius: 8px; padding: 6px 12px; font-weight: 600;">
                                            {{ strtoupper($transaction->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border shadow-sm p-2 rounded-3" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-h text-brown"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                                <li>
                                                    <h6 class="dropdown-header text-muted small text-uppercase fw-bold">
                                                        {{ __('admin.action') }}
                                                    </h6>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item btn-view-transaction py-2" data-id="{{ $transaction->id }}">
                                                        <i class="fas fa-eye me-2 text-brown"></i> Detail
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class="dropdown-item btn-print-direct py-2" data-id="{{ $transaction->id }}">
                                                        <i class="fas fa-print me-2 text-secondary"></i> Cetak Struk
                                                    </button>
                                                </li>
                                                @if($transaction->status !== 'rolled_back')
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item btn-edit-transaction py-2 text-primary"
                                                            data-id="{{ $transaction->id }}"
                                                            data-method="{{ $transaction->payment_method }}"
                                                            data-cash="{{ $transaction->cash_amount }}">
                                                            <i class="fas fa-edit me-2"></i> Edit/Koreksi
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fa-solid fa-receipt fa-3x mb-3 text-muted opacity-25"></i>
                                        <p class="text-muted mb-0">{{ __('admin.no_transactions_found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@include('manager.modals.transaction_details')
@include('manager.modals.transaction_edit')
@include('manager.modals.return_process')

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const transactionDetailModal = new bootstrap.Modal(document.getElementById('transactionDetailModal'));
        const transactionEditModal = new bootstrap.Modal(document.getElementById('transactionEditModal'));
        const returnProcessModal = new bootstrap.Modal(document.getElementById('returnProcessModal'));

        // Transaction Detail View
        document.querySelectorAll('.btn-view-transaction').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const itemsBody = document.getElementById('tx-items-body');
                const invoiceSpan = document.getElementById('tx-invoice-no');

                itemsBody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</td></tr>';
                transactionDetailModal.show();

                fetch(`{{ route($routePrefix . 'reports.cashier.items', ['id' => ':id']) }}`.replace(':id', id))
                    .then(response => response.json())
                    .then(data => {
                        invoiceSpan.textContent = `#${data.invoice_no}`;
                        document.getElementById('tx-cashier').textContent = data.cashier_name;
                        document.getElementById('tx-date').textContent = data.date;
                        document.getElementById('tx-payment-method').textContent = data.payment_method;

                        const statusBadge = document.getElementById('tx-status');
                        if (data.status === 'rolled_back') {
                            statusBadge.textContent = 'ROLLED BACK';
                            statusBadge.className = 'badge bg-danger py-2 w-100';
                        } else if (data.status === 'returned') {
                            statusBadge.textContent = 'RETURNED';
                            statusBadge.className = 'badge bg-danger py-2 w-100';
                        } else if (data.status === 'partial_return') {
                            statusBadge.textContent = 'PARTIAL RETURN';
                            statusBadge.className = 'badge bg-warning text-dark py-2 w-100';
                        } else {
                            statusBadge.textContent = 'COMPLETED';
                            statusBadge.className = 'badge bg-success py-2 w-100';
                        }

                        const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);
                        document.getElementById('tx-subtotal').textContent = `Rp ${formatIDR(data.subtotal)}`;
                        document.getElementById('tx-discount').textContent = `- Rp ${formatIDR(data.discount)}`;

                        const totalRow = document.getElementById('tx-total').closest('.row');
                        if (data.total_refunded > 0) {
                            document.getElementById('tx-total').innerHTML = `<span class="text-decoration-line-through text-muted small">Rp ${formatIDR(data.total_amount)}</span><br>Rp ${formatIDR(data.total_amount - data.total_refunded)}`;
                            let refundSummary = document.getElementById('tx-refund-summary');
                            if (!refundSummary) {
                                refundSummary = document.createElement('div');
                                refundSummary.id = 'tx-refund-summary';
                                refundSummary.className = 'd-flex justify-content-between align-items-center mt-2 p-2 bg-light rounded text-danger col-md-5 ms-auto';
                                totalRow.parentNode.insertBefore(refundSummary, totalRow.nextSibling);
                            }
                            refundSummary.innerHTML = `<span>Refunded:</span><span class="fw-bold">- Rp ${formatIDR(data.total_refunded)}</span>`;
                            refundSummary.classList.remove('d-none');
                        } else {
                            document.getElementById('tx-total').textContent = `Rp ${formatIDR(data.total_amount)}`;
                            const refundSummary = document.getElementById('tx-refund-summary');
                            if (refundSummary) refundSummary.classList.add('d-none');
                        }

                        document.getElementById('tx-cash-received').textContent = `Rp ${formatIDR(data.cash_amount)}`;
                        document.getElementById('tx-change').textContent = `Rp ${formatIDR(data.change_amount)}`;

                        const btnReturn = document.getElementById('btn-initiate-return-tx');
                        if (btnReturn && (data.status === 'completed' || data.status === 'partial_return')) {
                            btnReturn.classList.remove('d-none');
                            btnReturn.onclick = function () {
                                transactionDetailModal.hide();
                                document.getElementById('return-tx-id').value = id;
                                let returnItemsHtml = '';
                                data.items.forEach((item, index) => {
                                    const qty = parseInt(item.quantity) || 0;
                                    const retQty = parseInt(item.returned_quantity) || 0;
                                    const availableToReturn = qty - retQty;
                                    returnItemsHtml += `
                                        <tr>
                                            <td>
                                                <div class="fw-bold">${item.name}</div>
                                                <div class="small text-muted">Rp ${formatIDR(item.price)} / unit</div>
                                                ${retQty > 0 ? `<div class="badge bg-light text-brown border" style="font-size: 0.65rem;">${retQty} Terkumpul</div>` : ''}
                                                <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                                            </td>
                                            <td class="text-center">${availableToReturn}</td>
                                            <td>
                                                <div class="input-group input-group-sm">
                                                    <button type="button" class="btn btn-outline-brown btn-qty" data-type="minus" ${availableToReturn <= 0 ? 'disabled' : ''}>-</button>
                                                    <input type="number" name="items[${index}][quantity]" class="form-control text-center input-qty" 
                                                        value="0" min="0" max="${availableToReturn}" data-price="${item.price}" readonly>
                                                    <button type="button" class="btn btn-outline-brown btn-qty" data-type="plus" ${availableToReturn <= 0 ? 'disabled' : ''}>+</button>
                                                </div>
                                            </td>
                                            <td class="text-end fw-bold text-brown">Rp <span class="item-refund-est">0</span></td>
                                        </tr>`;
                                });
                                document.getElementById('return-items-body').innerHTML = returnItemsHtml;
                                document.getElementById('return-total-refund').textContent = '0';
                                bindReturnEvents();
                                setTimeout(() => returnProcessModal.show(), 400);
                            };
                        } else if (btnReturn) {
                            btnReturn.classList.add('d-none');
                        }

                        document.getElementById('btn-print-receipt').onclick = function () {
                            const width = 400; height = 600;
                            const left = (window.screen.width / 2) - (width / 2);
                            const top = (window.screen.height / 2) - (height / 2);
                            window.open(`{{ route($routePrefix . 'reports.cashier.receipt', ['id' => ':id']) }}`.replace(':id', id), 'Receipt', `width=${width},height=${height},top=${top},left=${left},scrollbars=yes`);
                        };

                        itemsBody.innerHTML = '';
                        data.items.forEach(item => {
                            const netQuantity = item.quantity - item.returned_quantity;
                            itemsBody.innerHTML += `
                                <tr>
                                    <td>
                                        <div class="fw-bold text-brown">${item.name}</div>
                                        <div class="small text-muted">Rp ${formatIDR(item.price)}</div>
                                    </td>
                                    <td class="text-center text-brown">
                                        ${item.returned_quantity > 0 ? `<span class="text-decoration-line-through text-muted small">${item.quantity}</span><br><b>${netQuantity}</b>` : `<b>${item.quantity}</b>`}
                                    </td>
                                    <td class="text-center text-brown">
                                        ${item.returned_quantity > 0 ? `<span class="text-decoration-line-through text-muted small">Rp ${formatIDR(item.subtotal)}</span><br><b>Rp ${formatIDR(netQuantity * item.price)}</b>` : `<b>Rp ${formatIDR(item.subtotal)}</b>`}
                                    </td>
                                    <td class="text-end">
                                        ${item.returned_quantity > 0 ? `<span class="badge bg-danger-soft text-danger border border-danger-subtle">- ${item.returned_quantity} Retur</span>` : '<i class="fa-solid fa-check text-success"></i>'}
                                    </td>
                                </tr>`;
                        });
                    })
                    .catch(err => {
                        console.error(err);
                        itemsBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4"><i class="fas fa-exclamation-circle me-2"></i>Error loading details</td></tr>';
                    });
            });
        });

        // Direct Print
        document.querySelectorAll('.btn-print-direct').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const width = 400; height = 600;
                const left = (window.screen.width / 2) - (width / 2);
                const top = (window.screen.height / 2) - (height / 2);
                window.open(`{{ route($routePrefix . 'reports.cashier.receipt', ['id' => ':id']) }}`.replace(':id', id), 'Receipt', `width=${width},height=${height},top=${top},left=${left},scrollbars=yes`);
            });
        });

        // Transaction Edit
        document.querySelectorAll('.btn-edit-transaction').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const method = this.getAttribute('data-method');
                const cash = this.getAttribute('data-cash');

                document.getElementById('tx-edit-form').action = `{{ route($routePrefix . 'reports.cashier.update', ['id' => ':id']) }}`.replace(':id', id);
                document.getElementById('tx-edit-method').value = method;
                document.getElementById('tx-edit-cash').value = cash;

                transactionEditModal.show();
            });
        });

        function bindReturnEvents() {
            const updateTotals = () => {
                let totalRefund = 0;
                document.querySelectorAll('.input-qty').forEach(input => {
                    const qty = parseInt(input.value) || 0;
                    const price = parseFloat(input.getAttribute('data-price')) || 0;
                    const refund = qty * price;
                    input.closest('tr').querySelector('.item-refund-est').textContent = new Intl.NumberFormat('id-ID').format(refund);
                    totalRefund += refund;
                });
                document.getElementById('return-total-refund').textContent = new Intl.NumberFormat('id-ID').format(totalRefund);
            };

            document.querySelectorAll('.btn-qty').forEach(btn => {
                btn.onclick = function () {
                    const input = this.closest('.input-group').querySelector('.input-qty');
                    const type = this.getAttribute('data-type');
                    const max = parseInt(input.getAttribute('max'));
                    let val = parseInt(input.value) || 0;
                    if (type === 'plus' && val < max) val++;
                    else if (type === 'minus' && val > 0) val--;
                    input.value = val;
                    updateTotals();
                };
            });

            document.querySelectorAll('.input-qty').forEach(input => {
                input.oninput = function () {
                    let val = parseInt(this.value) || 0;
                    const max = parseInt(this.getAttribute('max'));
                    if (val > max) this.value = max;
                    if (val < 0) this.value = 0;
                    updateTotals();
                };
            });
        }
    });
</script>
@endpush

    <style>
        .bg-success-soft {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .bg-primary-soft {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .bg-danger-soft {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .text-brown {
            color: #6f5849;
        }

        .btn-outline-brown {
            border-color: #6f5849;
            color: #6f5849;
        }

        .btn-outline-brown:hover {
            background-color: #6f5849;
            color: white !important;
        }
        
        .bg-brown-soft {
            background-color: rgba(111, 88, 73, 0.1);
        }

        .rounded-16 {
            border-radius: 16px;
        }
    </style>
@endsection