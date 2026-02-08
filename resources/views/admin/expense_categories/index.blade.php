@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1" style="color: #6f5849;">
                    <i class="fa-solid fa-tags me-2"></i>{{ __('admin.expense_categories') }}
                </h4>
                <p class="text-muted mb-0 small">{{ __('admin.manage_expense_categories_subtitle') }}</p>
            </div>
            <button class="btn btn-primary shadow-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#addCategoryModal"
                style="background: #6f5849; border: none; border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600; height: fit-content;">
                <i class="fa-solid fa-plus me-2"></i> {{ __('admin.add_category') }}
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm"
                style="border-radius: 12px; background-color: #f0fdf4; color: #16a34a;">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm"
                style="border-radius: 12px; background-color: #fef2f2; color: #dc2626;">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Categories Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: #fdf8f6;">
                                <tr>
                                    <th class="px-4 py-3 border-0" style="color: #6f5849; font-weight: 600; width: 30%;">
                                        {{ __('admin.category_name') }}
                                    </th>
                                    <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">
                                        {{ __('admin.description') }}
                                    </th>
                                    <th class="py-3 border-0" style="color: #6f5849; font-weight: 600; width: 15%;">
                                        {{ __('admin.usage_count') }}
                                    </th>
                                    <th class="py-3 border-0 text-end px-4"
                                        style="color: #6f5849; font-weight: 600; width: 15%;">{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                    <tr style="border-bottom: 1px solid #f2e8e5;">
                                        <td class="px-4 py-3">
                                            <div class="fw-bold" style="color: #2c3e50;">{{ $category->name }}</div>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-muted small">
                                                {{ $category->description ?: '-' }}
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge rounded-pill"
                                                style="background: #fdf2f2; color: #dc2626; border: 1px solid #fecaca;">
                                                {{ $category->expenses_count }} {{ __('admin.expenses') }}
                                            </span>
                                        </td>
                                        <td class="py-3 text-end px-4">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm shadow-sm" type="button"
                                                    data-bs-toggle="dropdown" style="border-radius: 8px;">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                                                    style="border-radius: 12px;">
                                                    <li>
                                                        <button class="dropdown-item py-2" data-bs-toggle="modal"
                                                            data-bs-target="#editCategoryModal" data-category='@json($category)'>
                                                            <i class="fa-solid fa-pen me-2 text-primary"></i>
                                                            {{ __('common.edit') }}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider opacity-50">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.expense-categories.delete', $category->id) }}"
                                                            method="POST" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item py-2 text-danger btn-delete"
                                                                data-in-use="{{ $category->expenses_count > 0 ? 'true' : 'false' }}"
                                                                title="{{ $category->expenses_count > 0 ? __('admin.cannot_delete_category_with_expenses') : '' }}">
                                                                <i class="fa-solid fa-trash me-2"></i> {{ __('common.delete') }}
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
                        <div class="mb-3" style="font-size: 4rem; opacity: 0.15; color: #6f5849;">
                            <i class="fa-solid fa-tags"></i>
                        </div>
                        <h5 class="text-muted">{{ __('admin.no_expense_categories_found') }}</h5>
                        <button class="btn btn-brown mt-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fa-solid fa-plus me-1"></i> {{ __('admin.add_first_expense_category') }}
                        </button>
                    </div>
                @endif
            </div>
            @if($categories->hasPages())
                <div class="card-footer border-0 d-flex justify-content-end py-3 px-4">
                    {{ $categories->links('vendor.pagination.custom-brown') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;">
                        <i class="fa-solid fa-circle-plus me-2"></i>{{ __('admin.add_category') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.expense-categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"
                                style="color: #85695a;">{{ __('admin.category_name') }}</label>
                            <input type="text" name="name" class="form-control custom-input"
                                placeholder="{{ __('admin.category_name_placeholder') }}" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold"
                                style="color: #85695a;">{{ __('admin.description') }}</label>
                            <textarea name="description" class="form-control custom-input" rows="3"
                                placeholder="{{ __('admin.category_description_placeholder') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4 d-flex gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                            style="border-radius: 10px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-brown px-4 shadow-sm" style="border-radius: 10px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('common.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;">
                        <i class="fa-solid fa-pen-to-square me-2"></i>{{ __('admin.edit_category') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold"
                                style="color: #85695a;">{{ __('admin.category_name') }}</label>
                            <input type="text" name="name" id="edit_name" class="form-control custom-input" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold"
                                style="color: #85695a;">{{ __('admin.description') }}</label>
                            <textarea name="description" id="edit_description" class="form-control custom-input"
                                rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4 d-flex gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                            style="border-radius: 10px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-brown px-4 shadow-sm" style="border-radius: 10px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('admin.update_category') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .btn-brown {
            background: #6f5849;
            color: white;
            border: none;
        }

        .btn-brown:hover {
            color: white;
            opacity: 0.9;
        }

        .custom-input {
            border-radius: 12px;
            border: 2px solid #f2e8e5;
            padding: 0.6rem 1rem;
        }

        .custom-input:focus {
            border-color: #d4c4bb;
            box-shadow: none;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editModal = document.getElementById('editCategoryModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const category = JSON.parse(button.getAttribute('data-category'));

                    const form = document.getElementById('editCategoryForm');
                    form.action = `/admin/expense-categories/${category.id}`;

                    document.getElementById('edit_name').value = category.name;
                    document.getElementById('edit_description').value = category.description || '';
                });
            }

            // Handle delete confirmation
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    if (this.dataset.inUse === 'true') {
                        if (typeof showToast === 'function') {
                            showToast('error', "{{ __('admin.category_has_expenses_error') }}");
                        } else {
                            alert("{{ __('admin.category_has_expenses_error') }}");
                        }
                        return;
                    }

                    const form = this.closest('form');
                    confirmAction({
                        text: "{{ __('admin.delete_expense_category_confirm') }}",
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