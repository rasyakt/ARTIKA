@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            {{-- Render page number links only --}}
            @for ($page = 1; $page <= $paginator->lastPage(); $page++)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                @endif
            @endfor
        </ul>
    </nav>
@endif
