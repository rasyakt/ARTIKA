@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1><i class="fas fa-clipboard-list"></i> {{ __('admin.audit_log') }}</h1>
                <p class="text-muted">{{ __('admin.audit_log_desc') }}</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                    <i class="fas fa-filter"></i> {{ __('common.filter') }}
                </button>
                <button class="btn btn-success" onclick="downloadPdf()">
                    <i class="fas fa-file-pdf"></i> {{ __('admin.download_pdf') }}
                </button>
                <button class="btn btn-info" onclick="exportCsv()">
                    <i class="fas fa-file-csv"></i> {{ __('admin.export_csv') }}
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-left-primary">
                    <div class="card-body">
                        <div class="text-primary font-weight-bold text-uppercase mb-1">{{ __('admin.total_logs') }}</div>
                        <div class="h3 mb-0">{{ $logs->total() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-success">
                    <div class="card-body">
                        <div class="text-success font-weight-bold text-uppercase mb-1">{{ __('common.page') }}</div>
                        <div class="h3 mb-0">{{ $logs->currentPage() }} / {{ $logs->lastPage() }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-info">
                    <div class="card-body">
                        <div class="text-info font-weight-bold text-uppercase mb-1">{{ __('common.period') }}</div>
                        <div class="small">{{ request('start_date') ?? __('common.all') }} -
                            {{ request('end_date') ?? __('common.today') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-left-warning">
                    <div class="card-body">
                        <div class="text-warning font-weight-bold text-uppercase mb-1">{{ __('common.user') }}</div>
                        <div class="small">{{ Auth::user()->name }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit Logs Table -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ __('admin.activity_logs_list') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('common.date') }}</th>
                                <th>{{ __('common.user') }}</th>
                                <th>{{ __('common.action') }}</th>
                                <th>{{ __('admin.model') }}</th>
                                <th>{{ __('common.amount') }}</th>
                                <th>{{ __('admin.method') }}</th>
                                <th>{{ __('admin.ip_address') }}</th>
                                <th>{{ __('common.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <small>{{ $log->created_at->format('Y-m-d H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $log->user?->name ?? __('common.system') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $log->action }}</span>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $log->model_type }}
                                            @if($log->model_id)
                                                <br><code>#{{ $log->model_id }}</code>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($log->amount)
                                            <strong>Rp{{ number_format($log->amount, 0, ',', '.') }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($log->payment_method)
                                            {{ $log->payment_method }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small><code>{{ $log->ip_address }}</code></small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" data-id="{{ $log->id }}"
                                            data-user="{{ $log->user?->name ?? __('common.system') }}"
                                            data-action="{{ $log->action }}" data-model="{{ $log->model_type }}"
                                            data-model-id="{{ $log->model_id }}"
                                            data-amount="{{ $log->amount ? 'Rp' . number_format($log->amount, 0, ',', '.') : '-' }}"
                                            data-method="{{ $log->payment_method ?? '-' }}" data-ip="{{ $log->ip_address }}"
                                            data-agent="{{ $log->user_agent }}"
                                            data-date="{{ $log->created_at->format('Y-m-d H:i:s') }}"
                                            data-changes="{{ json_encode($log->changes ?? []) }}"
                                            data-notes="{{ $log->notes ?? '-' }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        {{ __('admin.no_audit_logs') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                {{ $logs->links() }}
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('admin.filter_audit_log') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="GET" action="{{ route('admin.audit.index') }}">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('admin.start_date') }}</label>
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('admin.end_date') }}</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('common.action') }}</label>
                                <select name="action" class="form-control">
                                    <option value="">-- {{ __('admin.all_actions') }} --</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" @selected(request('action') == $action)>
                                            {{ $action }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('common.user') }}</label>
                                <select name="user_id" class="form-control">
                                    <option value="">-- {{ __('admin.all_users') }} --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                        <a href="{{ route('admin.audit.index') }}"
                            class="btn btn-outline-secondary">{{ __('common.reset') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('admin.apply_filter') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ __('admin.audit_log_detail') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('common.date') }}</label>
                            <p id="detail-date" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('common.user') }}</label>
                            <p id="detail-user" class="mb-0 fw-bold"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('common.action') }}</label>
                            <p id="detail-action" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.model_type') }}</label>
                            <p id="detail-model" class="mb-0 fw-bold"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.model_id') }}</label>
                            <p id="detail-model-id" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('common.amount') }}</label>
                            <p id="detail-amount" class="mb-0 fw-bold"></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.payment_method') }}</label>
                            <p id="detail-method" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small">{{ __('admin.ip_address') }}</label>
                            <p id="detail-ip" class="mb-0 fw-bold"><code></code></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('admin.user_agent') }}</label>
                        <p id="detail-agent" class="mb-0 fw-bold" style="font-size: 0.75rem; word-break: break-all;"></p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('common.notes') }}</label>
                        <p id="detail-notes" class="mb-0 fw-bold"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">{{ __('admin.data_changes') }}</label>
                        <pre id="detail-changes" class="bg-light p-2"
                            style="border-radius: 6px; max-height: 300px; overflow-y: auto;"></pre>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('common.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle detail modal button click
        document.getElementById('detailModal').addEventListener('show.bs.modal', function (e) {
            const button = e.relatedTarget;
            const modal = this;

            // Get data from button attributes
            const date = button.getAttribute('data-date');
            const user = button.getAttribute('data-user');
            const action = button.getAttribute('data-action');
            const model = button.getAttribute('data-model');
            const modelId = button.getAttribute('data-model-id');
            const amount = button.getAttribute('data-amount');
            const method = button.getAttribute('data-method');
            const ip = button.getAttribute('data-ip');
            const agent = button.getAttribute('data-agent');
            const notes = button.getAttribute('data-notes');
            const changes = JSON.parse(button.getAttribute('data-changes') || '{}');

            // Populate modal
            modal.querySelector('#detail-date').textContent = date;
            modal.querySelector('#detail-user').textContent = user;
            modal.querySelector('#detail-action').innerHTML = `<span class="badge bg-secondary">${action}</span>`;
            modal.querySelector('#detail-model').textContent = model;
            modal.querySelector('#detail-model-id').textContent = modelId || '-';
            modal.querySelector('#detail-amount').textContent = amount;
            modal.querySelector('#detail-method').textContent = method;
            modal.querySelector('#detail-ip').innerHTML = `<code>${ip}</code>`;
            modal.querySelector('#detail-agent').textContent = agent;
            modal.querySelector('#detail-notes').textContent = notes;
            modal.querySelector('#detail-changes').textContent = JSON.stringify(changes, null, 2);
        });

        function downloadPdf() {
            const params = new URLSearchParams(window.location.search);
            const url = "{{ route('admin.audit.download-pdf') }}?" + params.toString();
            window.location.href = url;
        }

        function exportCsv() {
            const params = new URLSearchParams(window.location.search);
            const url = "{{ route('admin.audit.export-csv') }}?" + params.toString();
            window.location.href = url;
        }
    </script>

    <style>
        .border-left-primary {
            border-left: 4px solid #007bff !important;
        }

        .border-left-success {
            border-left: 4px solid #28a745 !important;
        }

        .border-left-info {
            border-left: 4px solid #17a2b8 !important;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107 !important;
        }
    </style>
@endsection