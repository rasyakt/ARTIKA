@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col">
                <h2 class="fw-bold mb-1" style="color: #6f5849;"><i
                        class="fa-solid fa-rotate-left me-2"></i>{{ __('admin.returns_management') }}</h2>
                <p class="text-muted small mb-0">{{ __('admin.manage_returns_subtitle') }}</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <form action="{{ route('admin.returns.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-brown">{{ __('admin.start_date') }}</label>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}"
                            style="border-radius: 10px;">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-brown">{{ __('admin.end_date') }}</label>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}"
                            style="border-radius: 10px;">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-brown px-4" style="border-radius: 10px;">
                            <i class="fa-solid fa-filter me-2"></i>{{ __('admin.apply_filter') }}
                        </button>
                        <a href="{{ route('admin.returns.index') }}" class="btn btn-outline-brown px-4 ms-2"
                            style="border-radius: 10px;">
                            {{ __('pos.clear_filter') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Returns Table -->
        <div class="card border-0 shadow-sm" style="border-radius: 20px;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">{{ __('admin.return_no') }}
                                </th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.invoice') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.return_date') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.cashier') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.total_refund') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('admin.status') }}</th>
                                <th class="border-0 fw-semibold text-centerpe-4" style="color: #6f5849;">
                                    {{ __('admin.action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($returns as $return)
                                <tr>
                                    <td class="ps-4 fw-bold text-brown">{{ $return->return_no }}</td>
                                    <td>
                                        <span class="bg-brown-soft text-brown fw-medium">
                                            {{ $return->transaction->invoice_no }}
                                        </span>
                                    </td>
                                    <td class="small">{{ $return->created_at->format('d M Y, H:i') }}</td>
                                    <td>{{ $return->user->name ?? 'System' }}</td>
                                    <td class="fw-bold text-danger">Rp {{ number_format($return->total_refund, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $return->status === 'approved' ? 'bg-success' : ($return->status === 'pending' ? 'bg-warning' : 'bg-danger') }} rounded-pill px-3">
                                            {{ strtoupper($return->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button type="button" class="btn btn-sm btn-outline-brown btn-view-return"
                                            data-id="{{ $return->id }}" style="border-radius: 8px;">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-rotate-left fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0">{{ __('admin.no_returns_found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($returns->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $returns->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Return Detail Modal -->
    <div class="modal fade" id="returnDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-rotate-left me-2"></i>Detail Retur <span id="dt-return-no"
                            class="text-muted small"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4 bg-light p-3 rounded-16">
                        <div class="row">
                            <div class="col-6">
                                <label class="text-muted small d-block">Tanggal</label>
                                <span id="dt-date" class="fw-bold"></span>
                            </div>
                            <div class="col-6">
                                <label class="text-muted small d-block">Diproses Oleh</label>
                                <span id="dt-user" class="fw-bold"></span>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 text-brown">Item yang Dikembalikan</h6>
                    <div id="dt-items-container" class="mb-4">
                        <!-- Items injected here -->
                    </div>

                    <div class="p-3 bg-danger-soft rounded-16 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-danger">Total Refund</span>
                            <h4 id="dt-total-refund" class="fw-bold text-danger mb-0"></h4>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small d-block">Alasan</label>
                        <p id="dt-reason" class="fw-bold text-brown mb-0"></p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-brown px-4" style="border-radius: 10px;"
                        data-bs-dismiss="modal">{{ __('common.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const returnDetailModalEl = document.getElementById('returnDetailModal');
                const itemsContainer = document.getElementById('dt-items-container');
                const formatIDR = (num) => new Intl.NumberFormat('id-ID').format(num);

                document.querySelectorAll('.btn-view-return').forEach(button => {
                    button.addEventListener('click', function () {
                        const id = this.getAttribute('data-id');

                        // Show loading state
                        itemsContainer.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin me-2"></i>Loading...</div>';
                        bootstrap.Modal.getOrCreateInstance(returnDetailModalEl).show();

                        fetch(`/admin/returns/${id}`)
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('dt-return-no').textContent = data.return_no;
                                document.getElementById('dt-date').textContent = new Date(data.created_at).toLocaleString('id-ID');
                                document.getElementById('dt-user').textContent = data.user ? data.user.name : 'System';
                                document.getElementById('dt-total-refund').textContent = 'Rp ' + formatIDR(data.total_refund);
                                document.getElementById('dt-reason').textContent = data.reason || '-';

                                let itemsHtml = '';
                                if (data.items && Array.isArray(data.items)) {
                                    data.items.forEach(item => {
                                        itemsHtml += `
                                                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                                                        <div>
                                                            <div class="fw-bold">${item.product_name}</div>
                                                            <div class="small text-muted">${formatIDR(item.price)} x ${item.quantity}</div>
                                                        </div>
                                                        <div class="fw-bold">Rp ${formatIDR(item.subtotal)}</div>
                                                    </div>
                                                `;
                                    });
                                }
                                itemsContainer.innerHTML = itemsHtml || '<div class="text-center py-3 text-muted">Tidak ada item</div>';
                            })
                            .catch(error => {
                                console.error('Error fetching return details:', error);
                                itemsContainer.innerHTML = '<div class="text-center py-3 text-danger">Gagal memuat detail</div>';
                            });
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .bg-brown-soft {
                background-color: rgba(111, 88, 73, 0.1);
            }

            .bg-danger-soft {
                background-color: rgba(220, 53, 69, 0.1);
            }

            .rounded-16 {
                border-radius: 16px;
            }

            .btn-outline-brown {
                color: #6f5849;
                border-color: #6f5849;
            }

            .btn-outline-brown:hover {
                background-color: #6f5849;
                color: white;
            }

            .btn-brown {
                background-color: #6f5849;
                color: white;
            }

            .btn-brown:hover {
                background-color: #5a473b;
                color: white;
            }

            .text-brown {
                color: #6f5849;
            }
        </style>
    @endpush
@endsection