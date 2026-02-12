@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4>Welcome, {{ $user?->name }}!</h4>
                    <p>Role: <span class="badge bg-info">{{ $user?->role?->name ?? 'User' }}</span></p>
                    <div class="alert alert-info mt-3">
                        Select a menu to proceed.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection