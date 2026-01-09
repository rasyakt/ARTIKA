@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;">👥 Customer Management</h2>
                <p class="text-muted mb-0">Manage customer database and loyalty</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addCustomerModal"
                style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px; padding: 0.75rem 1.5rem; font-weight: 600;">
                <span style="font-size: 1.25rem;">+</span> Add Customer
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" style="border-radius: 12px; border: none;">
                <strong>✅ Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">Customer</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Phone</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Email</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Points</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">Member Since</th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold" style="color: #6f5849;">{{ $customer->name }}</div>
                                        <small class="text-muted">{{ $customer->address ?? '-' }}</small>
                                    </td>
                                    <td>{{ $customer->phone }}</td>
                                    <td>{{ $customer->email ?? '-' }}</td>
                                    <td><span class="badge bg-success">{{ $customer->points }} pts</span></td>
                                    <td class="text-muted">
                                        {{ $customer->member_since ? $customer->member_since->format('d M Y') : '-' }}
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
                                                    <button class="dropdown-item" onclick='editCustomer(@json($customer))'
                                                        style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                        ✏️ Edit Customer
                                                    </button>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.customers.delete', $customer->id) }}"
                                                        method="POST" onsubmit="return confirm('Delete this customer?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            style="border-radius: 8px; padding: 0.5rem 1rem;">
                                                            🗑️ Delete Customer
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
                                        <div style="font-size: 4rem; opacity: 0.2;">👥</div>
                                        <p class="text-muted mb-0">No customers yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($customers->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="addCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;">➕ Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.customers.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">Name *</label>
                            <input type="text" class="form-control" name="name" required
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">Phone *</label>
                            <input type="text" class="form-control" name="phone" required
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">Email</label>
                            <input type="email" class="form-control" name="email"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">Address</label>
                            <textarea class="form-control" name="address" rows="2"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">💾
                            Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid #f2e8e5;">
                    <h5 class="modal-title fw-bold" style="color: #6f5849;">✏️ Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCustomerForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">Name *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">Phone *</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone" required
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #6f5849;">Address</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="2"
                                style="border-radius: 12px; border: 2px solid #e0cec7; padding: 0.75rem 1rem;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid #f2e8e5;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-radius: 12px;">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                            style="background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; border-radius: 12px;">💾
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editCustomer(customer) {
            document.getElementById('edit_name').value = customer.name;
            document.getElementById('edit_phone').value = customer.phone;
            document.getElementById('edit_email').value = customer.email || '';
            document.getElementById('edit_address').value = customer.address || '';
            document.getElementById('editCustomerForm').action = `/admin/customers/${customer.id}`;
            new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
        }
    </script>
@endsection