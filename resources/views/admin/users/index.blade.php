@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: var(--color-primary-dark);"><i
                        class="fa-solid fa-users me-2"></i>{{ __('admin.user_management') }}</h2>
                <p class="text-muted mb-0">{{ __('admin.manage_users_permissions') }}</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary shadow-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#excelImportModal"
                    style="border-radius: 12px; padding: 0.75rem 1.25rem; font-weight: 600; height: fit-content; border: 1px solid var(--color-primary);">
                    <i class="fa-solid fa-file-import me-2"></i> Import Excel
                </button>
                <button class="btn btn-primary shadow-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                    data-bs-target="#addUserModal"
                    style="background: var(--color-primary-dark); border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600; height: fit-content;">
                    <i class="fa-solid fa-plus me-2"></i> {{ __('admin.add_user') }}
                </button>
            </div>
        </div>


        <!-- Users Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: var(--brown-50);">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: var(--color-primary-dark);">{{ __('common.user') }}</th>
                                <th class="border-0 fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.username') }}</th>
                                <th class="border-0 fw-semibold" style="color: var(--color-primary-dark);">ID (NIS/NIK/...)</th>
                                <th class="border-0 fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.role') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: var(--color-primary-dark);">
                                    {{ __('common.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3"
                                                style="width: 45px; height: 45px; background: var(--brown-100); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="color: var(--color-primary-dark);">{{ $user->name }}</div>
                                                <small class="text-muted">{{ __('common.id') }}: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code
                                            style="background: var(--brown-50); padding: 0.25rem 0.5rem; border-radius: 6px; color: var(--color-primary);">{{ $user->username }}</code>
                                    </td>
                                    <td>
                                        @if($user->identity_type)
                                            <span class="text-muted small fw-bold">{{ $user->identity_type->label }}:</span>
                                        @endif
                                        {{ $user->nis ?? '-' }}
                                    </td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'superadmin' => 'bg-dark',
                                                'admin' => 'bg-danger',
                                                'manager' => 'bg-info',
                                                'cashier' => 'bg-success',
                                                'warehouse' => 'bg-primary'
                                            ];
                                            $roleColor = $roleColors[$user->role->name] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $roleColor }}">{{ ucfirst($user->role->name) }}</span>
                                    </td>
                                    <td>
                                        <div class="dropdown text-center">
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                style="border-radius: 8px; border: 1px solid var(--color-secondary-light); font-size: 1.2rem; line-height: 1; padding: 0.25rem 0.5rem;">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                style="border-radius: 12px; border: 1px solid var(--color-secondary-light); box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editUserModal" onclick='editUser(@json($user))'
                                                        style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('common.edit') }}
                                                    </button>
                                                </li>
                                                @php
                                                    $canDelete = false;
                                                    if (auth()->user()->role->name === 'superadmin') {
                                                        $canDelete = $user->role->name !== 'superadmin';
                                                    } else {
                                                        $canDelete = $user->role->name === 'cashier';
                                                    }
                                                @endphp
                                                @if($canDelete)
                                                    <li>
                                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                                            class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item text-danger btn-delete"
                                                                style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                                <i class="fa-solid fa-trash me-1"></i> {{ __('admin.delete_user') }}
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
                                    <td colspan="5" class="text-center py-5">
                                        <div style="font-size: 4rem; opacity: 0.2;"><i class="fa-solid fa-users"></i></div>
                                        <p class="text-muted mb-0">{{ __('admin.no_users_found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
                <div class="card-footer border-0 d-flex justify-content-end py-3">
                    {{ $users->links('vendor.pagination.custom-brown') }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .password-field {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--color-primary-light);
            cursor: pointer;
            z-index: 10;
            padding: 5px;
            display: flex;
            align-items: center;
        }

        .toggle-password:hover {
            color: var(--color-primary);
        }
    </style>
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid var(--brown-100);">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-1"></i>
                        {{ __('admin.add_new_user') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.full_name') }}
                                    *</label>
                                <input type="text" class="form-control" name="name" required
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.username') }}
                                    *</label>
                                <input type="text" class="form-control" name="username" required
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.password') }}
                                    *</label>
                                <div class="password-field">
                                    <input type="password" class="form-control" name="password" id="add_password" required
                                        style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 2.5rem 0.75rem 1rem;">
                                    <button type="button" class="toggle-password"
                                        onclick="togglePasswordVisibility('add_password', this)">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">Jenis ID</label>
                                <select class="form-select identity-type-select" name="identity_type_id" id="add_identity_type_id"
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                                    <option value="">Tanpa ID</option>
                                    @foreach($identityTypes as $type)
                                        <option value="{{ $type->id }}" data-label="{{ $type->label }}">{{ $type->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold identity-number-label" style="color: var(--color-primary-dark);">Nomor ID (Opsional)</label>
                                <input type="text" class="form-control" name="nis"
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.role') }}
                                    *</label>
                                <select class="form-select" name="role_id" required
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                                    <option value="">{{ __('common.select_role') }}</option>
                                    @foreach($roles as $role)
                                        @php
                                            $isAllowed = false;
                                            if (auth()->user()->role->name === 'superadmin') {
                                                $isAllowed = $role->name !== 'superadmin';
                                            } else {
                                                $isAllowed = !in_array($role->name, ['superadmin', 'admin']);
                                            }
                                        @endphp
                                        @if($isAllowed)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid var(--brown-100);">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: var(--color-primary-dark); border: none; border-radius: 12px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('admin.save_user') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-800 d-flex align-items-center gap-2" id="deleteConfirmModalLabel">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        {{ __('admin.confirm_delete') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <div class="mb-3 text-danger" style="font-size: 3rem;">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <h5 class="fw-bold mb-2">{{ __('admin.are_you_sure') }}</h5>
                    <p class="text-muted mb-0">{{ __('admin.delete_warning') }}</p>
                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-center pb-4">
                    <button type="button" class="btn btn-outline-secondary fw-semibold rounded-pill px-4"
                        data-bs-dismiss="modal">
                        {{ __('common.cancel') }}
                    </button>
                    <button type="button" class="btn btn-danger fw-semibold rounded-pill px-4" id="btnConfirmDelete">
                        {{ __('admin.yes_delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Import Excel Modal Component --}}
    <x-excel-import-modal
        title="Pengguna"
        importRoute="{{ route('admin.users.import') }}"
        templateRoute="{{ route('admin.users.template') }}"
    />

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid var(--brown-100);">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen me-1"></i>
                        {{ __('admin.edit_user') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.full_name') }}
                                    *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.username') }}
                                    *</label>
                                <input type="text" class="form-control" id="edit_username" name="username" required
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.password') }}
                                    ({{ __('admin.password_leave_blank') }})</label>
                                <div class="password-field">
                                    <input type="password" class="form-control" name="password" id="edit_password"
                                        style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 2.5rem 0.75rem 1rem;">
                                    <button type="button" class="toggle-password"
                                        onclick="togglePasswordVisibility('edit_password', this)">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">Jenis ID</label>
                                <select class="form-select identity-type-select" name="identity_type_id" id="edit_identity_type_id"
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                                    <option value="">Tanpa ID</option>
                                    @foreach($identityTypes as $type)
                                        <option value="{{ $type->id }}" data-label="{{ $type->label }}">{{ $type->label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold identity-number-label" style="color: var(--color-primary-dark);">Nomor ID (Opsional)</label>
                                <input type="text" class="form-control" id="edit_nis" name="nis"
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: var(--color-primary-dark);">{{ __('common.role') }}
                                    *</label>
                                <select class="form-select" id="edit_role_id" name="role_id" required
                                    style="border-radius: 12px; border: 1px solid var(--gray-300); padding: 0.75rem 1rem;">
                                    @foreach($roles as $role)
                                        @php
                                            $isAllowed = false;
                                            if (auth()->user()->role->name === 'superadmin') {
                                                $isAllowed = $role->name !== 'superadmin';
                                            } else {
                                                $isAllowed = !in_array($role->name, ['superadmin', 'admin']);
                                            }
                                        @endphp
                                        @if($isAllowed)
                                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid var(--brown-100);">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: var(--color-primary-dark); border: none; border-radius: 12px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('common.update') }} {{ __('common.user') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editUser(user) {
            document.getElementById('edit_name').value = user.name;
            document.getElementById('edit_username').value = user.username;
            document.getElementById('edit_nis').value = user.nis || '';
            document.getElementById('edit_role_id').value = user.role_id;
            document.getElementById('edit_identity_type_id').value = user.identity_type_id || '';
            
            // Trigger label update for edit modal
            const editTypeSelect = document.getElementById('edit_identity_type_id');
            const editLabel = editTypeSelect.parentElement.nextElementSibling.querySelector('.identity-number-label');
            const selectedOption = editTypeSelect.options[editTypeSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                editLabel.textContent = selectedOption.dataset.label + ' (Opsional)';
            } else {
                editLabel.textContent = 'Nomor ID (Opsional)';
            }

            // Reset password field and toggle icon
            const passwordInput = document.getElementById('edit_password');
            passwordInput.value = '';
            passwordInput.type = 'password';
            const toggleIcon = passwordInput.parentElement.querySelector('.toggle-password i');
            if (toggleIcon) {
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }

            document.getElementById('editUserForm').action = `/admin/users/${user.id}`;
        }

        // Handle dynamic identity labels
        document.querySelectorAll('.identity-type-select').forEach(select => {
            select.addEventListener('change', function() {
                const label = this.parentElement.nextElementSibling.querySelector('.identity-number-label');
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    label.textContent = selectedOption.dataset.label + ' (Opsional)';
                } else {
                    label.textContent = 'Nomor ID (Opsional)';
                }
            });
        });

        function togglePasswordVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Handle delete confirmation
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    confirmAction({
                        text: "{{ __('admin.delete_user_confirm') }}",
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