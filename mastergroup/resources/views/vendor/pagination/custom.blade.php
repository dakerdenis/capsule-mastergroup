@if ($paginator->hasPages())
    <nav class="pagination__wrapper">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination__arrow disabled" aria-hidden="true">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination__arrow" aria-label="Previous">
                ‹
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="pagination__dots">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pagination__page active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination__page">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination__arrow" aria-label="Next">
                ›
            </a>
        @else
            <span class="pagination__arrow disabled" aria-hidden="true">
                ›
            </span>
        @endif
    </nav>
@endif
