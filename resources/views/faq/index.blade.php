@php /** @var \App\Models\User $user */ $user = Auth::user(); @endphp
<!DOCTYPE html>
<html lang="id">

<head>
    {{-- Apply theme ASAP to prevent flash --}}
    <script>
        (function () {
            const saved = localStorage.getItem('artika-theme') || 'system';
            if (saved === 'dark' || (saved === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            }
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Bantuan - ARTIKA POS</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    {!! \App\Helpers\ThemeHelper::getCssVariables(\App\Models\Setting::get('site_color_theme', 'brown')) !!}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: var(--color-primary);
            --primary-dark: var(--color-primary-dark);
            --primary-light: var(--color-primary-light);
            --brown-50: var(--brown-50);
            --gray-100: var(--color-bg);
            --gray-200: var(--gray-200);
            --gray-700: var(--gray-600);
            --gray-800: var(--gray-700);
        }

        body {
            background-color: var(--gray-100);
            font-family: 'Inter', sans-serif;
            color: var(--color-text, var(--gray-800));
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* NAVBAR */
        .pos-navbar {
            background: var(--primary-dark);
            color: white;
            padding: 0.75rem 1.5rem;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(133, 105, 90, 0.15);
            z-index: 1050;
            position: sticky;
            top: 0;
        }

        .navbar-brand {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .profile-trigger {
            transition: all 0.25s;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            background: none;
            border: none;
            display: flex;
            align-items: center;
        }

        .profile-trigger:hover {
            background: var(--color-primary) !important;
        }

        .profile-avatar {
            width: 40px;
            height: 40px;
            background: var(--color-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.4rem;
            border: 2px solid var(--color-primary-light);
            transition: all 0.2s;
        }

        .dropdown-menu {
            border: 1px solid rgba(133, 105, 90, 0.1);
            animation: slideIn 0.2s ease-out;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item:hover {
            background-color: var(--brown-50);
            color: var(--primary-dark);
        }

        /* Back Button */
        .btn-back-pos {
            border-radius: 10px;
            padding: 0.4rem 0.8rem;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            font-size: 0.75rem;
            transition: all 0.2s;
        }

        .btn-back-pos:hover {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            transform: translateY(-1px);
        }

        /* FAQ STYLES */
        .category-pill {
            background: var(--card-bg, #fff);
            border: 1px solid var(--gray-200);
            color: var(--color-text, #555);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .category-pill:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
            background: var(--brown-50);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(133, 105, 90, 0.1);
        }

        .category-pill.active {
            background: var(--color-primary-dark) !important;
            border-color: var(--color-primary) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(133, 105, 90, 0.2);
        }

        .faq-toggle:not(.collapsed) .faq-arrow {
            transform: rotate(90deg);
        }

        .faq-toggle:not(.collapsed) .faq-icon {
            background: var(--color-primary) !important;
            color: white !important;
            transform: scale(1.05);
            box-shadow: 0 4px 10px rgba(133, 105, 90, 0.3);
        }

        .faq-icon {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .faq-item {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            background: var(--card-bg, #fff);
            border: 1px solid rgba(133, 105, 90, 0.05) !important;
        }

        .faq-item:hover {
            box-shadow: 0 8px 25px rgba(133, 105, 90, 0.12) !important;
            transform: translateY(-2px);
            border-color: rgba(133, 105, 90, 0.15) !important;
        }

        @keyframes fadeUpIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .faq-item-animate {
            animation: fadeUpIn 0.4s ease-out forwards;
            opacity: 0;
        }

        .faq-item.search-hidden {
            display: none !important;
        }

        .faq-category-group.search-hidden {
            display: none !important;
        }

        /* Dark Mode Overrides */
        [data-bs-theme="dark"] .card {
            background: var(--card-bg);
        }

        [data-bs-theme="dark"] .dropdown-menu {
            background-color: var(--card-bg);
            border-color: var(--gray-200);
        }

        [data-bs-theme="dark"] .dropdown-item {
            color: var(--color-text);
        }

        [data-bs-theme="dark"] .dropdown-item:hover {
            background-color: var(--gray-100);
        }
    </style>
</head>

<body>
    <div class="pos-navbar">
        <div class="d-flex align-items-center gap-3">
            @if(strtolower($user->role->name ?? '') === 'cashier')
                <a href="{{ route('pos.index') }}" class="btn-back-pos d-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Kembali ke POS</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="btn-back-pos d-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span class="d-none d-sm-inline">Kembali ke Dasbor</span>
                </a>
            @endif
            <div class="navbar-brand">Pusat Bantuan</div>
        </div>

        <div class="navbar-right">
            <div class="dropdown">
                <button class="profile-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-end d-none d-sm-block">
                            <div class="fw-800 text-white line-height-1" style="font-size: 0.9rem;">
                                {{ $user->name }}
                            </div>
                            <div class="text-white opacity-75 fw-600" style="font-size: 0.75rem;">
                                {{ $user->role->name ?? 'User' }}
                            </div>
                        </div>
                        <div class="profile-avatar">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                    style="width: 100%; height: 100%; border-radius: 8px; object-fit: cover;">
                            @else
                                <i class="fa-solid fa-user-tie"></i>
                            @endif
                        </div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end p-2 border-0 shadow-lg"
                    style="width: 280px; border-radius: 15px;">
                    <li>
                        <a href="{{ route('profile') }}"
                            class="dropdown-item px-3 py-3 border-bottom mb-2 d-flex align-items-center gap-3"
                            style="white-space: normal;">
                            <div class="profile-avatar shadow-sm" style="width: 45px; height: 45px; min-width: 45px;">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                        style="width: 100%; height: 100%; border-radius: 8px; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-user-tie"></i>
                                @endif
                            </div>
                            <div class="overflow-hidden text-start">
                                <div class="fw-800 text-dark text-truncate" style="font-size: 0.95rem;">
                                    {{ $user->name }}
                                </div>
                                <div class="text-muted fw-600 text-truncate" style="font-size: 0.75rem;">
                                    {{ $user->nis ?? '-' }}
                                </div>
                            </div>
                            <i class="fa-solid fa-chevron-right ms-auto text-muted opacity-50"
                                style="font-size: 0.8rem;"></i>
                        </a>
                    </li>
                    <div class="px-3 py-2">
                        <div class="fw-700 text-uppercase mb-2 text-primary"
                            style="font-size: 0.65rem; letter-spacing: 0.05em;">
                            {{ __('common.settings') ?? 'Pengaturan' }}
                        </div>
                        <div class="px-3 py-2">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="small fw-600 text-muted"><i class="fa-solid fa-circle-half-stroke me-2"
                                        id="posThemeIcon"></i>Tema</span>
                            </div>
                            <div class="btn-group w-100" role="group">
                                <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt"
                                    data-theme="light" title="Light">
                                    <i class="fa-solid fa-sun"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt"
                                    data-theme="dark" title="Dark">
                                    <i class="fa-solid fa-moon"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary pos-theme-opt"
                                    data-theme="system" title="System">
                                    <i class="fa-solid fa-desktop"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @if(strtolower($user->role->name ?? '') === 'cashier')
                        <li class="border-top mt-2 pt-2">
                            <div class="fw-700 text-uppercase px-3 mb-1 text-primary"
                                style="font-size: 0.65rem; letter-spacing: 0.05em;">
                                Menu POS
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="{{ route('pos.history') }}">
                                <i class="fa-solid fa-clock-rotate-left me-3 text-primary opacity-75"></i>
                                <span class="fw-600">Riwayat Transaksi</span>
                            </a>
                        </li>
                        @if(\App\Models\Setting::get('cashier_enable_audit_logs', true))
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center" href="{{ route('pos.logs') }}">
                                    <i class="fa-solid fa-list-check me-3 text-primary opacity-75"></i>
                                    <span class="fw-600">Log Aktivitas</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    <li>
                        <a class="dropdown-item py-2 px-3 d-flex align-items-center active"
                            href="{{ route('faq.index') }}"
                            style="background: var(--brown-50); color: var(--primary-dark);">
                            <i class="fa-solid fa-circle-question me-3 text-primary"></i>
                            <span class="fw-600">Bantuan / FAQ</span>
                        </a>
                    </li>
                    <li class="border-top mt-1">
                        <button type="button" class="dropdown-item py-3 px-3 d-flex align-items-center text-danger"
                            id="btnLogout">
                            <i class="fas fa-sign-out-alt me-3"></i>
                            <span class="fw-700">Keluar Sistem</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="container mt-4 pb-5">
        {{-- Header Info --}}
        <div class="mb-4 text-center">
            <h4 class="fw-800 mb-1" style="color: var(--color-primary-dark);">
                <i class="fa-solid fa-circle-question me-2" style="color: var(--color-primary);"></i>
                Pusat Bantuan
            </h4>
            <p class="text-muted mb-0">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
        </div>

        {{-- Search Bar --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
            <div class="card-body p-3 p-lg-4" style="background: var(--color-primary-dark);">
                <div class="position-relative mx-auto" style="max-width: 600px;">
                    <i class="fa-solid fa-magnifying-glass position-absolute"
                        style="left: 16px; top: 50%; transform: translateY(-50%); color: var(--color-primary); font-size: 1rem; z-index: 2;"></i>
                    <input type="text" id="faqSearch" class="form-control border-0 shadow-sm"
                        placeholder="Cari pertanyaan atau kata kunci..." autocomplete="off"
                        style="padding: 0.875rem 1rem 0.875rem 2.75rem; border-radius: 12px; font-size: 0.95rem; background: var(--card-bg, #fff);">
                </div>
            </div>
        </div>

        {{-- Category Filter --}}
        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4" id="categoryFilter">
            <button class="btn btn-sm category-pill active" data-category="all"
                style="border-radius: 20px; padding: 0.5rem 1.25rem; font-weight: 600; font-size: 0.825rem;">
                <i class="fa-solid fa-layer-group me-1"></i> Semua
            </button>
            @foreach($categories as $key => $label)
                <button class="btn btn-sm category-pill" data-category="{{ $key }}"
                    style="border-radius: 20px; padding: 0.5rem 1.25rem; font-weight: 600; font-size: 0.825rem;">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- FAQ Content --}}
        <div id="faqContainer">
            @forelse($faqs as $category => $items)
                <div class="faq-category-group mb-4" data-category="{{ $category }}">
                    <h6 class="fw-700 text-uppercase mb-3"
                        style="font-size: 0.75rem; letter-spacing: 0.05em; color: var(--color-primary);">
                        <i
                            class="fa-solid fa-folder-open me-2"></i>{{ \App\Models\Faq::CATEGORIES[$category] ?? $category }}
                    </h6>
                    <div class="accordion" id="accordion-{{ $category }}">
                        @foreach($items as $faq)
                            <div class="faq-item card border-0 shadow-sm mb-2" style="border-radius: 12px; overflow: hidden;"
                                data-question="{{ strtolower($faq->question) }}"
                                data-answer="{{ strtolower(strip_tags($faq->answer)) }}">
                                <div class="card-header bg-transparent border-0 p-0" id="heading-{{ $faq->id }}">
                                    <button class="btn w-100 text-start d-flex align-items-center gap-3 collapsed faq-toggle"
                                        type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $faq->id }}"
                                        aria-expanded="false" aria-controls="collapse-{{ $faq->id }}"
                                        style="padding: 1rem 1.25rem; font-weight: 600; color: var(--color-text, #333); font-size: 0.925rem;">
                                        <span class="faq-icon d-flex align-items-center justify-content-center shrink-0"
                                            style="width: 32px; height: 32px; border-radius: 8px; background: var(--brown-50); color: var(--color-primary); font-size: 0.85rem;">
                                            <i class="fa-solid fa-chevron-right faq-arrow"
                                                style="transition: transform 0.3s;"></i>
                                        </span>
                                        <span class="faq-question-text">{{ $faq->question }}</span>
                                    </button>
                                </div>
                                <div id="collapse-{{ $faq->id }}" class="collapse" aria-labelledby="heading-{{ $faq->id }}">
                                    <div class="card-body pt-0 pb-3 px-4" style="margin-left: 56px;">
                                        <div class="faq-answer"
                                            style="font-size: 0.9rem; line-height: 1.7; color: var(--color-text-muted, #555);">
                                            {!! nl2br(e($faq->answer)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-5" id="emptyState">
                    <i class="fa-solid fa-face-smile-wink mb-3"
                        style="font-size: 3rem; color: var(--color-primary); opacity: 0.5;"></i>
                    <h5 class="fw-700" style="color: var(--color-primary-dark);">Belum Ada FAQ</h5>
                    <p class="text-muted">Daftar pertanyaan akan segera tersedia.</p>
                </div>
            @endforelse
        </div>

        {{-- No Results State --}}
        <div class="text-center py-5" id="noResults" style="display: none;">
            <i class="fa-solid fa-search mb-3" style="font-size: 3rem; color: var(--color-primary); opacity: 0.4;"></i>
            <h5 class="fw-700" style="color: var(--color-primary-dark);">Tidak Ditemukan</h5>
            <p class="text-muted">Coba ubah kata kunci pencarian Anda.</p>
        </div>
    </div>

    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // FAQ Logic
            const searchInput = document.getElementById('faqSearch');
            const faqItems = document.querySelectorAll('.faq-item');
            const categoryGroups = document.querySelectorAll('.faq-category-group');
            const noResults = document.getElementById('noResults');
            const faqContainer = document.getElementById('faqContainer');
            const categoryPills = document.querySelectorAll('.category-pill');
            let activeCategory = 'all';

            searchInput.addEventListener('input', filterFaqs);
            categoryPills.forEach(pill => {
                pill.addEventListener('click', function () {
                    categoryPills.forEach(p => p.classList.remove('active'));
                    this.classList.add('active');
                    activeCategory = this.dataset.category;
                    filterFaqs();
                });
            });

            function filterFaqs() {
                const query = searchInput.value.toLowerCase().trim();
                let visibleCount = 0;
                let delay = 0;

                categoryGroups.forEach(group => {
                    const groupCategory = group.dataset.category;
                    const items = group.querySelectorAll('.faq-item');
                    let groupVisible = 0;

                    items.forEach(item => {
                        const question = item.dataset.question;
                        const answer = item.dataset.answer;
                        const matchesSearch = !query || question.includes(query) || answer.includes(query);
                        const matchesCategory = activeCategory === 'all' || groupCategory === activeCategory;

                        if (matchesSearch && matchesCategory) {
                            item.classList.remove('search-hidden');

                            // Trigger animation
                            item.classList.remove('faq-item-animate');
                            void item.offsetWidth; // trigger reflow
                            item.classList.add('faq-item-animate');
                            item.style.animationDelay = `${delay}s`;
                            delay += 0.05;

                            groupVisible++;
                            visibleCount++;
                        } else {
                            item.classList.add('search-hidden');
                            item.classList.remove('faq-item-animate');
                            item.style.animationDelay = '0s';
                        }
                    });

                    group.classList.toggle('search-hidden', groupVisible === 0);
                });

                noResults.style.display = visibleCount === 0 ? 'block' : 'none';
                faqContainer.style.display = visibleCount === 0 ? 'none' : 'block';

                // Animate no-results if shown
                if (visibleCount === 0) {
                    noResults.classList.remove('faq-item-animate');
                    void noResults.offsetWidth;
                    noResults.classList.add('faq-item-animate');
                }
            }

            // Initial animation run
            filterFaqs();

            // Logout confirmation handler
            document.getElementById('btnLogout')?.addEventListener('click', function () {
                Swal.fire({
                    title: 'Keluar Sistem?',
                    text: "Apakah Anda yakin ingin keluar?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--color-primary-dark)',
                    cancelButtonColor: 'var(--gray-100)',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-primary px-4 py-2 me-3',
                        cancelButton: 'btn btn-light px-4 py-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logoutForm').submit();
                    }
                });
            });

            // Theme Toggle Script
            (function () {
                const opts = document.querySelectorAll('.pos-theme-opt');
                const icon = document.getElementById('posThemeIcon');
                const htmlEl = document.documentElement;
                function getEffective(p) { return p === 'system' ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light') : p; }
                function apply(p) {
                    htmlEl.setAttribute('data-bs-theme', getEffective(p));
                    localStorage.setItem('artika-theme', p);
                    const icons = { light: 'fa-sun', dark: 'fa-moon', system: 'fa-desktop' };
                    if (icon) icon.className = 'fa-solid ' + (icons[p] || 'fa-sun');
                    opts.forEach(o => {
                        const chk = o.querySelector('.pos-theme-check');
                        if (o.dataset.theme === p) { o.classList.add('active'); chk?.classList.remove('d-none'); }
                        else { o.classList.remove('active'); chk?.classList.add('d-none'); }
                    });
                }
                apply(localStorage.getItem('artika-theme') || 'system');
                opts.forEach(o => o.addEventListener('click', () => apply(o.dataset.theme)));
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                    if (localStorage.getItem('artika-theme') === 'system') apply('system');
                });
            })();
        });
    </script>
</body>

</html>