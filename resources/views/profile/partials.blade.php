@php /** @var \App\Models\User $user */ @endphp
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: '{{ __('common.success') }}',
                text: '{{ session('success') }}',
                customClass: { popup: 'artika-swal-popup', confirmButton: 'artika-swal-confirm-btn' },
                buttonsStyling: false
            });
        });
    </script>
@endif

@if($errors->any())
    <div class="alert alert-danger" style="border-radius: 12px;">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row g-4">
    <!-- Account Information -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-white py-3 border-bottom" style="border-radius: 16px 16px 0 0;">
                <h6 class="mb-0 fw-bold" style="color: var(--color-primary-dark);">
                    <i class="fa-solid fa-address-card me-2"></i>{{ __('common.account_info') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm"
                        style="width: 80px; height: 80px; font-size: 2rem; background: var(--color-primary) !important;">
                        {{ substr($user->name ?? 'U', 0, 1) }}
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">{{ __('common.full_name') }}</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->name ?? '' }}" readonly disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">{{ __('common.username') }}</label>
                    <input type="text" class="form-control bg-light" value="{{ $user->username ?? '' }}" readonly
                        disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">{{ __('common.role') }}</label>
                    <input type="text" class="form-control bg-light text-uppercase fw-bold text-primary"
                        value="{{ $role }}" readonly disabled>
                </div>

                @if($user->nis)
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">{{ $user->identity_type->label ?? __('common.nis') }}</label>
                        <input type="text" class="form-control bg-light" value="{{ $user->nis }}" readonly disabled>
                    </div>
                @endif

                <div class="alert alert-info mt-4"
                    style="background: var(--brown-50); color: var(--color-primary-dark); border: none; border-radius: 10px;">
                    <i class="fa-solid fa-circle-info me-2"></i>
                    {{ __('common.contact_admin_to_change_info') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Security / Change Password -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white py-3 border-bottom" style="border-radius: 16px 16px 0 0;">
                <h6 class="mb-0 fw-bold" style="color: var(--color-primary-dark);">
                    <i class="fa-solid fa-lock me-2"></i>{{ __('common.security') }}
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ url('profile/password') }}" method="POST">
                    @csrf
                    @method('PUT')



                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">{{ __('common.new_password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <input type="password" name="new_password" id="new_password"
                                class="form-control border-start-0 border-end-0 ps-0"
                                placeholder="{{ __('common.new_password_placeholder') }}" required minlength="8">
                            <span class="input-group-text bg-white border-start-0 cursor-pointer toggle-password"
                                data-target="new_password">
                                <i class="fa-solid fa-eye text-muted"></i>
                            </span>
                        </div>
                        <div class="form-text mt-2"><i class="fa-solid fa-circle-info me-1"></i>
                            {{ __('common.password_rule') }}</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">{{ __('common.confirm_new_password') }}</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fa-solid fa-check-double"></i>
                            </span>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="form-control border-start-0 border-end-0 ps-0"
                                placeholder="{{ __('common.confirm_password_placeholder') }}" required minlength="8">
                            <span class="input-group-text bg-white border-start-0 cursor-pointer toggle-password"
                                data-target="new_password_confirmation">
                                <i class="fa-solid fa-eye text-muted"></i>
                            </span>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">
                            <i class="fa-solid fa-floppy-disk me-2"></i>{{ __('common.save_password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .toggle-password {
        cursor: pointer;
        transition: all 0.2s;
    }

    .toggle-password:hover {
        background-color: var(--gray-100) !important;
    }

    .toggle-password:hover i {
        color: var(--color-primary) !important;
    }

    /* Input Group Focus Effects */
    .input-group {
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
    }

    .input-group .input-group-text,
    .input-group .form-control {
        border-color: var(--gray-200);
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .input-group:focus-within {
        box-shadow: 0 0 0 0.2rem rgba(133, 105, 90, 0.15);
        border-radius: 10px;
    }

    .input-group:focus-within .input-group-text,
    .input-group:focus-within .form-control {
        border-color: var(--color-primary-light) !important;
    }

    .input-group-text {
        background-color: #fff !important;
    }

    [data-bs-theme="dark"] .input-group-text {
        background-color: var(--gray-100) !important;
        border-color: var(--gray-200) !important;
    }

    .form-control:focus {
        box-shadow: none !important;
        /* Managed by input-group focus-within */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = document.querySelectorAll('.toggle-password');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>