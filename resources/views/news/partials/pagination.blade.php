@if ($paginator->hasPages())
    <ul class="news-pagination-list">
        @if ($paginator->onFirstPage())
            <li><span class="news-pagination-item is-disabled" aria-disabled="true">上一页</span></li>
        @else
            <li><a class="news-pagination-item" href="{{ $paginator->previousPageUrl() }}" rel="prev">上一页</a></li>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <li><span class="news-pagination-item is-ellipsis">{{ $element }}</span></li>
            @else
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li><span class="news-pagination-item is-active" aria-current="page">{{ $page }}</span></li>
                    @else
                        <li><a class="news-pagination-item" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <li><a class="news-pagination-item" href="{{ $paginator->nextPageUrl() }}" rel="next">下一页</a></li>
        @else
            <li><span class="news-pagination-item is-disabled" aria-disabled="true">下一页</span></li>
        @endif
    </ul>
@endif
