@extends('layouts.app')

@section('title', __('menu.settings') ?? 'Settings')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;"><i class="fa-solid fa-gear me-2"></i>{{ __('menu.settings') ?? 'Settings' }}</h2>
                <p class="text-muted mb-0">{{ __('admin.settings_description') ?? 'Manage application features and configuration.' }}</p>
            </div>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    ArtikaToast.fire({
                        icon: 'success',
                        title: "{{ session('success') }}"
                    });
                });
            </script>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <!-- Feature Toggles Card -->
                    <div class="card shadow-sm border-0" style="border-radius: 16px;">
                        <div class="card-header bg-white py-3" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                            <h5 class="mb-0 fw-bold" style="color: #6f5849;">
                                {{ __('admin.feature_management') }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Camera Scanner Toggle -->
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-4 border-bottom">
                                <div class="me-3">
                                    <h6 class="fw-bold mb-1" style="color: #6f5849;">
                                        <i class="fa-solid fa-camera me-2"></i>{{ __('admin.camera_scanner') }}
                                    </h6>
                                    <p class="text-muted small mb-0">{{ __('admin.camera_scanner_hint') }}</p>
                                </div>
                                <div class="form-check form-switch custom-switch">
                                    <input class="form-check-input" type="checkbox" name="enable_camera" id="enable_camera" {{ $settings['enable_camera'] ? 'checked' : '' }} style="cursor: pointer; width: 3.5rem; height: 1.75rem;">
                                </div>
                            </div>

                            <!-- Placeholder for more settings -->
                            <div class="text-center py-3">
                                <p class="text-muted small mb-0 italic">{{ __('admin.more_settings_will_be_available_in_future_updates') }}</p>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-0 py-3 text-end" style="border-radius: 0 0 16px 16px;">
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm" style="border-radius: 12px; font-weight: 600; background: #6f5849; border: none;">
                                <i class="fa-solid fa-floppy-disk me-2"></i>{{ __('common.save_changes') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Helpful Info Card -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #fdf8f6;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color: #6f5849;"><i class="fa-solid fa-circle-info me-2"></i>{{ __('common.info') }}</h6>
                        <p class="small text-muted mb-3">
                            {{ __('admin.settings_applied_here_are_global_and_will_affect_all_admin_manager_and_warehouse_users') }}
                        </p>
                        <div class="alert alert-warning border-0 small mb-0" style="border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: #92400e;">
                            <i class="fa-solid fa-triangle-exclamation me-2"></i>
                            {{ __('admin.disabling_features_may_improve_performance_on_older_devices_but_will_limit_functionality_for_newer_tablets') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-switch .form-check-input:checked {
            background-color: #6f5849;
            border-color: #6f5849;
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.25rem rgba(111, 88, 73, 0.25);
            border-color: #6f5849;
        }
    </style>
@endsection
