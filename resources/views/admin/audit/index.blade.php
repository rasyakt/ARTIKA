@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1><i class="fas fa-clipboard-list"></i> Audit Log</h1>
            <p class="text-muted">Catat semua aktivitas dan transaksi untuk keperluan audit</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn btn-success" onclick="downloadPdf()">
                <i class="fas fa-file-pdf"></i> Download PDF
            </button>
            <button class="btn btn-info" onclick="exportCsv()">
                <i class="fas fa-file-csv"></i> Export CSV
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Total Logs</div>
                    <div class="h3 mb-0">{{ $logs->total() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">Halaman</div>
                    <div class="h3 mb-0">{{ $logs->currentPage() }} / {{ $logs->lastPage() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-1">Periode</div>
                    <div class="small">{{ request('start_date') ?? 'All' }} - {{ request('end_date') ?? 'Today' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">User</div>
                    <div class="small">{{ Auth::user()->name }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Daftar Activity Logs</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Model</th>
                            <th>Amount</th>
                            <th>Metode</th>
                            <th>IP Address</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <small>{{ $log->created_at->format('Y-m-d H:i:s') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $log->user?->name ?? 'System' }}</span>
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
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewDetails({{ $log->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    Tidak ada audit log ditemukan
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
                <h5 class="modal-title">Filter Audit Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="GET" action="{{ route('audit.index') }}">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Awal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Aksi</label>
                            <select name="action" class="form-control">
                                <option value="">-- Semua Aksi --</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" @selected(request('action') == $action)>
                                        {{ $action }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">User</label>
                            <select name="user_id" class="form-control">
                                <option value="">-- Semua User --</option>
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="{{ route('audit.index') }}" class="btn btn-outline-secondary">Reset</a>
                    <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function downloadPdf() {
        const params = new URLSearchParams(window.location.search);
        const url = "{{ route('audit.download-pdf') }}?" + params.toString();
        window.location.href = url;
    }

    function exportCsv() {
        const params = new URLSearchParams(window.location.search);
        const url = "{{ route('audit.export-csv') }}?" + params.toString();
        window.location.href = url;
    }

    function viewDetails(id) {
        alert('Detail view coming soon!');
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
