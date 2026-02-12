<!-- Return Process Modal -->
<div class="modal fade" id="returnProcessModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
            <form id="return-process-form" action="{{ route('manager.returns.store') }}" method="POST">
                @csrf
                <input type="hidden" name="transaction_id" id="return-tx-id">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-rotate-left me-2"></i>{{ __('admin.process_return') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">{{ __('admin.select_items_to_return') }}</p>

                    <div class="table-responsive mb-4">
                        <table class="table align-middle">
                            <thead>
                                <tr class="text-muted small">
                                    <th class="border-0">Produk</th>
                                    <th class="border-0 text-center">Terjual</th>
                                    <th class="border-0 text-center" style="width: 150px;">
                                        {{ __('admin.return_quantity') }}
                                    </th>
                                    <th class="border-0 text-end">Refund Est.</th>
                                </tr>
                            </thead>
                            <tbody id="return-items-body">
                                <!-- Populated via JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-brown">{{ __('admin.return_reason') }}*</label>
                        <textarea name="reason" class="form-control" rows="2" required
                            placeholder="Contoh: Barang cacat, Salah beli..." style="border-radius: 12px;"></textarea>
                    </div>

                    <div class="p-3 bg-brown-soft rounded-16">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-brown">Total Pengembalian Dana (Refund)</span>
                            <h4 class="fw-bold text-brown mb-0">Rp <span id="return-total-refund">0</span></h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-brown px-4" style="border-radius: 10px;"
                        data-bs-toggle="modal"
                        data-bs-target="#transactionDetailModal">{{ __('admin.cancel') }}</button>
                    <button type="submit" class="btn btn-danger px-4" style="border-radius: 10px;">
                        {{ __('admin.process_return') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>