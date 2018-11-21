@if($paginator->lastPage() > 1)
    <div class="pagination">
        @if($paginator->currentPage() > 1)
            <a class="prev" href="{{ $paginator->previousPageUrl() }}">Página anterior</a>
        @else
            <a class="disabled prev">Página anterior</a>
        @endif

        <span>{{ $paginator->currentPage() }}</span>

        @if($paginator->currentPage() < $paginator->lastPage())
            <a class="next" href="{{ $paginator->nextPageUrl() }}">Próxima página</a>
        @else
            <a class="disabled next">Próxima página</a>
        @endif
    </div>
@endif
