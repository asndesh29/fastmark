<style>
    .pagination li {
        display: inline-block;
        margin: 0 5px;
    }
    .pagination li a {
        padding: 8px 12px;
        border: 1px solid #ddd;
        color: #007bff;
        text-decoration: none;
    }
    .pagination li.active a {
        background-color: #007bff;
        color: #fff;
    }
    .pagination li.disabled a {
        color: #6c757d;
        cursor: not-allowed;
    }
</style>

@if ($paginator->hasPages())
    <nav>
        <ul class="pagination">
            <li class="{{ ($paginator->currentPage() === 1) ? 'disabled' : '' }}">
                <a href="{{ $paginator->previousPageUrl() }}">Previous</a>
            </li>
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="{{ ($page == $paginator->currentPage()) ? 'active' : '' }}">
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach
            <li class="{{ ($paginator->hasMorePages()) ? '' : 'disabled' }}">
                <a href="{{ $paginator->nextPageUrl() }}">Next</a>
            </li>
        </ul>
    </nav>
@endif