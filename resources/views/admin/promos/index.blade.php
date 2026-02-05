@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1" style="color: #6f5849;">
                    <i class="fa-solid fa-tags me-2"></i>{{ __('admin.promos') }}
                </h4>
                <p class="text-muted mb-0">{{ __('admin.manage_promos_subtitle') }}</p>
            </div>
            <button class="btn shadow-sm" data-bs-toggle="modal" data-bs-target="#addPromoModal"
                style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); color: white; border: none; border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600; transition: all 0.3s;">
                <i class="fa-solid fa-plus me-1"></i> {{ __('admin.add_promo') }}
            </button>
        </div>

        <!-- Promos Table -->
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-body p-0">
                @if($promos->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #fdf8f6;">
                                <tr>
                                    <th class="px-4 py-3" style="border: none; color: #6f5849; font-weight: 600;">#</th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('admin.promo_name') }}</th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('admin.type') }}</th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('admin.value') }}</th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('admin.active_periods') }}</th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('admin.status') }}</th>
                                    <th class="py-3 text-end px-4" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promos as $index => $promo)
                                    <tr style="border-bottom: 1px solid #f2e8e5; vertical-align: middle;">
                                        <td class="px-4 py-3 text-muted">{{ $promos->firstItem() + $index }}</td>
                                        <td class="py-3">
                                            <div class="fw-bold" style="color: #4b382f;">{{ $promo->name }}</div>
                                            @if($promo->product)
                                                <small class="text-muted"><i class="fa-solid fa-box me-1"></i>
                                                    {{ $promo->product->name }}</small>
                                            @elseif($promo->category)
                                                <small class="text-muted"><i class="fa-solid fa-folder me-1"></i>
                                                    {{ $promo->category->name }}</small>
                                            @else
                                                <small class="text-muted"><i class="fa-solid fa-store me-1"></i> Global</small>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            <span class="badge" style="background: #f2e8e5; color: #6f5849; font-weight: 500;">
                                                {{ $promo->type === 'percentage' ? __('admin.percentage') : __('admin.fixed_amount') }}
                                            </span>
                                        </td>
                                        <td class="py-3 fw-bold" style="color: #85695a;">
                                            @if($promo->type === 'percentage')
                                                {{ number_format($promo->value, 0) }}%
                                            @else
                                                Rp {{ number_format($promo->value, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td class="py-3 small">
                                            <div>{{ $promo->start_date->format('d M Y') }}</div>
                                            <div class="text-muted">{{ $promo->end_date->format('d M Y') }}</div>
                                        </td>
                                        <td class="py-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input btn-toggle-status" type="checkbox"
                                                    data-id="{{ $promo->id }}" {{ $promo->is_active ? 'checked' : '' }}
                                                    style="cursor: pointer;">
                                            </div>
                                        </td>
                                        <td class="py-3 text-end px-4">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm shadow-sm" type="button"
                                                    data-bs-toggle="dropdown" data-bs-boundary="viewport"
                                                    style="border: 1px solid #f2e8e5; border-radius: 8px;">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm"
                                                    style="border-radius: 12px; border: 1px solid #f2e8e5;">
                                                    <li>
                                                        <button class="dropdown-item py-2 edit-promo-btn"
                                                            data-promo="{{ json_encode($promo) }}">
                                                            <i class="fa-solid fa-pen me-2 text-primary"></i>{{ __('common.edit') }}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider opacity-50">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.promos.delete', $promo->id) }}" method="POST"
                                                            class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item py-2 text-danger btn-delete">
                                                                <i class="fa-solid fa-trash me-2"></i>{{ __('common.delete') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3" style="font-size: 4rem; opacity: 0.2; color: #6f5849;">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <h5 class="text-muted mb-3">{{ __('admin.no_promos_found') }}</h5>
                        <button class="btn shadow-sm" data-bs-toggle="modal" data-bs-target="#addPromoModal"
                            style="background: #6f5849; color: white; border: none; border-radius: 10px; padding: 0.6rem 1.5rem; font-weight: 600;">
                            <i class="fa-solid fa-plus me-1"></i> {{ __('admin.add_first_promo') }}
                        </button>
                    </div>
                @endif
            </div>
            @if($promos->hasPages())
                <div class="card-footer bg-white border-0 d-flex justify-content-end py-3">
                    {{ $promos->links('vendor.pagination.custom-brown') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Promo Modal -->
    <div class="modal fade" id="addPromoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
                <div class="modal-header border-bottom-0 p-4">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i
                            class="fa-solid fa-plus me-2"></i>{{ __('admin.add_promo') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.promos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.promo_name') }}
                                    *</label>
                                <input type="text" name="name" class="form-control" required
                                    placeholder="Contoh: Diskon Awal Tahun"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.promo_type') }}
                                    *</label>
                                <select name="type" class="form-select" required
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                                    <option value="percentage">{{ __('admin.percentage') }}</option>
                                    <option value="fixed">{{ __('admin.fixed_amount') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.promo_value') }}
                                    *</label>
                                <input type="number" name="value" class="form-control" required step="0.01" min="0"
                                    placeholder="0"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.start_date') }}
                                    *</label>
                                <input type="date" name="start_date" class="form-control" required
                                    value="{{ date('Y-m-d') }}"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.end_date') }}
                                    *</label>
                                <input type="date" name="end_date" class="form-control" required
                                    value="{{ date('Y-m-d', strtotime('+1 month')) }}"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">Target Produk
                                    (Opsional)</label>
                                <select name="product_id" class="form-select select2"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                                    <option value="">-- Semua Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->barcode }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">Target Kategori
                                    (Opsional)</label>
                                <select name="category_id" class="form-select"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                                    <option value="">-- Semua Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">Min. Pembelian (Rp)</label>
                                <input type="number" name="min_purchase" class="form-control" step="0.01" min="0" value="0"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked
                                        id="isActiveAdd">
                                    <label class="form-check-label fw-semibold ms-2" for="isActiveAdd">Aktifkan
                                        Sekarang</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 border-0 fw-bold"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn px-4 py-2 fw-bold"
                            style="background: #6f5849; color: white; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(111, 88, 73, 0.2);">
                            Simpan Promo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Promo Modal -->
    <div class="modal fade" id="editPromoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg" style="border-radius: 20px; border: none;">
                <div class="modal-header border-bottom-0 p-4">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i
                            class="fa-solid fa-pen me-2"></i>{{ __('admin.edit_promo') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPromoForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.promo_name') }}
                                    *</label>
                                <input type="text" name="name" id="edit_name" class="form-control" required
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.promo_type') }}
                                    *</label>
                                <select name="type" id="edit_type" class="form-select" required
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                                    <option value="percentage">{{ __('admin.percentage') }}</option>
                                    <option value="fixed">{{ __('admin.fixed_amount') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.promo_value') }}
                                    *</label>
                                <input type="number" name="value" id="edit_value" class="form-control" required step="0.01"
                                    min="0" style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.start_date') }}
                                    *</label>
                                <input type="date" name="start_date" id="edit_start_date" class="form-control" required
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('admin.end_date') }}
                                    *</label>
                                <input type="date" name="end_date" id="edit_end_date" class="form-control" required
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">Target Produk</label>
                                <select name="product_id" id="edit_product_id" class="form-select"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                                    <option value="">-- Semua Produk --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">Target Kategori</label>
                                <select name="category_id" id="edit_category_id" class="form-select"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                                    <option value="">-- Semua Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color: #6f5849;">Min. Pembelian (Rp)</label>
                                <input type="number" name="min_purchase" id="edit_min_purchase" class="form-control"
                                    step="0.01" min="0"
                                    style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        id="edit_is_active">
                                    <label class="form-check-label fw-semibold ms-2" for="edit_is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 p-4 pt-0">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2 border-0 fw-bold"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn px-4 py-2 fw-bold"
                            style="background: #6f5849; color: white; border-radius: 10px; box-shadow: 0 4px 6px -1px rgba(111, 88, 73, 0.2);">
                            Perbarui Promo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Edit Promo Logic
            const editPromoModal = new bootstrap.Modal(document.getElementById('editPromoModal'));
            const editForm = document.getElementById('editPromoForm');

            document.querySelectorAll('.edit-promo-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const promo = JSON.parse(this.dataset.promo);

                    editForm.action = `/admin/promos/${promo.id}`;
                    document.getElementById('edit_name').value = promo.name;
                    document.getElementById('edit_type').value = promo.type;
                    document.getElementById('edit_value').value = promo.value;
                    document.getElementById('edit_start_date').value = promo.start_date.split('T')[0];
                    document.getElementById('edit_end_date').value = promo.end_date.split('T')[0];
                    document.getElementById('edit_product_id').value = promo.product_id || '';
                    document.getElementById('edit_category_id').value = promo.category_id || '';
                    document.getElementById('edit_min_purchase').value = promo.min_purchase;
                    document.getElementById('edit_is_active').checked = !!promo.is_active;

                    editPromoModal.show();
                });
            });

            // Toggle Status Logic
            document.querySelectorAll('.btn-toggle-status').forEach(toggle => {
                toggle.addEventListener('change', function () {
                    const id = this.dataset.id;
                    fetch(`/admin/promos/${id}/toggle`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', 'Status promo diperbarui');
                            }
                        });
                });
            });

            // Delete Logic
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    confirmAction({
                        text: "{{ __('admin.delete_promo_confirm') ?? 'Hapus program promo ini?' }}",
                        confirmButtonText: "{{ __('common.delete') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush