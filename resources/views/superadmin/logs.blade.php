@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold" style="color: #6f5849;">
                        <i class="fa-solid fa-terminal me-2"></i>System Logs
                    </h4>
                    <p class="text-muted mb-0">Showing filtered **ERROR** entries from storage/logs/laravel.log</p>
                </div>
                <div class="d-flex gap-3 align-items-center">
                    <div class="position-relative">
                        <i class="fa-solid fa-search position-absolute text-muted" style="top: 12px; left: 15px;"></i>
                        <input type="text" id="logSearch" class="form-control ps-5" placeholder="Search logs..."
                            style="border-radius: 12px; width: 300px;">
                    </div>
                    <a href="{{ route('superadmin.dashboard') }}" class="btn btn-outline-secondary"
                        style="border-radius: 12px;">
                        <i class="fa-solid fa-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-0">
                <pre class="m-0 p-4 text-white bg-dark" id="logContainer"
                    style="border-radius: 16px; max-height: 70vh; overflow-y: auto; font-family: 'Courier New', Courier, monospace; font-size: 0.85rem; line-height: 1.5;">@php
                        $lines = explode("\n", $logs);
                        foreach ($lines as $line) {
                            if (trim($line)) {
                                echo '<span class="log-line">' . htmlspecialchars($line) . '</span>' . "\n";
                            }
                        }
                    @endphp</pre>
            </div>
            <div class="card-footer bg-white border-0 py-3 text-center" style="border-radius: 0 0 16px 16px;">
                <p class="text-muted small mb-0" id="logStatus">End of log file.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var pre = document.getElementById('logContainer');
            var searchInput = document.getElementById('logSearch');
            var logLines = document.getElementsByClassName('log-line');
            var logStatus = document.getElementById('logStatus');

            // Auto-scroll to bottom initially
            pre.scrollTop = pre.scrollHeight;

            searchInput.addEventListener('input', function () {
                var term = this.value.toLowerCase();
                var visibleCount = 0;

                for (var i = 0; i < logLines.length; i++) {
                    var line = logLines[i];
                    if (line.textContent.toLowerCase().includes(term)) {
                        line.style.display = 'inline'; // Use inline to maintain spacing
                        visibleCount++;
                    } else {
                        line.style.display = 'none';
                    }
                }

                if (term) {
                    logStatus.textContent = 'Found ' + visibleCount + ' matching lines.';
                    logStatus.classList.add('text-primary', 'fw-bold');
                } else {
                    logStatus.textContent = 'End of log file.';
                    logStatus.classList.remove('text-primary', 'fw-bold');
                    pre.scrollTop = pre.scrollHeight;
                }
            });
        });
    </script>
    <style>
        .log-line {
            display: block;
            min-height: 1.5em;
        }
    </style>
@endsection