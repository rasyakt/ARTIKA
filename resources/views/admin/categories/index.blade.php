@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0" style="color: #6f5849;">
                <i class="fa-solid fa-folder me-2"></i>Categories
            </h4>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal"
                style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 8px; padding: 0.5rem 1rem;">
                <i class="fa-solid fa-plus me-1"></i> Add Category
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border-radius: 8px; border: none;">
                <i class="fa-solid fa-circle-check me-1"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 8px; border: none;">
                <i class="fa-solid fa-circle-exclamation me-1"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Categories Table -->
        <div class="card shadow-sm" style="border-radius: 12px; border: none;">
            <div class="card-body p-0">
                @if($categories->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #f8f9fa;">
                                <tr>
                                    <th class="px-4 py-3" style="border: none; color: #6f5849; font-weight: 600;">#</th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">Category Name</th>
                                    <th class="py-3" style="border: none; color: #6f5849; font-weight: 600;">Products</th>
                                    <th class="py-3 text-end px-4" style="border: none; color: #6f5849; font-weight: 600;">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $category)
                                    <tr style="border-bottom: 1px solid #f0f0f0;">
                                        <td class="px-4 py-3 align-middle" style="color: #6c757d;">{{ $index + 1 }}</td>
                                        <td class="py-3 align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3"
                                                    style="width: 40px; height: 40px; background: linear-gradient(135deg, #f2e8e5 0%, #e0cec7 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid fa-folder" style="color: #6f5849; font-size: 1.2rem;"></i>
                                                </div>
                                                <span class="fw-semibold" style="color: #2c3e50;">{{ $category->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 align-middle">
                                            <span class="badge"
                                                style="background: #e0cec7; color: #6f5849; padding: 0.4rem 0.8rem; border-radius: 6px;">
                                                {{ $category->products_count }} item{{ $category->products_count != 1 ? 's' : '' }}
                                            </span>
                                        </td>
                                        <td class="py-3 align-middle text-end px-4">
                                            <div class="dropdown d-inline-block">
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"
                                                    style="border: 1px solid #dee2e6; border-radius: 6px; padding: 0.25rem 0.5rem;">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end"
                                                    style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                    <li>
                                                        <button class="dropdown-item" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editCategoryModal"
                                                            data-category-id="{{ $category->id }}"
                                                            data-category-name="{{ $category->name }}">
                                                            <i class="fa-solid fa-pen me-2"></i>Edit
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider my-1"></li>
                                                    <li>
                                                        <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fa-solid fa-trash me-2"></i>Delete
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
                            <i class="fa-solid fa-folder"></i>
                        </div>
                        <p class="text-muted mb-3">No categories yet</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 8px;">
                            <i class="fa-solid fa-plus me-1"></i> Add Your First Category
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i class="fa-solid fa-plus me-1"></i> Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold" style="color: #6f5849;">Category Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="e.g., Electronics"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Save Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i class="fa-solid fa-pen me-1"></i> Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCategoryForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label fw-semibold" style="color: #6f5849;">Category Name *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editCategoryModal = document.getElementById('editCategoryModal');
            if (editCategoryModal) {
                editCategoryModal.addEventListener('show.bs.modal', function (event) {
                    // Button that triggered the modal
                    var button = event.relatedTarget;
                    
                    // Extract info from data-* attributes
                    var id = button.getAttribute('data-category-id');
                    var name = button.getAttribute('data-category-name');
                    
                    // Update the modal's content.
                    var modalForm = editCategoryModal.querySelector('#editCategoryForm');
                    var modalNameInput = editCategoryModal.querySelector('#edit_name');
                    
                    modalForm.action = '/admin/categories/' + id;
                    modalNameInput.value = name;
                });
            }
        });
    </script>
@endsection