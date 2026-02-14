@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <h4 class="fw-bold mb-0" style="color: #6f5849;">
                <i class="fa-solid fa-scale-balanced me-2"></i>{{ __('admin.unit_management') ?? 'Manajemen Satuan' }}
            </h4>
            <button class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#addUnitModal"
                style="border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600; height: fit-content;">
                <i class="fa-solid fa-plus me-2"></i> {{ __('admin.add_unit') ?? 'Tambah Satuan' }}
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
                <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        @endif

        <!-- Units Table -->
        <div class="card shadow-sm border-0" style="border-radius: 16px;">
            <div class="card-body p-0">
                @if($units->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #fdf8f6;">
                                <tr>
                                    <th class="px-4 py-3" style="border: none; color: #6f5849; font-weight: 600;">#</th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('admin.unit_name') ?? 'Nama Satuan' }}
                                    </th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('admin.short_name') ?? 'Singkatan' }}
                                    </th>
                                    <th class="py-3 text-end px-4" style="border: none; color: #6f5849; font-weight: 600;">
                                        {{ __('common.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($units as $index => $unit)
                                    <tr style="border-bottom: 1px solid #f2e8e5;">
                                        <td class="px-4 py-3 align-middle" style="color: #6c757d;">
                                            {{ ($units->currentPage() - 1) * $units->perPage() + $loop->iteration }}</td>
                                        <td class="py-3 align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3"
                                                    style="width: 40px; height: 40px; background: #fdf8f6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid fa-box" style="color: #6f5849; font-size: 1.2rem;"></i>
                                                </div>
                                                <span class="fw-semibold" style="color: #2c3e50;">{{ $unit->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 align-middle text-muted">
                                            {{ $unit->short_name ?: '-' }}
                                        </td>
                                        <td class="py-3 align-middle text-end px-4">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-light" type="button" data-bs-toggle="dropdown"
                                                    style="border: 1px solid #f2e8e5; border-radius: 8px; padding: 0.35rem 0.7rem;">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                                                    style="border-radius: 12px; min-width: 150px;">
                                                    <li>
                                                        <button class="dropdown-item py-2" data-bs-toggle="modal"
                                                            data-bs-target="#editUnitModal" data-unit-id="{{ $unit->id }}"
                                                            data-unit-name="{{ $unit->name }}"
                                                            data-unit-short="{{ $unit->short_name }}">
                                                            <i class="fa-solid fa-pen me-2 text-primary"></i>{{ __('common.edit') }}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider my-1">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST"
                                                            class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item py-2 text-danger">
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
                        <div style="font-size: 4rem; opacity: 0.2; color: #6f5849;">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <p class="text-muted mb-3">Belum ada data satuan.</p>
                        <button class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal"
                            data-bs-target="#addUnitModal"
                            style="border-radius: 10px; padding: 0.6rem 1.5rem; font-weight: 600;">
                            <i class="fa-solid fa-plus me-2"></i> {{ __('admin.add_unit') ?? 'Tambah Satuan' }}
                        </button>
                    </div>
                @endif
            </div>
            @if($units->hasPages())
                <div class="card-footer border-0 bg-white d-flex justify-content-end py-3"
                    style="border-radius: 0 0 16px 16px;">
                    {{ $units->links('vendor.pagination.bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Unit Modal -->
    <div class="modal fade" id="addUnitModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: #e8e7e7ff;">
                        <i class="fa-solid fa-plus-circle me-1"></i> {{ __('admin.add_unit') ?? 'Tambah Satuan' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.units.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"
                                style="color: #6f5849;">{{ __('admin.unit_name') ?? 'Nama Satuan' }} *</label>
                            <input type="text" class="form-control" name="name" required
                                placeholder="Contoh: Box, Kardus, Pcs"
                                style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold"
                                style="color: #6f5849;">{{ __('admin.short_name') ?? 'Singkatan (Opsional)' }}</label>
                            <input type="text" class="form-control" name="short_name" placeholder="Contoh: box, krd, pcs"
                                style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                            style="border-radius: 10px; padding: 0.6rem 1.25rem;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-brown px-4"
                            style="border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Unit Modal -->
    <div class="modal fade" id="editUnitModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: #ddddddff;">
                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('admin.edit_unit') ?? 'Ubah Satuan' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUnitForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"
                                style="color: #6f5849;">{{ __('admin.unit_name') ?? 'Nama Satuan' }} *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold"
                                style="color: #6f5849;">{{ __('admin.short_name') ?? 'Singkatan (Opsional)' }}</label>
                            <input type="text" class="form-control" id="edit_short_name" name="short_name"
                                style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 0.75rem 1rem;">
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 px-4">
                        <button type="button" class="btn btn-warning px-4" data-bs-dismiss="modal"
                            style="border-radius: 10px; padding: 0.6rem 1.25rem;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary px-4"
                            style="border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('common.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editUnitModal = document.getElementById('editUnitModal');
            if (editUnitModal) {
                editUnitModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-unit-id');
                    var name = button.getAttribute('data-unit-name');
                    var short = button.getAttribute('data-unit-short');

                    var modalForm = editUnitModal.querySelector('#editUnitForm');
                    var modalNameInput = editUnitModal.querySelector('#edit_name');
                    var modalShortInput = editUnitModal.querySelector('#edit_short_name');

                    modalForm.action = '/admin/units/' + id;
                    modalNameInput.value = name;
                    modalShortInput.value = short;
                });
            }
        });
    </script>
@endsection