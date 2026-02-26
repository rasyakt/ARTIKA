@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 px-3 px-lg-4">
        {{-- Header --}}
        <div
            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-4 gap-2">
            <div>
                <h4 class="fw-800 mb-1" style="color: var(--color-primary-dark);">
                    <i class="fa-solid fa-circle-question me-2" style="color: var(--color-primary);"></i>
                    Pusat Bantuan
                </h4>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Temukan jawaban untuk pertanyaan yang sering diajukan
                </p>
            </div>
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
        <div class="d-flex flex-wrap gap-2 mb-4" id="categoryFilter">
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
                        <i class="fa-solid fa-folder-open me-2"></i>{{ \App\Models\Faq::CATEGORIES[$category] ?? $category }}
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
                                            <i class="fa-solid fa-chevron-right faq-arrow" style="transition: transform 0.3s;"></i>
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

        {{-- No Results State (hidden initially) --}}
        <div class="text-center py-5" id="noResults" style="display: none;">
            <i class="fa-solid fa-search mb-3" style="font-size: 3rem; color: var(--color-primary); opacity: 0.4;"></i>
            <h5 class="fw-700" style="color: var(--color-primary-dark);">Tidak Ditemukan</h5>
            <p class="text-muted">Coba ubah kata kunci pencarian Anda.</p>
        </div>
    </div>

    <style>
        .category-pill {
            background: var(--card-bg, #fff);
            border: 1px solid var(--brown-100);
            color: var(--color-text, #555);
            transition: all 0.25s;
        }

        .category-pill:hover {
            border-color: var(--color-primary);
            color: var(--color-primary);
            background: var(--brown-50);
        }

        .category-pill.active {
            background: var(--color-primary-dark) !important;
            border-color: var(--color-primary) !important;
            color: white !important;
        }

        .faq-toggle:not(.collapsed) .faq-arrow {
            transform: rotate(90deg);
        }

        .faq-toggle:not(.collapsed) .faq-icon {
            background: var(--color-primary) !important;
            color: white !important;
        }

        .faq-item {
            transition: all 0.2s;
        }

        .faq-item:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
        }

        .faq-item.search-hidden {
            display: none !important;
        }

        .faq-category-group.search-hidden {
            display: none !important;
        }

        .faq-item .faq-question-text mark {
            background: rgba(var(--color-primary-rgb, 139, 90, 43), 0.15);
            color: var(--color-primary);
            padding: 0 2px;
            border-radius: 3px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('faqSearch');
            const faqItems = document.querySelectorAll('.faq-item');
            const categoryGroups = document.querySelectorAll('.faq-category-group');
            const noResults = document.getElementById('noResults');
            const faqContainer = document.getElementById('faqContainer');
            const categoryPills = document.querySelectorAll('.category-pill');
            let activeCategory = 'all';

            // Search
            searchInput.addEventListener('input', function () {
                filterFaqs();
            });

            // Category Filter
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
                            groupVisible++;
                            visibleCount++;
                        } else {
                            item.classList.add('search-hidden');
                        }
                    });

                    if (groupVisible > 0) {
                        group.classList.remove('search-hidden');
                    } else {
                        group.classList.add('search-hidden');
                    }
                });

                if (noResults) noResults.style.display = visibleCount === 0 ? 'block' : 'none';
                if (faqContainer) faqContainer.style.display = visibleCount === 0 ? 'none' : 'block';
            }
        });
    </script>
@endsection