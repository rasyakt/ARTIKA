<!-- Transaction Detail Modal -->
<div class="modal fade" id="transactionDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-receipt me-2"></i>{{ __('admin.transaction_detail') }}
                    <span id="tx-invoice-no" class="text-brown ms-2">#0000</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Info Header -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="text-muted small text-uppercase fw-bold d-block">{{ __('admin.cashier') }}</label>
                        <span id="tx-cashier" class="fw-bold">N/A</span>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small text-uppercase fw-bold d-block">{{ __('admin.date') }}</label>
                        <span id="tx-date" class="fw-bold">N/A</span>
                    </div>
                    <div class="col-md-3">
                        <label
                            class="text-muted small text-uppercase fw-bold d-block">{{ __('admin.payment_method') }}</label>
                        <span id="tx-payment-method" class="fw-bold">N/A</span>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small text-uppercase fw-bold d-block">{{ __('admin.status') }}</label>
                        <div id="tx-status">
                            <span class="badge bg-success py-2 w-100">COMPLETED</span>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-4" style="max-height: 300px;">
                    <table class="table align-middle">
                        <thead class="sticky-top bg-white">
                            <tr class="text-muted small text-uppercase">
                                <th class="border-0">{{ __('admin.product_management') }}</th>
                                <th class="border-0 text-center">{{ __('admin.quantity') }}</th>
                                <th class="border-0 text-center">{{ __('admin.subtotal') }}</th>
                                <th class="border-0 text-end"></th>
                            </tr>
                        </thead>
                        <tbody id="tx-items-body">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>

                <!-- Footer Summary -->
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="p-3 bg-light rounded-16 h-100">
                            <p class="small text-muted mb-0">Catatan/Keterangan:</p>
                            <p class="small mb-0 fst-italic">Tidak ada catatan untuk transaksi ini.</p>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Subtotal:</span>
                            <span id="tx-subtotal" class="fw-bold">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Diskon:</span>
                            <span id="tx-discount" class="fw-bold text-danger">- Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 border-top pt-1 mt-1">
                            <span class="fw-bold text-dark">TOTAL:</span>
                            <span id="tx-total" class="fw-bold text-primary fs-5">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1 mt-3">
                            <span class="text-muted small">Bayar:</span>
                            <span id="tx-cash-received" class="text-dark small">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Kembalian:</span>
                            <span id="tx-change" class="text-dark small">Rp 0</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4">
                @if(\App\Models\Setting::get('cashier_enable_returns', true))
                    <button type="button" class="btn btn-outline-danger px-4 me-auto d-none" id="btn-initiate-return-tx"
                        style="border-radius: 10px;">
                        <i class="fa-solid fa-rotate-left me-2"></i>{{ __('admin.return_items') }}
                    </button>
                @endif
                <button type="button" class="btn btn-outline-brown px-4" id="btn-print-receipt"
                    style="border-radius: 10px;">
                    <i class="fa-solid fa-print me-2"></i>Cetak Struk
                </button>
                <button type="button" class="btn btn-brown px-4" style="border-radius: 10px;"
                    data-bs-dismiss="modal">{{ __('admin.close') }}</button>
            </div>
        </div>
    </div>
</div>