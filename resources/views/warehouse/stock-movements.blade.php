@extends('layouts.app')

@section('content')
    <style>
        .movement-card {
            border-radius: 16px;
            border: none;
            overflow: hidden;
        }

        .movement-type-badge {
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
        }

        .timeline-item {
            position: relative;
            padding-left: 3rem;
            padding-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0.875rem;
            top: 2rem;
            bottom: 0;
            width: 2px;
            background: #e0cec7;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 0;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: #6f5849;"><i
                        class="fa-solid fa-arrows-rotate me-2"></i>{{ __('warehouse.stock_movements') }}</h2>
                <p class="text-muted mb-0">{{ __('warehouse.track_stock_changes') }}</p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card movement-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div style="font-size: 2.5rem;"><i class="fa-solid fa-arrow-down me-1"></i></div>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-1">{{ __('warehouse.stock_in_today') }}</h6>
                                <h3 class="mb-0 text-success fw-bold">{{ $stockInToday }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card movement-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div style="font-size: 2.5rem;"><i class="fa-solid fa-arrow-up me-1"></i></div>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-1">{{ __('warehouse.stock_out_today') }}</h6>
                                <h3 class="mb-0 text-danger fw-bold">{{ $stockOutToday }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card movement-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div style="font-size: 2.5rem;"><i class="fa-solid fa-gear"></i></div>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-1">{{ __('warehouse.adjustments_today') }}</h6>
                                <h3 class="mb-0 fw-bold" style="color: #6f5849;">{{ $adjustmentsToday }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card movement-card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div style="font-size: 2.5rem;"><i class="fa-solid fa-chart-pie"></i></div>
                            </div>
                            <div class="ms-3">
                                <h6 class="text-muted mb-1">{{ __('warehouse.total_movements') }}</h6>
                                <h3 class="mb-0 fw-bold" style="color: #6f5849;">{{ $totalMovements }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Movement Timeline -->
        <div class="card shadow-sm" style="border-radius: 16px; border: none;">
            <div class="card-header bg-white" style="border-bottom: 2px solid #f2e8e5; border-radius: 16px 16px 0 0;">
                <h5 class="mb-0 fw-bold" style="color: #6f5849;"><i
                        class="fa-solid fa-scroll me-2"></i>{{ __('warehouse.recent_movements') }}</h5>
            </div>
            <div class="card-body">
                @if($recentMovements->count() > 0)
                    @foreach($recentMovements as $movement)
                        <div class="timeline-item">
                            <div
                                class="timeline-dot {{ $movement->type === 'in' ? 'bg-success' : ($movement->type === 'out' ? 'bg-danger' : 'bg-warning') }}">
                                {!! $movement->type === 'in' ? '<i class="fa-solid fa-arrow-down text-white"></i>' : ($movement->type === 'out' ? '<i class="fa-solid fa-arrow-up text-white"></i>' : '<i class="fa-solid fa-gear text-white"></i>') !!}
                            </div>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1 fw-bold" style="color: #6f5849;">
                                                {{ $movement->product->name }}
                                            </h6>
                                            <small class="text-muted">{{ $movement->product->barcode }}</small>
                                        </div>
                                        <span
                                            class="movement-type-badge {{ $movement->type === 'in' ? 'bg-success' : ($movement->type === 'out' ? 'bg-danger' : 'bg-warning') }} text-white">
                                            {{ $movement->type === 'in' ? '+ ' : ($movement->type === 'out' ? '- ' : '') }}{{ abs($movement->quantity_change) }}
                                            {{ __('warehouse.units') }}
                                        </span>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <small class="text-muted">{{ __('warehouse.adjustment_type') }}:</small>
                                            <div class="fw-semibold text-capitalize">
                                                {{ $movement->type === 'in' ? __('warehouse.stock_in') : ($movement->type === 'out' ? __('warehouse.stock_out') : __('warehouse.adjustment')) }}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">{{ __('warehouse.by') }}:</small>
                                            <div class="fw-semibold">{{ $movement->user->name }}</div>
                                        </div>
                                    </div>
                                    @if($movement->reason)
                                        <div class="mt-2 p-2 bg-light rounded">
                                            <small class="text-muted">{{ __('warehouse.reason') }}: {{ $movement->reason }}</small>
                                        </div>
                                    @endif
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fa-solid fa-clock me-1"></i> {{ $movement->created_at->diffForHumans() }}
                                            ({{ $movement->created_at->format('d M Y, H:i') }})
                                            • {{ __('warehouse.reference') }}: {{ $movement->reference }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($recentMovements->hasPages())
                        <div class="mt-4 d-flex justify-content-end">
                            {{ $recentMovements->links('vendor.pagination.custom-brown') }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div style="font-size: 4rem; opacity: 0.2;"><i class="fa-solid fa-clipboard"></i></div>
                        <p class="text-muted mb-0">{{ __('warehouse.no_movements') }}</p>
                        <small class="text-muted">{{ __('warehouse.no_movements_desc') }}</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection