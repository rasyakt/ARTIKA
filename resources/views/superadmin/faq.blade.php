@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 px-3 px-lg-4">
        {{-- Header --}}
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
            <div>
                <h4 class="fw-800 mb-1" style="color: var(--color-primary-dark);">
                    <i class="fa-solid fa-circle-question me-2" style="color: var(--color-primary);"></i>
                    FAQ Management
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Kelola pertanyaan yang sering diajukan</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('faq.index') }}" class="btn btn-outline-primary btn-sm" style="border-radius: 10px;">
                    <i class="fa-solid fa-eye me-1"></i> Preview
                </a>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#faqModal"
                    onclick="resetModal()" style="border-radius: 10px;">
                    <i class="fa-solid fa-plus me-1"></i> Tambah FAQ
                </button>
            </div>
        </div>

        {{-- Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert"
                style="border-radius: 12px;">
                <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FAQ Table --}}
        <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                    <thead style="background: var(--brown-50);">
                        <tr>
                            <th class="ps-3 text-muted fw-700" style="font-size: 0.75rem;">#</th>
                            <th class="text-muted fw-700" style="font-size: 0.75rem;">PERTANYAAN</th>
                            <th class="text-muted fw-700" style="font-size: 0.75rem;">KATEGORI</th>
                            <th class="text-muted fw-700" style="font-size: 0.75rem;">TARGET ROLE</th>
                            <th class="text-muted fw-700 text-center" style="font-size: 0.75rem;">URUTAN</th>
                            <th class="text-muted fw-700 text-center" style="font-size: 0.75rem;">STATUS</th>
                            <th class="text-muted fw-700 text-center pe-3" style="font-size: 0.75rem;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($faqs as $faq)
                            <tr>
                                <td class="ps-3 text-muted">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="fw-600" style="max-width: 350px;">{{ Str::limit($faq->question, 60) }}</div>
                                    <div class="text-muted small" style="font-size: 0.75rem;">
                                        {{ Str::limit(strip_tags($faq->answer), 50) }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border"
                                        style="border-radius: 8px; font-size: 0.7rem;">
                                        {{ $faq->category_label }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $faq->target_role ? 'bg-info text-dark' : 'bg-success' }}"
                                        style="border-radius: 8px; font-size: 0.7rem;">
                                        {{ $faq->target_role_label }}
                                    </span>
                                </td>
                                <td class="text-center">{{ $faq->sort_order }}</td>
                                <td class="text-center">
                                    @if($faq->is_active)
                                        <span class="badge bg-success" style="border-radius: 8px;">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary" style="border-radius: 8px;">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center pe-3">
                                    <button class="btn btn-sm btn-outline-primary me-1" style="border-radius: 8px;" title="Edit"
                                        onclick="editFaq({{ json_encode($faq) }})">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('superadmin.faq.destroy', $faq->id) }}" method="POST"
                                        class="d-inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px;"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fa-solid fa-folder-open mb-2"
                                        style="font-size: 2rem; color: var(--color-primary); opacity: 0.4;"></i>
                                    <div class="text-muted">Belum ada FAQ. Klik "Tambah FAQ" untuk memulai.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add/Edit FAQ Modal --}}
    <div class="modal fade" id="faqModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0" style="border-radius: 16px;">
                <form id="faqForm" method="POST" action="{{ route('superadmin.faq.store') }}">
                    @csrf
                    <div id="methodField"></div>
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-800" id="faqModalTitle">
                            <i class="fa-solid fa-plus me-2" style="color: var(--color-primary);"></i> Tambah FAQ
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body pt-3">
                        <div class="mb-3">
                            <label class="form-label fw-600">Pertanyaan <span class="text-danger">*</span></label>
                            <input type="text" name="question" id="faqQuestion" class="form-control" required
                                placeholder="Contoh: Bagaimana cara menambahkan produk?" style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-600">Jawaban <span class="text-danger">*</span></label>
                            <textarea name="answer" id="faqAnswer" class="form-control" rows="5" required
                                placeholder="Tulis jawaban yang jelas dan detail..."
                                style="border-radius: 10px;"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-600">Kategori</label>
                                <select name="category" id="faqCategory" class="form-select" style="border-radius: 10px;">
                                    @foreach($categories as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-600">Target Role</label>
                                <select name="target_role" id="faqTargetRole" class="form-select"
                                    style="border-radius: 10px;">
                                    @foreach($targetRoles as $key => $label)
                                        <option value="{{ $key ?? '' }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-600">Urutan</label>
                                <input type="number" name="sort_order" id="faqSortOrder" class="form-control" value="0"
                                    min="0" style="border-radius: 10px;">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="faqIsActive"
                                        value="1" checked>
                                    <label class="form-check-label fw-600" for="faqIsActive">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 10px;">Batal</button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px;" id="faqSubmitBtn">
                            <i class="fa-solid fa-save me-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function resetModal() {
            document.getElementById('faqForm').action = "{{ route('superadmin.faq.store') }}";
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('faqModalTitle').innerHTML = '<i class="fa-solid fa-plus me-2" style="color: var(--color-primary);"></i> Tambah FAQ';
            document.getElementById('faqQuestion').value = '';
            document.getElementById('faqAnswer').value = '';
            document.getElementById('faqCategory').value = 'general';
            document.getElementById('faqTargetRole').value = '';
            document.getElementById('faqSortOrder').value = '0';
            document.getElementById('faqIsActive').checked = true;
        }

        function editFaq(faq) {
            document.getElementById('faqForm').action = "{{ url('/superadmin/faq') }}/" + faq.id;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            document.getElementById('faqModalTitle').innerHTML = '<i class="fa-solid fa-pen-to-square me-2" style="color: var(--color-primary);"></i> Edit FAQ';
            document.getElementById('faqQuestion').value = faq.question;
            document.getElementById('faqAnswer').value = faq.answer;
            document.getElementById('faqCategory').value = faq.category;
            document.getElementById('faqTargetRole').value = faq.target_role || '';
            document.getElementById('faqSortOrder').value = faq.sort_order;
            document.getElementById('faqIsActive').checked = faq.is_active;

            const modal = new bootstrap.Modal(document.getElementById('faqModal'));
            modal.show();
        }

        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus FAQ?',
                    text: 'FAQ ini akan dihapus secara permanen.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--color-danger)',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'artika-swal-popup',
                        title: 'artika-swal-title',
                        confirmButton: 'artika-swal-confirm-btn',
                        cancelButton: 'artika-swal-cancel-btn'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
@endsection