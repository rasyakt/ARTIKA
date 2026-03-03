@php
    /** @var \App\Models\User $user */
    $user = Auth::user();
@endphp

<ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-0 overflow-hidden"
    style="min-width: 280px; border-radius: 16px;">
    <li>
        <a href="{{ route('profile') }}" class="dropdown-item p-3 bg-light border-bottom d-flex align-items-center"
            style="white-space: normal;">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
                style="width: 48px; height: 48px; font-size: 1.3rem; min-width: 48px;">
                {{ substr($user?->name ?? '', 0, 1) }}
            </div>
            <div class="overflow-hidden text-start">
                <h6 class="mb-0 fw-800 text-truncate text-dark" style="font-size: 1rem;">{{ $user?->name }}</h6>
                <div class="small text-muted text-truncate" style="font-size: 0.8rem;">@ {{ $user?->username }}</div>
                <div class="small fw-700 text-primary mt-1" style="font-size: 0.75rem;">
                    {{ $user?->identity_type->label ?? 'NIS' }}: {{ $user?->nis ?? '-' }}
                </div>
            </div>
            <i class="fa-solid fa-chevron-right ms-auto opacity-50" style="font-size: 0.8rem;"></i>
        </a>
    </li>

    {{-- Section: Settings --}}
    <div class="p-3 border-bottom">
        <div class="mb-3">
            <div class="small fw-bold text-uppercase text-muted mb-2"
                style="font-size: 0.7rem; letter-spacing: 0.05em;">
                {{ __('common.settings') ?? 'Pengaturan' }}
            </div>

            {{-- Theme Selector --}}
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small fw-600 text-muted"><i class="fa-solid fa-circle-half-stroke me-2"></i>Tema</span>
            </div>
            <div class="btn-group w-100 mb-3" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt" data-theme="light"
                    title="Light">
                    <i class="fa-solid fa-sun"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt" data-theme="dark"
                    title="Dark">
                    <i class="fa-solid fa-moon"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt" data-theme="system"
                    title="System">
                    <i class="fa-solid fa-desktop"></i>
                </button>
            </div>

            {{-- Language Selector --}}
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small fw-600 text-muted"><i class="fa-solid fa-globe me-2"></i>Bahasa</span>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('language.change', 'en') }}"
                    class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-secondary' }} flex-fill py-2 fw-600">English</a>
                <a href="{{ route('language.change', 'id') }}"
                    class="btn btn-sm {{ app()->getLocale() == 'id' ? 'btn-primary' : 'btn-outline-secondary' }} flex-fill py-2 fw-600">Indonesia</a>
            </div>
        </div>
    </div>

    {{-- Menu Items --}}
    <div class="py-2">
        <a class="dropdown-item py-2 px-3 d-flex align-items-center {{ request()->routeIs('pos.history') ? 'active bg-primary text-white' : '' }}"
            href="{{ route('pos.history') }}">
            <i
                class="fa-solid fa-clock-rotate-left me-3 {{ request()->routeIs('pos.history') ? 'text-white' : 'text-primary opacity-75' }}"></i>
            <span class="fw-600">Riwayat Transaksi</span>
        </a>

        @if(App\Models\Setting::get('cashier_enable_audit_logs', true))
            <a class="dropdown-item py-2 px-3 d-flex align-items-center {{ request()->routeIs('pos.logs') ? 'active bg-primary text-white' : '' }}"
                href="{{ route('pos.logs') }}">
                <i
                    class="fa-solid fa-list-check me-3 {{ request()->routeIs('pos.logs') ? 'text-white' : 'text-primary opacity-75' }}"></i>
                <span class="fw-600">Log Aktivitas</span>
            </a>
        @endif

        @if(\App\Models\Setting::get('enable_faq', true))
            <a class="dropdown-item py-2 px-3 d-flex align-items-center {{ request()->routeIs('faq.index') ? 'active bg-primary text-white' : '' }}"
                href="{{ route('faq.index') }}">
                <i
                    class="fa-solid fa-circle-question me-3 {{ request()->routeIs('faq.index') ? 'text-white' : 'text-primary opacity-75' }}"></i>
                <span class="fw-600">Bantuan / FAQ</span>
            </a>
        @endif
    </div>

    <div class="border-top mt-1">
        <button type="button" class="dropdown-item py-3 px-3 d-flex align-items-center text-danger fw-700"
            id="btnLogout">
            <i class="fas fa-sign-out-alt me-3"></i>
            <span>Keluar Sistem</span>
        </button>
    </div>
</ul>