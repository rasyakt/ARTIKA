@extends('layouts.app')

@section('content')
    <style>
        .alert-card {
            border-radius: 16px;
            border: none;
            overflow: hidden;
            transition: all 0.3s;
        }

        .alert-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(133, 105, 90, 0.15);
        }

        .stock-badge {
            padding: 0.5rem 0.75rem;
            border- radius: 8px;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: #6f5849;"><i
                    class="fa-solid fa-triangle-exclamation me-2"></i>{{ __('warehouse.low_stock_alerts') }}</h2>
            <p class="text-muted mb-0">{{ __('warehouse.low_stock_description') }}</p>
        </div>

        <!-- Alert Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card alert-card shadow-sm border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div style="font-size: 2.5rem;"><i class="fa-solid fa-circle text-danger"></i></div>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-1">{{ __('warehouse.critical_stock') }}</h6>
                                <h3 class="mb-0 text-danger fw-bold">
                                    {{ $criticalCount }}
                                </h3>
                                <small class="text-muted">{{ __('warehouse.below_10_units') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card alert-card shadow-sm border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div style="font-size: 2.5rem;"><i class="fa-solid fa-circle text-warning"></i></div>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-1">{{ __('warehouse.low_stock_label') }}</h6>
                                <h3 class="mb-0 text-warning fw-bold">
                                    {{ $lowCount }}
                                </h3>
                                <small class="text-muted">{{ __('warehouse.10_19_units') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card alert-card shadow-sm border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div style="font-size: 2.5rem;"><i class="fa-solid fa-chart-pie"></i></div>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-1">{{ __('warehouse.total_alerts') }}</h6>
                                <h3 class="mb-0 fw-bold" style="color: #6f5849;">
                                    {{ $totalAlerts }}
                                </h3>
                                <small class="text-muted">{{ __('warehouse.requires_attention') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts Table -->
        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i
                        class="fa-solid fa-clipboard-list me-2"></i>{{ __('warehouse.alert_list') }}</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #fdf8f6;">
                            <tr>
                                <th class="border-0 fw-semibold ps-4" style="color: #6f5849;">{{ __('common.product') }}
                                </th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('common.category') }}</th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.current_stock') }}
                                </th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.min_stock') }}
                                </th>
                                <th class="border-0 fw-semibold" style="color: #6f5849;">{{ __('warehouse.alert_level') }}
                                </th>
                                <th class="border-0 fw-semibold text-center" style="color: #6f5849;">
                                    {{ __('common.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockItems as $stock)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold" style="color: #6f5849;">{{ $stock->product->name }}</div>
                                        <small class="text-muted">{{ $stock->product->barcode }}</small>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #e0cec7; color: #6f5849;">
                                            {{ $stock->product->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $stock->quantity < 10 ? 'text-danger' : 'text-warning' }}">
                                            {{ $stock->quantity }} {{ __('warehouse.units') }}
                                        </span>
                                    </td>
                                    <td>{{ $stock->min_stock }}</td>
                                    <td>
                                        @if($stock->quantity < 10)
                                            <span class="badge bg-danger stock-badge"><i
                                                    class="fa-solid fa-circle me-1"></i>{{ __('warehouse.critical') }}</span>
                                        @else
                                            <span class="badge bg-warning stock-badge"><i
                                                    class="fa-solid fa-circle me-1"></i>{{ __('warehouse.low') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('warehouse.stock', ['stock_id' => $stock->id]) }}" class="btn btn-sm btn-outline-primary"
                                            style="border-radius: 8px;">
                                            <i class="fa-solid fa-gear me-1"></i> {{ __('warehouse.adjust_stock') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div style="font-size: 4rem; opacity: 0.2;"><i class="fa-solid fa-circle-check"></i>
                                        </div>
                                        <p class="text-muted mb-0">{{ __('warehouse.all_stock_sufficient') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($lowStockItems->hasPages())
                <div class="card-footer bg-white d-flex justify-content-end" style="border-top: 2px solid #f2e8e5;">
                    {{ $lowStockItems->links('vendor.pagination.custom-brown') }}
                </div>
            @endif
        </div>
    </div>
@endsection