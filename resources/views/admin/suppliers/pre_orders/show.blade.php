@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.pre_orders.index') }}"
                                style="color: #85695a;">{{ __('admin.supplier_pre_orders') ?? 'Pre-Order Supplier' }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $preOrder->uuid }}</li>
                    </ol>
                </nav>
                <h4 class="fw-bold mb-0" style="color: #6f5849;">
                    <i class="fa-solid fa-receipt me-2"></i>{{ __('admin.pre_order_details') ?? 'Detail Pre-Order' }}
                </h4>
            </div>
            <div class="d-flex gap-2">
                @if($preOrder->status !== 'received' && $preOrder->status !== 'cancelled')
                    <form action="{{ route('admin.suppliers.pre_orders.update_status', $preOrder->id) }}" method="POST"
                        id="receive-form">
                        @csrf
                        <input type="hidden" name="status" value="received">
                        <button type="submit" class="btn btn-success shadow-sm" style="border-radius: 10px; font-weight: 600;"
                            onclick="return confirm('Tandai sebagai diterima dan update stok?')">
                            <i class="fa-solid fa-check-double me-1"></i> {{ __('admin.mark_received') ?? 'Tandai Diterima' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.suppliers.pre_orders.update_status', $preOrder->id) }}" method="POST"
                        id="cancel-form">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn btn-outline-danger shadow-sm"
                            style="border-radius: 10px; font-weight: 600;" onclick="return confirm('Batalkan pesanan ini?')">
                            <i class="fa-solid fa-xmark me-1"></i> {{ __('admin.cancel_order') ?? 'Batalkan' }}
                        </button>
                    </form>
                @endif
                @if($preOrder->status === 'received')
                    <a href="{{ route('admin.suppliers.pre_orders.print_faktur', $preOrder->id) }}" target="_blank"
                        class="btn btn-primary shadow-sm" style="border-radius: 10px; font-weight: 600;">
                        <i class="fa-solid fa-print me-1"></i> Cetak Faktur
                    </a>
                @endif
                <a href="{{ route('admin.suppliers.pre_orders.index') }}" class="btn btn-light shadow-sm"
                    style="border-radius: 10px; font-weight: 600;">
                    <i class="fa-solid fa-arrow-left me-1"></i> {{ __('common.back') }}
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4" style="color: #6f5849;">{{ __('admin.order_info') ?? 'Informasi Pesanan' }}
                        </h5>

                        <div class="mb-3">
                            <label class="small text-muted d-block mb-1">{{ __('common.status') }}</label>
                            @php
                                $badgeClass = match ($preOrder->status) {
                                    'pending' => 'bg-warning text-dark',
                                    'ordered' => 'bg-info text-white',
                                    'shipped' => 'bg-primary text-white',
                                    'received' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white',
                                    default => 'bg-secondary text-white',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}" style="border-radius: 8px; padding: 0.5rem 0.75rem;">
                                {{ ucfirst($preOrder->status) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted d-block mb-1">{{ __('common.supplier') }}</label>
                            <div class="fw-semibold text-dark">{{ $preOrder->supplier->name }}</div>
                        </div>

                        <div class="mb-3">
                            <label
                                class="small text-muted d-block mb-1">{{ __('admin.reference_number') ?? 'Nomor Referensi' }}</label>
                            <div class="fw-semibold text-dark">{{ $preOrder->reference_number ?: '-' }}</div>
                        </div>

                        <div class="mb-3">
                            <label
                                class="small text-muted d-block mb-1">{{ __('admin.expected_arrival') ?? 'Estimasi Kedatangan' }}</label>
                            <div class="fw-semibold text-dark">
                                {{ $preOrder->expected_arrival_date ? $preOrder->expected_arrival_date->format('d M Y') : '-' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted d-block mb-1">{{ __('admin.created_at') }}</label>
                            <div class="fw-semibold text-dark">{{ $preOrder->created_at->format('d M Y H:i') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="small text-muted d-block mb-1">{{ __('admin.added_by') }}</label>
                            <div class="fw-semibold text-dark">{{ $preOrder->user->name }}</div>
                        </div>

                        <div class="mb-0">
                            <label class="small text-muted d-block mb-1">{{ __('admin.notes') }}</label>
                            <div class="text-dark">{{ $preOrder->notes ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header border-0 py-4 px-4 bg-white">
                        <h5 class="fw-bold mb-0" style="color: #6f5849;">{{ __('admin.order_items') ?? 'Daftar Barang' }}
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead style="background-color: #fdf8f6;">
                                    <tr>
                                        <th class="px-4 py-3 border-0" style="color: #6f5849; font-weight: 600;">
                                            {{ __('admin.product') }}
                                        </th>
                                        <th class="py-3 border-0 text-center" style="color: #6f5849; font-weight: 600;">
                                            {{ __('admin.unit') }}
                                        </th>
                                        <th class="py-3 border-0 text-center" style="color: #6f5849; font-weight: 600;">Qty
                                        </th>
                                        <th class="py-3 border-0 text-center" style="color: #6f5849; font-weight: 600;">
                                            Pcs/Unit</th>
                                        <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">
                                            {{ __('admin.purchase_price') ?? 'Harga Beli' }}
                                        </th>
                                        <th class="py-3 border-0 text-end pe-4" style="color: #6f5849; font-weight: 600;">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($preOrder->items as $item)
                                        <tr style="border-bottom: 1px solid #f2e8e5;">
                                            <td class="px-4 py-3">
                                                <div class="fw-bold text-dark">{{ $item->product->name }}</div>
                                                <div class="small text-muted">{{ $item->product->barcode }}</div>
                                            </td>
                                            <td class="py-3 text-center">
                                                <span class="badge rounded-pill"
                                                    style="background: #fdf8f6; color: #85695a; border: 1px solid #f2e8e5; padding: 0.5rem 1rem;">
                                                    {{ $item->unit_name }}
                                                </span>
                                            </td>
                                            <td class="py-3 text-center">
                                                {{ $item->quantity }}
                                            </td>
                                            <td class="py-3 text-center">
                                                {{ $item->pcs_per_unit }}
                                            </td>
                                            <td class="py-3">
                                                Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                                <div class="small text-muted">per Pcs (HPP)</div>
                                            </td>
                                            <td class="py-3 text-end pe-4">
                                                <div class="fw-bold" style="color: #6f5849;">Rp
                                                    {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </div>
                                                <div class="small text-muted">
                                                    {{ $item->quantity }} {{ $item->unit_name }}
                                                    ({{ $item->quantity * $item->pcs_per_unit }} Pcs)
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background-color: #fdf8f6;">
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-end fw-bold" style="color: #6f5849;">
                                            {{ __('admin.total_purchase_amount') ?? 'Total Harga Beli' }}
                                        </td>
                                        <td class="py-3 text-end pe-4 fw-bold" style="color: #6f5849; font-size: 1.2rem;">
                                            Rp {{ number_format($preOrder->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection