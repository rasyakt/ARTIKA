@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-7">
                <div class="d-flex align-items-center mb-1">
                    <a href="{{ route('admin.reports') }}" class="btn btn-outline-brown me-3 shadow-sm"
                        style="border-radius: 10px; padding: 0.5rem 0.75rem;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h1 class="fw-bold mb-0" style="color: #6f5849;">
                        <i class="fas fa-clipboard-list me-2"></i>{{ __('admin.logs_report') }}
                    </h1>
                </div>
                <p class="text-muted mb-0 ms-5 ps-3">{{ __('admin.audit_log_desc') }}</p>
            </div>
            <div class="col-md-5 d-flex gap-2 justify-content-end align-items-center">
                <button class="btn btn-outline-brown shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#filterModal" style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;">
                    <i class="fas fa-filter me-2"></i> {{ __('common.filter') }}
                </button>
                <button class="btn btn-outline-brown shadow-sm" onclick="exportReport('pdf')"
                    style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;">
                    <i class="fas fa-file-pdf me-2"></i> {{ __('admin.download_pdf') }}
                </button>
                <button class="btn btn-brown shadow-sm" onclick="exportReport('print')"
                    style="border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600;">
                    <i class="fas fa-print me-2"></i> {{ __('admin.print_report') }}
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
                                <th>{{ __('admin.ip_address') }}</th>
                                <th>Device</th>
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
                                        <br><small class="text-muted">{{ $log->user?->role->name ?? '' }}</small>
                                        @if($log->user && $log->user->role && $log->user->role->name === 'cashier')
                                            <br><small class="text-muted">
                                                <i class="fa-solid fa-id-card me-1"></i>{{ $log->user->nis ?? '-' }}
                                            </small>
                                            <br><small class="text-muted">
                                                <i class="fa-solid fa-user me-1"></i>{{ $log->user->username ?? '-' }}
                                            </small>
                                        @endif
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
                                        <small><code>{{ $log->ip_address }}</code></small>
                                    </td>
                                    <td>
                                        <small>
                                            <i class="fa-solid fa-desktop"></i> {{ $log->device_name ?? 'Unknown' }}
                                        </small>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#detailModal" data-id="{{ $log->id }}"
                                            style="border-radius: 8px; padding: 0.25rem 0.6rem;"
                                            data-user="{{ $log->user?->name ?? __('common.system') }}"
                                            data-role="{{ $log->user?->role->name ?? '' }}"
                                            data-nis="{{ $log->user?->nis ?? '-' }}"
                                            data-username="{{ $log->user?->username ?? '-' }}"
                                            data-action="{{ $log->action }}" data-model="{{ $log->model_type }}"
                                            data-model-id="{{ $log->model_id }}"
                                            data-amount="{{ $log->amount ? 'Rp' . number_format($log->amount, 0, ',', '.') : '-' }}"
                                            data-method="{{ $log->payment_method ?? '-' }}" 
                                            data-ip="{{ $log->ip_address }}"
                                            data-mac="{{ $log->mac_address ?? 'Not Available' }}"
                                            data-device="{{ $log->device_name ?? 'Unknown Device' }}"
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
            <div class="card-footer bg-light d-flex justify-content-end">
                {{ $logs->links('vendor.pagination.no-prevnext') }}
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
                            <div class="col-md-12">
                                <label class="form-label">Search User (NIS / Username / Name)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control" placeholder="Enter NIS or Username..." value="{{ request('search') }}">
                                </div>
                            </div>
                        </div>
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
                                <label class="form-label">{{ __('common.role') }}</label>
                                <select name="role_id" class="form-control">
                                    <option value="">-- {{ __('admin.all_roles') ?? 'All Roles' }} --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary"
                            data-bs-dismiss="modal" style="border-radius: 10px; padding: 0.6rem 1.25rem;">{{ __('common.cancel') }}</button>
                        <a href="{{ route('admin.audit.index') }}"
                            class="btn btn-light border" style="border-radius: 10px; padding: 0.6rem 1.25rem;">{{ __('common.reset') }}</a>
                        <button type="submit" class="btn btn-primary" style="border-radius: 10px; padding: 0.6rem 1.25rem; font-weight: 600;">{{ __('admin.apply_filter') }}</button>
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
                            <p id="detail-role" class="mb-0 text-muted small"></p>
                        </div>
                    </div>
                    <div class="row mb-3" id="cashier-info-row" style="display: none;">
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="fa-solid fa-id-card me-1"></i>NIS</label>
                            <p id="detail-nis" class="mb-0 fw-bold"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="fa-solid fa-user me-1"></i>Username</label>
                            <p id="detail-username" class="mb-0 fw-bold"></p>
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
                    <h6 class="text-muted mb-3"><i class="fa-solid fa-network-wired me-2"></i>Device Information</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="fa-solid fa-globe me-1"></i>IP Address</label>
                            <p id="detail-ip" class="mb-0 fw-bold"><code></code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small"><i class="fa-solid fa-ethernet me-1"></i>MAC Address</label>
                            <p id="detail-mac" class="mb-0 fw-bold"><code></code></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small"><i class="fa-solid fa-desktop me-1"></i>Device Name</label>
                        <p id="detail-device" class="mb-0 fw-bold"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small"><i class="fa-solid fa-browser me-1"></i>User Agent</label>
                        <p id="detail-agent" class="mb-0 fw-bold" style="font-size: 0.75rem; word-break: break-all;"></p>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="text-muted small"><i class="fa-solid fa-sticky-note me-1"></i>Notes</label>
                        <p id="detail-notes" class="mb-0 fw-bold"></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small"><i class="fa-solid fa-code me-1"></i>Data Changes</label>
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
            const role = button.getAttribute('data-role');
            const nis = button.getAttribute('data-nis');
            const username = button.getAttribute('data-username');
            const action = button.getAttribute('data-action');
            const model = button.getAttribute('data-model');
            const modelId = button.getAttribute('data-model-id');
            const amount = button.getAttribute('data-amount');
            const method = button.getAttribute('data-method');
            const ip = button.getAttribute('data-ip');
            const mac = button.getAttribute('data-mac');
            const device = button.getAttribute('data-device');
            const agent = button.getAttribute('data-agent');
            const notes = button.getAttribute('data-notes');
            const changes = JSON.parse(button.getAttribute('data-changes') || '{}');

            // Populate modal
            modal.querySelector('#detail-date').textContent = date;
            modal.querySelector('#detail-user').textContent = user;
            modal.querySelector('#detail-role').textContent = role;
            
            // Show NIS and username only for cashiers
            const cashierInfoRow = modal.querySelector('#cashier-info-row');
            if (role && role.toLowerCase() === 'cashier') {
                cashierInfoRow.style.display = 'flex';
                modal.querySelector('#detail-nis').textContent = nis;
                modal.querySelector('#detail-username').textContent = username;
            } else {
                cashierInfoRow.style.display = 'none';
            }
            
            modal.querySelector('#detail-action').innerHTML = `<span class="badge bg-secondary">${action}</span>`;
            modal.querySelector('#detail-model').textContent = model;
            modal.querySelector('#detail-model-id').textContent = modelId || '-';
            modal.querySelector('#detail-amount').textContent = amount;
            modal.querySelector('#detail-ip').innerHTML = `<code>${ip}</code>`;
            modal.querySelector('#detail-mac').innerHTML = `<code>${mac}</code>`;
            modal.querySelector('#detail-device').textContent = device;
            modal.querySelector('#detail-agent').textContent = agent;
            modal.querySelector('#detail-notes').textContent = notes;
            modal.querySelector('#detail-changes').textContent = JSON.stringify(changes, null, 2);
        });

        function exportReport(format) {
            const params = new URLSearchParams(window.location.search);
            params.set('format', format);
            if (format === 'print') {
                params.set('auto_print', 'true');
                window.open("{{ route('admin.audit.export') }}?" + params.toString(), '_blank');
            } else {
                window.location.href = "{{ route('admin.audit.export') }}?" + params.toString();
            }
        }
    </script>

    <style>
        .no-print {
            display: none !important;
        }

        .btn-outline-brown {
            color: #6f5849;
            border-color: #6f5849;
        }

        .btn-outline-brown:hover {
            background-color: #6f5849;
            color: white;
        }

        .btn-brown {
            background-color: #6f5849;
            color: white;
        }

        .btn-brown:hover {
            background-color: #5d4a3e;
            color: white;
        }

        @media print {
            .no-print-sidebar {
                display: none !important;
            }

            .container-fluid {
                padding: 0 !important;
            }

            .card {
                border: 1px solid #dee2e6 !important;
                box-shadow: none !important;
            }
            
            .btn-outline-brown, .btn-brown, .modal, .pagination, footer, .navbar, .sidebar {
                display: none !important;
            }
        }
    </style>
@endsection