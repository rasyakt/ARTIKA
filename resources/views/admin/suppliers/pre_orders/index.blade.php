@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;"><i
                        class="fa-solid fa-receipt me-2"></i>{{ __('admin.supplier_pre_orders') ?? 'Pre-Order Supplier' }}</h2>
                <p class="text-muted mb-0">{{ __('admin.manage_pre_orders_desc') ?? 'Kelola pesanan barang ke supplier' }}</p>
            </div>
            <a href="{{ route('admin.suppliers.pre_orders.create') }}" class="btn btn-primary shadow-sm d-inline-flex align-items-center"
                style="background: #6f5849; border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600; height: fit-content;">
                <i class="fa-solid fa-plus me-2"></i> {{ __('admin.add_pre_order') ?? 'Tambah Pre-Order' }}
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">{{ __('common.date') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.supplier') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.reference') ?? 'Referensi' }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.total_amount') ?? 'Total' }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.status') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($preOrders as $order)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold" style="color: #6f5849;">{{ $order->created_at->format('Y-m-d') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                    </td>
                                    <td>{{ $order->supplier->name }}</td>
                                    <td>{{ $order->reference_number ?? '-' }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($order->status) {
                                                'pending' => 'bg-warning text-dark',
                                                'ordered' => 'bg-info text-white',
                                                'shipped' => 'bg-primary text-white',
                                                'received' => 'bg-success text-white',
                                                'cancelled' => 'bg-danger text-white',
                                                default => 'bg-secondary text-white',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}" style="border-radius: 8px; padding: 0.5rem 0.75rem;">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown text-center">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"
                                                data-bs-boundary="viewport" aria-expanded="false"
                                                style="border-radius: 8px; border: 1px solid #e0cec7; font-size: 1.2rem; line-height: 1; padding: 0.25rem 0.5rem;">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                style="border-radius: 12px; border: 1px solid #e0cec7; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                <li>
                                                    <a href="{{ route('admin.suppliers.pre_orders.show', $order->id) }}" class="dropdown-item py-2" style="border-radius: 8px;">
                                                        <i class="fa-solid fa-eye me-1 text-info"></i> {{ __('admin.view_details') }}
                                                    </a>
                                                </li>
                                                @if($order->status !== 'received' && $order->status !== 'cancelled')
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li class="dropdown-header">{{ __('admin.update_status') ?? 'Update Status' }}</li>
                                                <li>
                                                    <form action="{{ route('admin.suppliers.pre_orders.update_status', $order->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="received">
                                                        <button type="submit" class="dropdown-item py-2 text-success" onclick="return confirm('Tandai sebagai diterima dan update stok?')">
                                                            <i class="fa-solid fa-check-double me-1"></i> {{ __('admin.mark_received') ?? 'Tandai Diterima' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.suppliers.pre_orders.update_status', $order->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="status" value="cancelled">
                                                        <button type="submit" class="dropdown-item py-2 text-danger" onclick="return confirm('Batalkan pesanan ini?')">
                                                            <i class="fa-solid fa-xmark me-1"></i> {{ __('admin.cancel_order') ?? 'Batalkan' }}
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div style="font-size: 4rem; opacity: 0.2;"><i class="fa-solid fa-receipt"></i></div>
                                        <p class="text-muted mb-0">{{ __('admin.no_pre_orders_yet') ?? 'Belum ada pre-order' }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($preOrders->hasPages())
                <div class="card-footer border-0 d-flex justify-content-end">
                    {{ $preOrders->links('vendor.pagination.custom-brown') }}
                </div>
            @endif
        </div>
    </div>
@endsection
