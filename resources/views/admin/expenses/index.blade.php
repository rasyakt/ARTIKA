@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1" style="color: #6f5849;">
                    <i class="fa-solid fa-wallet me-2"></i>{{ __('admin.operational_expenses') }}
                </h4>
                <p class="text-muted mb-0 small">{{ __('admin.expenses_subtitle') }}</p>
            </div>
            <button class="btn btn-primary shadow-sm d-inline-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#addExpenseModal"
                style="background: #6f5849; border: none; border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600; height: fit-content;">
                <i class="fa-solid fa-plus me-2"></i> {{ __('admin.add_expense') }}
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm"
                style="border-radius: 12px; background-color: #f0fdf4; color: #16a34a;">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Expenses Table -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                @if($expenses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: #fdf8f6;">
                                <tr>
                                    <th class="px-4 py-3 border-0" style="color: #6f5849; font-weight: 600; width: 15%;">
                                        {{ __('admin.date') }}
                                    </th>
                                    <th class="py-3 border-0" style="color: #6f5849; font-weight: 600; width: 15%;">
                                        {{ __('admin.category') }}
                                    </th>
                                    <th class="py-3 border-0" style="color: #6f5849; font-weight: 600;">{{ __('admin.notes') }}
                                    </th>
                                    <th class="py-3 border-0" style="color: #6f5849; font-weight: 600; width: 15%;">
                                        {{ __('admin.amount') }}
                                    </th>
                                    <th class="py-3 border-0 text-end px-4"
                                        style="color: #6f5849; font-weight: 600; width: 10%;">{{ __('common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                    <tr style="border-bottom: 1px solid #f2e8e5;">
                                        <td class="px-4 py-3">
                                            <div class="fw-bold" style="color: #2c3e50;">{{ $expense->date->format('d M Y') }}</div>
                                            <small class="text-muted">{{ $expense->created_at->format('H:i') }}</small>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge"
                                                style="background: #e0cec7; color: #6f5849; padding: 0.5rem 0.8rem; border-radius: 8px;">
                                                {{ $expense->category->name }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $expense->notes }}">
                                                {{ $expense->notes ?: '-' }}
                                            </div>
                                            <small class="text-muted">{{ __('admin.added_by') }}: {{ $expense->user->name }}</small>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-bold text-danger">
                                                Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                            </div>
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
                                                            data-bs-target="#editExpenseModal" data-expense='@json($expense)'>
                                                            <i class="fa-solid fa-pen me-2 text-primary"></i>
                                                            {{ __('common.edit') }}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider opacity-50">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.expenses.delete', $expense->id) }}"
                                                            method="POST" class="delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="dropdown-item py-2 text-danger btn-delete">
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
                            <i class="fa-solid fa-receipt"></i>
                        </div>
                        <h5 class="text-muted">{{ __('admin.no_expenses_recorded') }}</h5>
                        <p class="text-muted small">{{ __('admin.start_tracking_expenses') }}</p>
                        <button class="btn btn-brown mt-2" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
                            <i class="fa-solid fa-plus me-1"></i> {{ __('admin.add_first_expense') }}
                        </button>
                    </div>
                @endif
            </div>
            @if($expenses->hasPages())
                <div class="card-footer border-0 d-flex justify-content-end py-3 px-4">
                    {{ $expenses->links('vendor.pagination.custom-brown') }}
                </div>
            @endif
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div class="modal fade" id="addExpenseModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-circle-plus me-2"></i>{{ __('admin.add_expense') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.expenses.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.date') }}</label>
                            <input type="date" name="date" class="form-control custom-input" value="{{ date('Y-m-d') }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.category') }}</label>
                            <select name="expense_category_id" class="form-select custom-input" required>
                                <option value="" disabled selected>{{ __('admin.select_category') }}</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-text">Rp</span>
                                <input type="number" name="amount" class="form-control custom-input" placeholder="0"
                                    required min="0">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.notes') }}</label>
                            <textarea name="notes" class="form-control custom-input" rows="3"
                                placeholder="{{ __('admin.expense_notes_placeholder') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
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

    <!-- Edit Expense Modal -->
    <div class="modal fade" id="editExpenseModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-pen-to-square me-2"></i>{{ __('admin.edit_expense') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editExpenseForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.date') }}</label>
                            <input type="date" name="date" id="edit_date" class="form-control custom-input" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.category') }}</label>
                            <select name="expense_category_id" id="edit_category" class="form-select custom-input" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text custom-input-text">Rp</span>
                                <input type="number" name="amount" id="edit_amount" class="form-control custom-input"
                                    required min="0">
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold" style="color: #85695a;">{{ __('admin.notes') }}</label>
                            <textarea name="notes" id="edit_notes" class="form-control custom-input" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                            style="border-radius: 10px;">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-brown px-4 shadow-sm" style="border-radius: 10px;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> {{ __('admin.update_expense') }}
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

        .custom-input-text {
            background-color: #fdf8f6;
            border: 2px solid #f2e8e5;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #85695a;
            font-weight: 600;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editModal = document.getElementById('editExpenseModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const expense = JSON.parse(button.getAttribute('data-expense'));

                    const form = document.getElementById('editExpenseForm');
                    form.action = `/admin/expenses/${expense.id}`;

                    document.getElementById('edit_date').value = expense.date.split('T')[0];
                    document.getElementById('edit_category').value = expense.expense_category_id;
                    document.getElementById('edit_amount').value = expense.amount;
                    document.getElementById('edit_notes').value = expense.notes || '';
                });
                // Handle delete confirmation
                const deleteButtons = document.querySelectorAll('.btn-delete');
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const form = this.closest('form');
                        confirmAction({
                            text: "{{ __('admin.delete_expense_confirm') }}",
                            confirmButtonText: "{{ __('common.delete') }}"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            }
        });
    </script>
@endsection