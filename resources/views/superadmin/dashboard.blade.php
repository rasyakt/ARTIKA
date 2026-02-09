@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold" style="color: #6f5849;">
                    <i class="fa-solid fa-code me-2"></i>Developer Tools
                </h4>
                <p class="text-muted">High-level system management and technical utilities.</p>
            </div>
        </div>

        @if($systemInfo['is_maintenance'])
            <div class="alert alert-warning border-0 shadow-sm mb-4"
                style="border-radius: 12px; border-left: 5px solid #ffc107;">
                <h6 class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-2"></i>Maintenance Mode is Active</h6>
                <p class="small mb-0">The application is currently locked. To access the site, you must use your secret token.
                    If you lose it, check the latest entries in the <strong>System Logs</strong> or run
                    <code>php artisan up</code> in the terminal.</p>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="row g-4">
            <!-- System Info Board -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="card-header bg-white py-3"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                            <i class="fa-solid fa-server me-2"></i>System Information
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-light" style="border-radius: 12px;">
                                    <label class="text-muted small d-block mb-1">Laravel Version</label>
                                    <span class="fw-bold">{{ $systemInfo['laravel_version'] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-light" style="border-radius: 12px;">
                                    <label class="text-muted small d-block mb-1">PHP Version</label>
                                    <span class="fw-bold">{{ $systemInfo['php_version'] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-light" style="border-radius: 12px;">
                                    <label class="text-muted small d-block mb-1">Environment</label>
                                    <span
                                        class="badge {{ $systemInfo['environment'] === 'production' ? 'bg-danger' : 'bg-success' }}"
                                        style="border-radius: 8px;">
                                        {{ strtoupper($systemInfo['environment']) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-light" style="border-radius: 12px;">
                                    <label class="text-muted small d-block mb-1">Database Type</label>
                                    <span class="fw-bold text-uppercase">{{ $systemInfo['db_connection'] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-light" style="border-radius: 12px;">
                                    <label class="text-muted small d-block mb-1">Database Version</label>
                                    <span class="fw-bold small">{{ $systemInfo['db_version'] }}</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4">
                                <div class="p-3 bg-light" style="border-radius: 12px;">
                                    <label class="text-muted small d-block mb-1">Server OS</label>
                                    <span class="fw-bold">{{ $systemInfo['server_os'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 p-2 text-center">
                            <small class="text-muted">
                                <i class="fa-solid fa-clock me-1"></i>Server Time: {{ $systemInfo['server_time'] }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Developer Actions Row -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                            <div class="card-body p-4 text-center">
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 64px; height: 64px; color: #6f5849;">
                                    <i class="fa-solid fa-broom fa-2x"></i>
                                </div>
                                <h5 class="fw-bold mb-2">System Cache</h5>
                                <p class="text-muted small mb-4">Clear all application, config, route, and view caches.</p>
                                <form action="{{ route('superadmin.clear-cache') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary w-100"
                                        style="border-radius: 12px;">
                                        Clear Cache
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                            <div class="card-body p-4 text-center">
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 64px; height: 64px; color: #6f5849;">
                                    <i class="fa-solid fa-bolt fa-2x"></i>
                                </div>
                                <h5 class="fw-bold mb-2">Optimize</h5>
                                <p class="text-muted small mb-4">Cache configuration and routes for production speed.</p>
                                <form action="{{ route('superadmin.optimize') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success w-100"
                                        style="border-radius: 12px;">
                                        Run Optimize
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Database Overview -->
                <div class="card border-0 shadow-sm mt-4" style="border-radius: 16px;">
                    <div class="card-header bg-white py-3"
                        style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                        <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                            <i class="fa-solid fa-database me-2"></i>Database Overview
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light sticky-top">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted small text-uppercase fw-bold">Table Name</th>
                                        <th class="py-3 text-muted small text-uppercase fw-bold text-end pe-4">Total Records
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dbStats as $stat)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="fw-medium">{{ $stat['name'] }}</span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <span class="badge bg-light text-dark px-3 py-2"
                                                    style="border-radius: 8px; font-size: 0.9rem;">
                                                    {{ number_format($stat['count']) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3 text-center" style="border-radius: 0 0 16px 16px;">
                        <small class="text-muted">Total Tables: {{ count($dbStats) }}</small>
                    </div>
                </div>
            </div>

            <!-- Right Side: Maintenance & Logs -->
            <div class="col-lg-4">
                <!-- Maintenance Mode -->
                <div class="card border-0 shadow-sm mb-4 {{ $systemInfo['is_maintenance'] ? 'bg-danger text-white' : '' }}"
                    style="border-radius: 16px;">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle {{ $systemInfo['is_maintenance'] ? 'bg-white text-danger' : 'bg-light text-danger' }} d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 64px; height: 64px;">
                            <i class="fa-solid fa-power-off fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Maintenance Mode</h5>
                        <p class="small mb-4 {{ $systemInfo['is_maintenance'] ? 'text-white-50' : 'text-muted' }}">
                            {{ $systemInfo['is_maintenance']
        ? 'The application is currently offline for users.'
        : 'Lock access to the application for all users except developers.' }}
                        </p>
                        <form action="{{ route('superadmin.toggle-maintenance') }}" method="POST">
                            @csrf
                            @if($systemInfo['is_maintenance'])
                                <button type="submit" class="btn btn-light w-100 fw-bold"
                                    style="border-radius: 12px; color: #dc3545;">
                                    Go Live Now
                                </button>
                            @else
                                <button type="submit" class="btn btn-danger w-100" style="border-radius: 12px;"
                                    onclick="return confirm('Enable maintenance mode? All public users will be locked out.')">
                                    Enable Maintenance
                                </button>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Logs Link Card -->
                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3"
                            style="width: 64px; height: 64px; color: #6f5849;">
                            <i class="fa-solid fa-terminal fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-2">System Logs</h5>
                        <p class="text-muted small mb-4">Monitor errors and developer-level debug messages.</p>
                        <a href="{{ route('superadmin.logs') }}" class="btn btn-outline-dark w-100"
                            style="border-radius: 12px;">
                            View Latest Logs
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection