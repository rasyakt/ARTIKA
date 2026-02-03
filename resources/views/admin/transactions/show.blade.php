@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold" style="color: #4b382f;">
                            <i class="fa-solid fa-file-invoice me-2"></i>Detail Transaksi
                        </h5>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <h6 class="text-muted text-uppercase small fw-bold mb-3">Informasi Toko</h6>
                                <h5 class="fw-bold mb-1">ARTIKA POS</h5>
                                <p class="text-muted mb-0 small">Sistem Point of Sale</p>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                <h6 class="text-muted text-uppercase small fw-bold mb-3">Invoice</h6>
                                <h5 class="fw-bold text-primary mb-1">{{ $transaction->invoice_no }}</h5>
                                <p class="text-muted mb-0 small">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <h6 class="text-muted text-uppercase small fw-bold mb-2">Kasir</h6>
                                <p class="mb-0 fw-semibold">{{ $transaction->user->name ?? 'System' }}</p>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                <h6 class="text-muted text-uppercase small fw-bold mb-2">Status</h6>
                                @if($transaction->status == 'completed')
                                    <span class="badge bg-success text-white">Sukses</span>
                                @elseif($transaction->status == 'rolled_back')
                                    <span class="badge bg-warning text-dark">Rolled Back</span>
                                @else
                                    <span class="badge bg-secondary text-white">{{ ucfirst($transaction->status) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless table-striped align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3">Produk</th>
                                        <th class="py-3 text-center">Harga</th>
                                        <th class="py-3 text-center">Jumlah</th>
                                        <th class="py-3 text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction->items as $item)
                                        <tr>
                                            <td class="py-3">
                                                <div class="fw-bold">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                                <div class="small text-muted">{{ $item->product->code ?? '-' }}</div>
                                            </td>
                                            <td class="py-3 text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="py-3 text-center">{{ $item->quantity }}</td>
                                            <td class="py-3 text-end fw-bold text-dark">Rp
                                                {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-end mt-4">
                            <div class="col-md-5">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Metode Pembayaran</span>
                                    <span class="fw-bold">{{ ucfirst($transaction->payment_method) }}</span>
                                </div>
                                <hr class="my-2" style="opacity: 0.1;">
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="h5 fw-bold mb-0">TOTAL</span>
                                    <span class="h5 fw-bold mb-0 text-primary">Rp
                                        {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                                </div>

                                @if($transaction->payment_method === 'cash')
                                    <div class="d-flex justify-content-between mb-1 small text-muted">
                                        <span>Bayar Tunai</span>
                                        <span>Rp {{ number_format($transaction->cash_amount, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1 small text-muted">
                                        <span>Kembalian</span>
                                        <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($transaction->note)
                            <div class="mt-4 p-3 bg-light rounded" style="border-left: 4px solid #85695a;">
                                <h6 class="text-muted text-uppercase small fw-bold mb-1">Catatan</h6>
                                <p class="mb-0 small">{{ $transaction->note }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-0 py-4 text-center">
                        <button onclick="window.print()" class="btn btn-primary px-4 me-2 d-print-none">
                            <i class="fa-solid fa-print me-2"></i> Cetak Langsung
                        </button>
                        <a href="{{ route('admin.transactions.show', ['id' => $transaction->id, 'format' => 'pdf']) }}"
                            class="btn btn-outline-primary px-4 me-2 d-print-none">
                            <i class="fa-solid fa-file-pdf me-2"></i> Download PDF
                        </a>
                        @if($transaction->status !== 'rolled_back')
                            <a href="{{ route('admin.transactions.edit', $transaction->id) }}"
                                class="btn btn-outline-warning px-4 d-print-none">
                                <i class="fa-solid fa-pen-to-square me-2"></i> Edit Transaksi
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .main-navbar,
            .sidebar,
            .d-print-none,
            .breadcrumb {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #eee !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
@endsection