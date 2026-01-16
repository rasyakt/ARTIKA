@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;"><i
                        class="fa-solid fa-users me-2"></i>{{ __('admin.user_management') }}</h2>
                <p class="text-muted mb-0">{{ __('admin.manage_users_permissions') }}</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal"
                style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600;">
                <span style="font-size: 1.25rem;">+</span> {{ __('admin.add_user') }}
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" style="border-radius: 12px; border: none;">
                <strong><i class="fa-solid fa-circle-check me-1"></i>{{ __('common.success') }}!</strong>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" style="border-radius: 12px; border: none;">
                <strong><i class="fa-solid fa-circle-exclamation me-1"></i>{{ __('common.error') }}!</strong>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Users Table -->
        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">{{ __('common.user') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.username') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.nis') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.role') }}</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3"
                                                style="width: 45px; height: 45px; background: linear-gradient(135deg, #f2e8e5 0%, #e0cec7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem;">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold" style="color: #6f5849;">{{ $user->name }}</div>
                                                <small class="text-muted">{{ __('common.id') }}: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <code
                                            style="background: #fdf8f6; padding: 0.25rem 0.5rem; border-radius: 6px; color: #85695a;">{{ $user->username }}</code>
                                    </td>
                                    <td>{{ $user->nis ?? '-' }}</td>
                                    <td>
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-danger',
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
                                                style="border-radius: 8px; border: 1px solid #e0cec7; font-size: 1.2rem; line-height: 1; padding: 0.25rem 0.5rem;">
                                                ⋮
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end"
                                                style="border-radius: 12px; border: 1px solid #e0cec7; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                                                <li>
                                                    <button class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editUserModal" onclick='editUser(@json($user))'
                                                        style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                        <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('common.edit') }}
                                                    </button>
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST"
                                                        onsubmit="return confirm('{{ __('admin.delete_user_confirm') }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                            <i class="fa-solid fa-trash me-1"></i> {{ __('admin.delete_user') }}
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div style="font-size: 4rem; opacity: 0.2;"><i class="fa-solid fa-users"></i></div>
                                        <p class="text-muted mb-0">{{ __('admin.no_users_found') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i class="fa-solid fa-plus me-1"></i>
                        {{ __('admin.add_new_user') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.full_name') }}
                                    *</label>
                                <input type="text" class="form-control" name="name" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.username') }}
                                    *</label>
                                <input type="text" class="form-control" name="username" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.password') }}
                                    *</label>
                                <input type="password" class="form-control" name="password" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.nis') }}
                                    ({{ __('common.optional') }})</label>
                                <input type="text" class="form-control" name="nis"
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.role') }}
                                    *</label>
                                <select class="form-select" name="role_id" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                    <option value="">{{ __('common.select_role') }}</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('admin.save_user') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;"><i class="fa-solid fa-pen me-1"></i>
                        {{ __('admin.edit_user') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.full_name') }}
                                    *</label>
                                <input type="text" class="form-control" id="edit_name" name="name" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.username') }}
                                    *</label>
                                <input type="text" class="form-control" id="edit_username" name="username" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.password') }}
                                    ({{ __('admin.password_leave_blank') }})</label>
                                <input type="password" class="form-control" name="password"
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.nis') }}
                                    ({{ __('common.optional') }})</label>
                                <input type="text" class="form-control" id="edit_nis" name="nis"
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold" style="color: #6f5849;">{{ __('common.role') }}
                                    *</label>
                                <select class="form-select" id="edit_role_id" name="role_id" required
                                    style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">
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
            document.getElementById('editUserForm').action = `/admin/users/${user.id}`;
            new bootstrap.Modal(document.getElementById('editUserModal')).show();
        }
    </script>
@endsection