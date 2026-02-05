@if ($paginator->hasPages())
    <nav class="d-flex justify-content-center mt-4">
        <ul class="pagination pagination-custom shadow-sm p-1 bg-white rounded-pill mb-0"
            style="border: 1px solid #f2e8e5;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-pill border-0 px-3 py-2" style="background: transparent; color: #bfa094;">
                        <i class="fa-solid fa-chevron-left me-2"></i>Sebelumnya
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-pill border-0 px-3 py-2" href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        style="background: transparent; color: #85695a; transition: all 0.2s;">
                        <i class="fa-solid fa-chevron-left me-2"></i>Sebelumnya
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link border-0" style="background: transparent; color: #bfa094;">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link rounded-circle fw-bold d-flex align-items-center justify-content-center"
                                    style="width: 38px; height: 38px; background: linear-gradient(135deg, #85695a 0%, #6f5849 100%); border: none; color: white;">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-circle d-flex align-items-center justify-content-center mx-1" href="{{ $url }}"
                                    style="width: 38px; height: 38px; background: transparent; border: none; color: #85695a; transition: all 0.2s;">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-pill border-0 px-3 py-2" href="{{ $paginator->nextPageUrl() }}" rel="next"
                        style="background: transparent; color: #85695a; transition: all 0.2s;">
                        Berikutnya<i class="fa-solid fa-chevron-right ms-2"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-pill border-0 px-3 py-2" style="background: transparent; color: #bfa094;">
                        Berikutnya<i class="fa-solid fa-chevron-right ms-2"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif