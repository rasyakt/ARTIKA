<div class="modal fade" id="excelImportModal" tabindex="-1" aria-labelledby="excelImportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-800 d-flex align-items-center gap-2" id="excelImportModalLabel">
                    <i class="fa-solid fa-file-import"></i>
                    Import Data {{ $title ?? '' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form action="{{ $importRoute }}" method="POST" enctype="multipart/form-data" id="excelImportForm">
                @csrf
                <div class="modal-body p-4">
                    {{-- Alert Messages --}}
                    <div id="importAlert" class="alert d-none mb-4" style="border-radius: 12px; font-size: 0.9rem;">
                    </div>

                    {{-- Download Template Notification --}}
                    <div class="d-flex align-items-center justify-content-between p-3 rounded mb-4"
                        style="background-color: var(--brown-50); border: 1px dashed var(--color-primary);">
                        <div>
                            <div class="fw-700 text-primary-dark" style="font-size: 0.9rem;">Belum punya formatnya?
                            </div>
                            <div class="small text-muted">Unduh template Excel yang sudah disediakan agar data Valid.
                            </div>
                        </div>
                        <a href="{{ $templateRoute }}" class="btn btn-sm btn-primary d-flex align-items-center gap-2">
                            <i class="fa-solid fa-download"></i> Unduh
                        </a>
                    </div>

                    {{-- Drag and Drop Area --}}
                    <div class="upload-zone text-center p-5 rounded position-relative" id="dropZone"
                        style="border: 2px dashed #cbd5e1; background-color: #f8fafc; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); cursor: pointer;">

                        <input class="position-absolute w-100 h-100 top-0 start-0 opacity-0" style="cursor: pointer;"
                            type="file" id="excelFile" name="file" accept=".xlsx, .xls, .csv">

                        <div class="upload-icon mb-3">
                            <i class="fa-solid fa-cloud-arrow-up text-primary" style="font-size: 2.5rem;"></i>
                        </div>
                        <h6 class="fw-700 text-dark mb-1">Pilih File atau Tarik Kesini</h6>
                        <p class="small text-muted mb-0">Hanya menerima format .xlsx, .xls, .csv</p>

                        {{-- File details --}}
                        <div id="fileDetails"
                            class="d-none mt-3 p-2 rounded bg-white shadow-sm d-flex align-items-center gap-2 justify-content-center border"
                            style="border-color: var(--color-primary) !important;">
                            <i class="fa-solid fa-file-excel text-success"></i>
                            <span id="fileName" class="fw-600 text-dark small text-truncate"
                                style="max-width: 200px;"></span>
                            <i class="fa-solid fa-circle-check text-primary ms-1"></i>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 bg-light px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary fw-600 rounded-pill px-4"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="submit"
                        class="btn btn-primary fw-600 rounded-pill px-4 d-flex align-items-center gap-2"
                        id="btnImportSubmit">
                        <i class="fa-solid fa-upload" id="submitIcon"></i>
                        <span id="submitText">Mulai Import</span>
                        <div class="spinner-border spinner-border-sm d-none" id="submitSpinner" role="status"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .upload-zone:hover,
    .upload-zone.dragover {
        border-color: var(--color-primary) !important;
        background-color: var(--brown-50) !important;
    }

    .upload-zone.dragover .upload-icon i {
        transform: scale(1.1);
        transition: transform 0.2s;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('excelFile');
        const fileDetails = document.getElementById('fileDetails');
        const fileName = document.getElementById('fileName');
        const importForm = document.getElementById('excelImportForm');
        const btnSubmit = document.getElementById('btnImportSubmit');
        const submitText = document.getElementById('submitText');
        const submitIcon = document.getElementById('submitIcon');
        const submitSpinner = document.getElementById('submitSpinner');
        const alertBox = document.getElementById('importAlert');

        // Drag events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', (e) => {
            let dt = e.dataTransfer;
            let files = dt.files;
            handleFiles(files);
        });

        // Handle selected files
        fileInput.addEventListener('change', function () {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                const file = files[0];
                const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel', 'text/csv'];

                // Check file extension as fallback if mime type is missing
                const ext = file.name.split('.').pop().toLowerCase();
                const validExts = ['xlsx', 'xls', 'csv'];

                if (validTypes.includes(file.type) || validExts.includes(ext)) {
                    // Remove required attribute from input if we populate it manually (for drag/drop)
                    if (fileInput.files !== files) {
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;
                    }

                    fileName.textContent = file.name;
                    fileDetails.classList.remove('d-none');
                    alertBox.classList.add('d-none');
                } else {
                    fileInput.value = '';
                    fileDetails.classList.add('d-none');
                    showAlert('Format file tidak didukung. Harap gunakan Excel (.xlsx, .xls) atau CSV.', 'danger');
                }
            }
        }

        function showAlert(message, type) {
            alertBox.innerHTML = `<i class="fa-solid fa-${type === 'danger' ? 'triangle-exclamation' : 'circle-check'} me-2"></i> ${message}`;
            alertBox.className = `alert alert-${type} mb-4`;
            alertBox.classList.remove('d-none');
        }

        // Handle form submission
        importForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (!fileInput.files.length) {
                showAlert('Pilih file terlebih dahulu sebelum melakukan import.', 'danger');
                return;
            }

            // Show loading state
            btnSubmit.disabled = true;
            submitText.textContent = 'Memproses Data...';
            submitIcon.classList.add('d-none');
            submitSpinner.classList.remove('d-none');

            // Force manual submit bypass
            importForm.submit();
        });
    });
</script>