<!-- Transaction Edit Modal -->
<div class="modal fade" id="transactionEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
            <form id="tx-edit-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pen-to-square me-2"></i>Edit Transaksi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <select name="payment_method" id="tx-edit-method" class="form-select">
                            <option value="Cash">Tunai</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer">Transfer</option>
                            <option value="Debit">Debit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Uang Diterima</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="cash_amount" id="tx-edit-cash" class="form-control" min="0"
                                step="1">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" style="border-radius: 10px;"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brown px-4" style="border-radius: 10px;">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>