@if($paginator->lastPage() > 1)
    <div class="pagination">
        @if($paginator->currentPage() > 1)
            <a class="prev" href="{{ $paginator->previousPageUrl() }}">Anterior</a>
        @else
            <a class="disabled prev">Anterior</a>
        @endif

        <span>{{ $paginator->currentPage() }}</span>

        @if($paginator->currentPage() < $paginator->lastPage())
            <a class="next" href="{{ $paginator->nextPageUrl() }}">Próxima</a>
        @else
            <a class="disabled next">Próxima</a>
        @endif
    </div>
@endif
