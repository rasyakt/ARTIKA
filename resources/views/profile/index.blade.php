@php 
        /** @var \App\Models\User $user */
    $user = Auth::user();
    $role = strtolower($user->role->name ?? '');
@endphp

@if($role === 'cashier')
    @include('profile.cashier')
@else
    @include('profile.standard')
@endif