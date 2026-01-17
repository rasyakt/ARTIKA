@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;"><i
                        class="fa-solid fa-truck me-2"></i>{{ __('admin.supplier_management') }}</h2>
                <p class="text-muted mb-0">{{ __('admin.manage_suppliers_contacts') }}</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal"
                style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600;">
                <span style="font-size: 1.25rem;">+</span> {{ __('admin.add_supplier') }}
            </button>
        </div>


        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">{{ __('common.supplier') }}
                                </th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.phone') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.email') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.address') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold" style="color: #6f5849;">{{ $supplier->name }}</div>
                                        <small class="text-muted">{{ $supplier->address ?? '-' }}</small>
                                    </td>
                                    <td>{{ $supplier->phone ?? '-' }}</td>
                                    <td>{{ $supplier->email ?? '-' }}</td>
                                    <td><small class="text-muted">{{ Str::limit($supplier->address ?? '-', 40) }}</small></td>
                                    <td>
                                        <div class="dropdown text-center">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                style="border-radius: 8px; border: 1px solid #e0cec7; font-size: 1.2rem; line-height: 1; padding: 0.25rem 0.5rem;">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                style="border-radius: 12px; border: 1px solid #e0cec7; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                <li>
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editSupplierModal"
                                                        onclick='editSupplier(@json($supplier))'
                                                        style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                        <i class="fa-solid fa-pen me-1"></i> {{ __('admin.edit_supplier') }}
                                                    </button>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.suppliers.delete', $supplier->id) }}"
                                                        method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item text-danger btn-delete"
                                                            style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                            <i class="fa-solid fa-trash me-1"></i>
                                                            {{ __('admin.delete_supplier') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div style="font-size: 4rem; opacity: 0.2;"><i class="fa-solid fa-truck"></i></div>
                                        <p class="text-muted mb-0">{{ __('admin.no_suppliers_yet') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($suppliers->hasPages())
                <div class="card-footer bg-white border-0 d-flex justify-content-end">
                    {{ $suppliers->links('vendor.pagination.no-prevnext') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i class="fa-solid fa-plus me-1"></i>
                        {{ __('admin.add_new_supplier') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.suppliers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.name') }} *</label>
                            <input type="text" class="form-control" name="name" required
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.phone') }}</label>
                            <input type="text" class="form-control" name="phone"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.email') }}</label>
                            <input type="email" class="form-control" name="email"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.address') }}</label>
                            <textarea class="form-control" name="address" rows="2"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;"><i
                                class="fa-solid fa-floppy-disk me-1"></i>
                            {{ __('common.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Supplier Modal -->
    <div class="modal fade" id="editSupplierModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i class="fa-solid fa-pen me-1"></i>
                        {{ __('admin.edit_supplier') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editSupplierForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.name') }} *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.phone') }}</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.email') }}</label>
                            <input type="email" class="form-control" id="edit_email" name="email"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.address') }}</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="2"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;"><i
                                class="fa-solid fa-floppy-disk me-1"></i>
                            {{ __('common.update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editSupplier(supplier) {
            document.getElementById('edit_name').value = supplier.name;
            document.getElementById('edit_phone').value = supplier.phone || '';
            document.getElementById('edit_email').value = supplier.email || '';
            document.getElementById('edit_address').value = supplier.address || '';
            document.getElementById('editSupplierForm').action = `/admin/suppliers/${supplier.id}`;
            new bootstrap.Modal(document.getElementById('editSupplierModal')).show();
        }

        // Handle delete confirmation
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');
                    confirmAction({
                        text: "{{ __('admin.delete_supplier_confirm') }}",
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
@endsection