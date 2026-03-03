@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <h4 class="fw-bold mb-0" style="color: var(--color-primary-dark);">
                <i class="fa-solid fa-id-card me-2"></i>Pengaturan Jenis ID
            </h4>
            <button class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#addTypeModal"
                style="background: var(--color-primary-dark); border: none; border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600; height: fit-content;">
                <i class="fa-solid fa-plus me-2"></i> Tambah Jenis ID
            </button>
        </div>

        <!-- Identity Types Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if($types->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: var(--gray-50);">
                                <tr>
                                    <th class="px-4 py-3"
                                        style="border: none; color: var(--color-primary-dark); font-weight: 600;">#</th>
                                    <th class="py-3" style="border: none; color: var(--color-primary-dark); font-weight: 600;">
                                        Label / Nama Tampilan
                                    </th>
                                    <th class="py-3" style="border: none; color: var(--color-primary-dark); font-weight: 600;">
                                        Kode System
                                    </th>
                                    <th class="py-3" style="border: none; color: var(--color-primary-dark); font-weight: 600;">
                                        Status
                                    </th>
                                    <th class="py-3 text-end px-4"
                                        style="border: none; color: var(--color-primary-dark); font-weight: 600;">
                                        {{ __('common.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($types as $index => $type)
                                    <tr style="border-bottom: 1px solid #f0f0f0;">
                                        <td class="px-4 py-3 align-middle" style="color: var(--gray-500);">{{ $index + 1 }}</td>
                                        <td class="py-3 align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3"
                                                    style="width: 40px; height: 40px; background: var(--brown-100); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid fa-id-card"
                                                        style="color: var(--color-primary-dark); font-size: 1.2rem;"></i>
                                                </div>
                                                <span class="fw-semibold" style="color: var(--gray-800);">{{ $type->label }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 align-middle">
                                            <code
                                                style="background: var(--gray-100); padding: 0.2rem 0.5rem; border-radius: 4px;">{{ $type->name }}</code>
                                        </td>
                                        <td class="py-3 align-middle">
                                            @if($type->is_active)
                                                <span class="badge bg-success-subtle text-success px-2 py-1">Aktif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger px-2 py-1">Non-aktif</span>
                                            @endif
                                        </td>
                                        <td class="py-3 align-middle text-end px-4">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-light" type="button" data-bs-toggle="dropdown"
                                                    data-bs-boundary="viewport"
                                                    style="border: 1px solid var(--gray-200); border-radius: 8px; padding: 0.35rem 0.7rem;">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end"
                                                    style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                    <li>
                                                        <button class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#editTypeModal" data-type-id="{{ $type->id }}"
                                                            data-type-name="{{ $type->name }}" data-type-label="{{ $type->label }}"
                                                            data-type-active="{{ $type->is_active ? '1' : '0' }}">
                                                            <i class="fa-solid fa-pen me-2"></i>{{ __('common.edit') }}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider my-1">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('superadmin.identity-types.destroy', $type->id) }}"
                                                            method="POST" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-danger btn-delete">
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
                        <div style="font-size: 4rem; opacity: 0.2; color: var(--color-primary-dark);">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <p class="text-muted mb-0">Belum ada jenis ID yang ditambahkan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Type Modal -->
    <div class="modal fade" id="addTypeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid var(--brown-100);">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-1"></i> Tambah Jenis ID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('superadmin.identity-types.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="label" class="form-label fw-semibold"
                                style="color: var(--color-primary-dark);">Label Nama *</label>
                            <input type="text" class="form-control" name="label" required
                                placeholder="Contoh: NIS, NISN, NIK"
                                style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                            <small class="text-muted">Nama yang akan muncul di form pendaftaran user.</small>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold" style="color: var(--color-primary-dark);">Kode
                                System (Unik) *</label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="Contoh: nis, nisn, nik"
                                style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                            <small class="text-muted">Gunakan huruf kecil tanpa spasi.</small>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid var(--brown-100);">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal"
                            style="border-radius: 10px; padding: 0.6rem 1.25rem;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary px-4"
                            style="background: var(--color-primary-dark); border: none; border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Type Modal -->
    <div class="modal fade" id="editTypeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid var(--brown-100);">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen me-1"></i> Edit Jenis ID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editTypeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="edit_label" class="form-label fw-semibold"
                                style="color: var(--color-primary-dark);">Label Nama *</label>
                            <input type="text" class="form-control" id="edit_label" name="label" required
                                style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label for="edit_name" class="form-label fw-semibold"
                                style="color: var(--color-primary-dark);">Kode System *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active"
                                    value="1">
                                <label class="form-check-label fw-semibold" for="edit_is_active">Status Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid var(--brown-100);">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal"
                            style="border-radius: 10px; padding: 0.6rem 1.25rem;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary px-4"
                            style="background: var(--color-primary-dark); border: none; border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('common.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editTypeModal = document.getElementById('editTypeModal');
            if (editTypeModal) {
                editTypeModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-type-id');
                    var name = button.getAttribute('data-type-name');
                    var label = button.getAttribute('data-type-label');
                    var active = button.getAttribute('data-type-active');

                    var modalForm = editTypeModal.querySelector('#editTypeForm');
                    modalForm.action = '/superadmin/identity-types/' + id;

                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_label').value = label;
                    document.getElementById('edit_is_active').checked = active === '1';
                });
            }

            // Handle delete confirmation
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    confirmAction({
                        text: "Yakin ingin menghapus jenis ID ini?",
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