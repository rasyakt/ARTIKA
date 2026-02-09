@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="fw-bold" style="color: #6f5849;">
                    <i class="fa-solid fa-gears me-2"></i>Advanced System Settings
                </h4>
                <p class="text-muted">Configure global system behavior and feature access per role.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('superadmin.settings.update') }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <div class="col-lg-3">
                    <!-- Tabs Navigation -->
                    <div class="card border-0 shadow-sm sticky-top" style="border-radius: 16px; top: 20px;">
                        <div class="card-body p-3">
                            <div class="nav flex-column nav-pills" id="settings-tabs" role="tablist">
                                @foreach($categories as $categoryName => $fields)
                                    <button class="nav-link @if($loop->first) active @endif mb-2 text-start d-flex align-items-center" 
                                            id="tab-{{ Str::slug($categoryName) }}" 
                                            data-bs-toggle="pill" 
                                            data-bs-target="#content-{{ Str::slug($categoryName) }}" 
                                            type="button" 
                                            role="tab"
                                            style="border-radius: 10px; font-weight: 600; padding: 12px 16px;">
                                        <i class="fa-solid @if($categoryName == 'General') fa-display @elseif($categoryName == 'Admin Features') fa-user-shield @elseif($categoryName == 'Warehouse Features') fa-boxes-stacked @elseif($categoryName == 'Cashier Features') fa-cash-register @else fa-microchip @endif me-3"></i>
                                        {{ $categoryName }}
                                    </button>
                                @endforeach
                            </div>
                            <hr class="my-3" style="border-color: #f2e8e5;">
                            <button type="submit" class="btn btn-primary w-100 py-3 shadow-sm" style="background: #6f5849; border: none; border-radius: 12px; font-weight: 700;">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Save Settings
                            </button>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <!-- Tabs Content -->
                    <div class="tab-content" id="settings-content">
                        @foreach($categories as $categoryName => $fields)
                            <div class="tab-pane fade @if($loop->first) show active @endif" 
                                 id="content-{{ Str::slug($categoryName) }}" 
                                 role="tabpanel">
                                
                                <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                                    <div class="card-header bg-white py-4 px-4" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                                        <h5 class="mb-0 fw-bold" style="color: #6f5849;">{{ $categoryName }} Settings</h5>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="row">
                                            @foreach($fields as $key => $config)
                                                <div class="col-12 mb-4">
                                                    @if($config['type'] === 'boolean')
                                                        <div class="d-flex justify-content-between align-items-center p-3 bg-light" style="border-radius: 12px;">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold">{{ $config['label'] }}</h6>
                                                                <p class="text-muted small mb-0">Enable or disable this module for the respective role.</p>
                                                            </div>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" name="{{ $key }}" id="{{ $key }}" 
                                                                       @if($settings->get($key, 'true') === 'true') checked @endif
                                                                       style="width: 3.5rem; height: 1.75rem; cursor: pointer;">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <label class="form-label fw-bold text-muted small text-uppercase mb-2">{{ $config['label'] }}</label>
                                                        <input type="{{ $config['type'] }}" class="form-control form-control-lg" name="{{ $key }}" 
                                                               value="{{ $settings->get($key, $config['default']) }}"
                                                               style="border-radius: 12px; border: 2px solid #f2e8e5; padding: 14px 20px;"
                                                               @if($config['type'] === 'number') min="0" @endif>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .nav-pills .nav-link {
            color: #85695a;
            transition: all 0.2s ease;
        }
        .nav-pills .nav-link:hover {
            background-color: #fdf8f6;
            color: #6f5849;
        }
        .nav-pills .nav-link.active {
            background-color: #6f5849 !important;
            color: white !important;
            box-shadow: 0 4px 12px rgba(111, 88, 73, 0.2);
        }
        .form-check-input:checked {
            background-color: #6f5849;
            border-color: #6f5849;
        }
    </style>
@endsection
