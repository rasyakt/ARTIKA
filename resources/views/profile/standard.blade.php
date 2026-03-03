@extends('layouts.app')

@section('title', __('common.profile'))

@section('content')
    <div class="container-fluid py-4">
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: var(--color-primary-dark);">
                <i class="fa-solid fa-user me-2"></i>{{ __('common.profile') }}
            </h2>
            <p class="text-muted mb-0">{{ __('common.profile_subtitle') }}</p>
        </div>
        @include('profile.partials')
    </div>
@endsection