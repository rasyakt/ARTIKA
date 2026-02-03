@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color: #4b382f;">
                            <i class="fa-solid fa-pen-to-square me-2"></i>Edit Transaksi #{{ $transaction->invoice_no }}
                        </h5>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0 shadow-sm mb-4"
                            style="border-radius: 12px; background: #eef2ff;">
                            <div class="d-flex">
                                <i class="fa-solid fa-circle-info mt-1 me-3 text-primary"></i>
                                <div>
                                    <h6 class="fw-bold text-primary mb-1">Informasi Penyesuaian Stok</h6>
                                    <p class="mb-0 small text-muted">Mengurangi jumlah akan mengembalikan barang ke stok.
                                        Menambah jumlah akan mengurangi stok (pastikan stok tersedia).</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.transactions.update', $transaction->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="py-3 px-4">Produk</th>
                                            <th class="py-3 text-center">Harga Satuan</th>
                                            <th class="py-3 text-center" style="width: 200px;">Jumlah</th>
                                            <th class="py-3 text-end px-4">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaction->items as $index => $item)
                                            <tr>
                                                <td class="py-3 px-4">
                                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item->id }}">
                                                    <div class="fw-bold text-dark">
                                                        {{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                                    <div class="small text-muted">ID: {{ $item->product_id }}</div>
                                                </td>
                                                <td class="py-3 text-center text-muted">
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </td>
                                                <td class="py-3">
                                                    <div class="input-group input-group-sm justify-content-center">
                                                        <button type="button" class="btn btn-outline-secondary qty-btn"
                                                            onclick="adjustQty(this, -1)">-</button>
                                                        <input type="number" name="items[{{ $index }}][quantity]"
                                                            class="form-control text-center qty-input"
                                                            value="{{ $item->quantity }}" min="0" style="max-width: 80px;"
                                                            data-price="{{ $item->price }}" onchange="updateSubtotal(this)">
                                                        <button type="button" class="btn btn-outline-secondary qty-btn"
                                                            onclick="adjustQty(this, 1)">+</button>
                                                    </div>
                                                </td>
                                                <td class="py-3 text-end px-4 fw-bold text-dark">
                                                    <span class="subtotal-text">Rp
                                                        {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-light">
                                        <tr>
                                            <td colspan="3" class="text-end py-3 fw-bold">TOTAL BARU:</td>
                                            <td class="text-end py-3 px-4 fw-bold text-primary h5 mb-0" id="grand-total">
                                                Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm"
                                    style="border-radius: 10px;">
                                    <i class="fa-solid fa-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function adjustQty(btn, delta) {
                const input = btn.parentElement.querySelector('.qty-input');
                let newVal = parseInt(input.value) + delta;
                if (newVal < 0) newVal = 0;
                input.value = newVal;
                updateSubtotal(input);
            }

            function updateSubtotal(input) {
                const price = parseFloat(input.dataset.price);
                const qty = parseInt(input.value);
                const subtotal = price * qty;

                const row = input.closest('tr');
                row.querySelector('.subtotal-text').innerText = formatRupiah(subtotal);

                calculateGrandTotal();
            }

            function calculateGrandTotal() {
                let total = 0;
                document.querySelectorAll('.qty-input').forEach(input => {
                    total += parseInt(input.value) * parseFloat(input.dataset.price);
                });
                document.getElementById('grand-total').innerText = formatRupiah(total);
            }

            function formatRupiah(amount) {
                return 'Rp ' + amount.toLocaleString('id-ID', { minimumFractionDigits: 0 });
            }
        </script>
    @endpush
@endsection