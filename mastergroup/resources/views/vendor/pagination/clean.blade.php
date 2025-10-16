@if ($paginator->hasPages())
<nav class="pager" role="navigation" aria-label="Pagination">
    {{-- Prev --}}
    @if ($paginator->onFirstPage())
        <span class="pager__btn is-disabled" aria-disabled="true" aria-label="Previous">
            ‹
        </span>
    @else
        <a class="pager__btn" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous">
            ‹
        </a>
    @endif

    {{-- Numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="pager__gap" aria-hidden="true">…</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="pager__num is-current" aria-current="page">{{ $page }}</span>
                @else
                    <a class="pager__num" href="{{ $url }}" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a class="pager__btn" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next">
            ›
        </a>
    @else
        <span class="pager__btn is-disabled" aria-disabled="true" aria-label="Next">
            ›
        </span>
    @endif
</nav>
@endif
