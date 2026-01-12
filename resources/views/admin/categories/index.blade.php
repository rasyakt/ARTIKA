@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;"><i class="fa-solid fa-folder me-2"></i>Category Management</h2>
                <p class="text-muted mb-0">Manage product categories</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal"
                style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600;">
                <span style="font-size: 1.25rem;">+</span> Add Category
            </button>
        </div>

        @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" style="border-radius: 12px; border: none;">
                <strong><i class="fa-solid fa-circle-check me-1"></i>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" style="border-radius: 12px; border: none;">
                <strong><i class="fa-solid fa-circle-exclamation me-1"></i>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Categories Grid -->
        <div class="row g-4">
            @forelse($categories as $category)
                <div class="col-md-3">
                    <div class="card shadow-sm h-100" style="border-radius: 16px; border: none; transition: all 0.3s;">
                        <div class="card-body text-center">
                                <div class="mb-3"
                                style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #f2e8e5 0%, #e0cec7 100%); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                                <i class="fa-solid fa-folder"></i>
                            </div>
                            <h5 class="fw-bold mb-2" style="color: #6f5849;">{{ $category->name }}</h5>
                            <p class="text-muted mb-3">
                                <span class="badge" style="background: #e0cec7; color: #6f5849; font-size: 0.9rem;">
                                    {{ $category->products_count }} products
                                </span>
                            </p>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"
                                    style="border-radius: 8px; border: 1px solid #e0cec7; font-size: 1.2rem; line-height: 1; padding: 0.25rem 0.5rem;">
                                    ⋮
                                </button>
                                <ul class="dropdown-menu"
                                    style="border-radius: 12px; border: 1px solid #e0cec7; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                    <li>
                                        <button class="dropdown-item"
                                            data-bs-toggle="modal" data-bs-target="#editCategoryModal"
                                            onclick="editCategory(@json($category))"
                                            style="border-radius: 8px; padding: 0.5rem 1rem;">
                                            <i class="fa-solid fa-pen me-1"></i> Edit Category
                                        </button>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                <i class="fa-solid fa-trash me-1"></i> Delete Category
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                        <div class="text-center py-5">
                        <div style="font-size: 5rem; opacity: 0.2;"><i class="fa-solid fa-folder"></i></div>
                        <p class="text-muted mb-3">No categories yet</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            Add Your First Category
                        </button>
                    </div>
                </div>
            @endforelse
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
                            <label for="edit_name" class="form-label fw-semibold" style="color: #6f5849;">Category Name
                                *</label>
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
        function editCategory(category) {
            document.getElementById('edit_name').value = category.name;
            document.getElementById('editCategoryForm').action = `/admin/categories/${category.id}`;
            new bootstrap.Modal(document.getElementById('editCategoryModal')).show();
        }
    </script>
@endsection