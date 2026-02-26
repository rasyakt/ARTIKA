@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <h4 class="fw-bold mb-0" style="color: var(--color-primary-dark);">
                <i class="fa-solid fa-credit-card me-2"></i>Metode Pembayaran
            </h4>
            <button class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal"
                data-bs-target="#addPaymentMethodModal"
                style="background: var(--color-primary-dark); border: none; border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600; height: fit-content;">
                <i class="fa-solid fa-plus me-2"></i> Tambah Metode
            </button>
        </div>

        <!-- Payment Methods Table -->
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body p-0">
                @if($paymentMethods->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="paymentMethodsTable">
                            <thead style="background-color: var(--gray-50);">
                                <tr>
                                    <th class="px-2 py-3" style="border: none; width: 40px;"></th>
                                    <th class="px-2 py-3" style="border: none; color: var(--color-primary-dark); font-weight: 600;">#</th>
                                    <th class="py-3" style="border: none; color: var(--color-primary-dark); font-weight: 600;">Metode</th>
                                    <th class="py-3" style="border: none; color: var(--color-primary-dark); font-weight: 600;">Slug</th>
                                    <th class="py-3" style="border: none; color: var(--color-primary-dark); font-weight: 600;">Status</th>
                                    <th class="py-3" style="border: none; color: var(--color-primary-dark); font-weight: 600;">Upload Bukti</th>
                                    <th class="py-3 text-end px-4" style="border: none; color: var(--color-primary-dark); font-weight: 600;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentMethods as $index => $method)
                                    <tr style="border-bottom: 1px solid #f0f0f0;" data-id="{{ $method->id }}">
                                        <td class="px-2 py-3 align-middle text-center drag-handle" style="cursor: grab; color: var(--gray-400);">
                                            <i class="fa-solid fa-grip-vertical"></i>
                                        </td>
                                        <td class="px-2 py-3 align-middle" style="color: var(--gray-500);">{{ $index + 1 }}</td>
                                        <td class="py-3 align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3"
                                                    style="width: 40px; height: 40px; background: var(--color-secondary-light); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa-solid {{ $method->icon ?? 'fa-wallet' }}" style="color: var(--color-primary-dark); font-size: 1.2rem;"></i>
                                                </div>
                                                <span class="fw-semibold" style="color: var(--gray-800);">{{ $method->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 align-middle">
                                            <code class="px-2 py-1 bg-light rounded text-muted" style="font-size: 0.85rem;">{{ $method->slug }}</code>
                                        </td>
                                        <td class="py-3 align-middle">
                                            @if($method->is_active)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill">
                                                    <i class="fa-solid fa-check-circle me-1"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                                                    <i class="fa-solid fa-times-circle me-1"></i> Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 align-middle">
                                            @if($method->proof_requirement === 'required')
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded-pill">
                                                    <i class="fa-solid fa-camera me-1"></i> Wajib
                                                </span>
                                            @elseif($method->proof_requirement === 'optional')
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill">
                                                    <i class="fa-solid fa-image me-1"></i> Opsional
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-2 rounded-pill">
                                                    <i class="fa-solid fa-ban me-1"></i> Nonaktif
                                                </span>
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
                                                            data-bs-target="#editPaymentMethodModal"
                                                            data-id="{{ $method->id }}"
                                                            data-name="{{ $method->name }}"
                                                            data-is-active="{{ $method->is_active }}"
                                                            data-icon="{{ $method->icon }}"
                                                            data-proof-requirement="{{ $method->proof_requirement }}">
                                                            <i class="fa-solid fa-pen me-2"></i>{{ __('common.edit') }}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider my-1">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('superadmin.payment-methods.destroy', $method->id) }}"
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
                            <i class="fa-solid fa-credit-card"></i>
                        </div>
                        <p class="text-muted mb-3">Belum ada metode pembayaran.</p>
                        <button class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal"
                            data-bs-target="#addPaymentMethodModal"
                            style="background: var(--color-primary-dark); border: none; border-radius: 10px; padding: 0.6rem 1.5rem; font-weight: 600; height: fit-content;">
                            <i class="fa-solid fa-plus me-2"></i> Tambah Pertama
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Payment Method Modal -->
    <div class="modal fade" id="addPaymentMethodModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid var(--gray-100);">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-plus me-1"></i> Tambah Metode Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('superadmin.payment-methods.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nama Metode *</label>
                            <input type="text" class="form-control" id="name" name="name" required
                                placeholder="Contoh: ShopeePay, Bank Central Asia"
                                style="border-radius: 10px; border: 2px solid var(--gray-100);">
                        </div>
                        <div class="mb-3">
                            <label for="icon" class="form-label fw-semibold">Icon (FontAwesome)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border: 2px solid var(--gray-100); border-radius: 10px 0 0 10px;">
                                    <i class="fa-solid fa-icons"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="icon" name="icon" 
                                    placeholder="fa-wallet, fa-mobile, etc"
                                    style="border-radius: 0 10px 10px 0; border: 2px solid var(--gray-100);">
                            </div>
                            <small class="text-muted">Gunakan class FontAwesome 6 (e.g. fa-wallet)</small>
                        </div>
                        <div class="mb-3">
                            <label for="proof_requirement" class="form-label fw-semibold">Upload Bukti Pembayaran</label>
                            <select class="form-select" id="proof_requirement" name="proof_requirement"
                                style="border-radius: 10px; border: 2px solid var(--gray-100);">
                                <option value="disabled">Nonaktif — Tidak ada upload</option>
                                <option value="optional">Opsional — Boleh upload, tidak wajib</option>
                                <option value="required">Wajib — Harus upload bukti</option>
                            </select>
                            <small class="text-muted">Contoh: Transfer/E-Wallet biasanya wajib, Cash biasanya nonaktif.</small>
                        </div>
                        <div class="mb-0">
                            <div class="form-check form-switch p-0 d-flex justify-content-between align-items-center">
                                <label class="form-check-label fw-semibold" for="is_active">Status Aktif</label>
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked style="width: 3rem; height: 1.5rem; cursor: pointer;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid var(--gray-100);">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary px-4"
                            style="background: var(--color-primary-dark); border: none; border-radius: 10px; font-weight: 600;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Payment Method Modal -->
    <div class="modal fade" id="editPaymentMethodModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="border-bottom: 2px solid var(--gray-100);">
                    <h5 class="modal-title fw-bold"><i class="fa-solid fa-pen me-1"></i> Edit Metode Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editPaymentMethodForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label fw-semibold">Nama Metode *</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required
                                style="border-radius: 10px; border: 2px solid var(--gray-100);">
                        </div>
                        <div class="mb-3">
                            <label for="edit_icon" class="form-label fw-semibold">Icon (FontAwesome)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" style="border: 2px solid var(--gray-100); border-radius: 10px 0 0 10px;">
                                    <i class="fa-solid fa-icons" id="icon_preview"></i>
                                </span>
                                <input type="text" class="form-control border-start-0" id="edit_icon" name="icon" 
                                    style="border-radius: 0 10px 10px 0; border: 2px solid var(--gray-100);">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_proof_requirement" class="form-label fw-semibold">Upload Bukti Pembayaran</label>
                            <select class="form-select" id="edit_proof_requirement" name="proof_requirement"
                                style="border-radius: 10px; border: 2px solid var(--gray-100);">
                                <option value="disabled">Nonaktif — Tidak ada upload</option>
                                <option value="optional">Opsional — Boleh upload, tidak wajib</option>
                                <option value="required">Wajib — Harus upload bukti</option>
                            </select>
                        </div>
                        <div class="mb-0">
                            <div class="form-check form-switch p-0 d-flex justify-content-between align-items-center">
                                <label class="form-check-label fw-semibold" for="edit_is_active">Status Aktif</label>
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1" style="width: 3rem; height: 1.5rem; cursor: pointer;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 2px solid var(--gray-100);">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary px-4"
                            style="background: var(--color-primary-dark); border: none; border-radius: 10px; font-weight: 600;">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Drag-and-drop reorder
            const tbody = document.querySelector('#paymentMethodsTable tbody');
            if (tbody) {
                new Sortable(tbody, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    onEnd: function () {
                        // Update row numbers
                        tbody.querySelectorAll('tr').forEach((row, i) => {
                            const numCell = row.querySelector('td:nth-child(2)');
                            if (numCell) numCell.textContent = i + 1;
                        });

                        // Save new order via AJAX
                        const order = [...tbody.querySelectorAll('tr')].map(row => row.dataset.id);
                        fetch('{{ route("superadmin.payment-methods.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ order: order })
                        }).then(res => res.json()).then(data => {
                            if (data.success) {
                                showToast('success', 'Urutan berhasil diperbarui!');
                            }
                        }).catch(() => {
                            showToast('error', 'Gagal menyimpan urutan.');
                        });
                    }
                });
            }

            // Edit modal
            var editPaymentMethodModal = document.getElementById('editPaymentMethodModal');
            if (editPaymentMethodModal) {
                editPaymentMethodModal.addEventListener('show.bs.modal', function (event) {
                    var button = event.relatedTarget;
                    var id = button.getAttribute('data-id');
                    var name = button.getAttribute('data-name');
                    var isActive = button.getAttribute('data-is-active') == '1';
                    var icon = button.getAttribute('data-icon');
                    var proofRequirement = button.getAttribute('data-proof-requirement');

                    var modalForm = editPaymentMethodModal.querySelector('#editPaymentMethodForm');
                    var modalNameInput = editPaymentMethodModal.querySelector('#edit_name');
                    var modalIconInput = editPaymentMethodModal.querySelector('#edit_icon');
                    var modalProofSelect = editPaymentMethodModal.querySelector('#edit_proof_requirement');
                    var modalActiveToggle = editPaymentMethodModal.querySelector('#edit_is_active');

                    modalForm.action = '/superadmin/payment-methods/' + id;
                    modalNameInput.value = name;
                    modalIconInput.value = icon || '';
                    modalProofSelect.value = proofRequirement || 'disabled';
                    modalActiveToggle.checked = isActive;
                });
            }

            // Handle delete confirmation
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    confirmAction({
                        text: "Metode ini akan dihapus permanen. Lanjutkan?",
                        confirmButtonText: "Hapus"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: var(--color-secondary-light) !important;
        }
        .drag-handle:active {
            cursor: grabbing !important;
        }
    </style>
@endsection
