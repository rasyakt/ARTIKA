@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4>Welcome, {{ Auth::user()->name }}!</h4>
                <p>Role: <span class="badge bg-info">{{ Auth::user()->role->name ?? 'User' }}</span></p>
                <div class="alert alert-info mt-3">
                    Select a menu to proceed.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
