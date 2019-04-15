@foreach ($related_products as $rp)
    <div class="product">
        <a href="{{ route('show-product', $rp->slug) }}" class="show-product">
            <img src="{{ asset('uploads/' . $rp->store->id . '/products/' . $rp->images->first()->image) }}" class="image" alt="{{ $rp->title }}" />
        </a>

        <div class="infos">
            <a href="{{ route('show-product', $rp->slug) }}" class="show-product">
                @if ($rp->free_freight)
                    <div class="free-freight">
                        <span>FRETE GRÁTIS</span>
                    </div>
                @endif

                @if ($rp->old_price)
                    <span class="old-price">de <span>{{ number_format($rp->old_price, 2, ',', '.') }}</span></span>
                @endif

                <span class="price"><span>R$</span> {{ number_format($rp->price, 2, ',', '.') }}</span>

                @if ($rp->old_price)
                    <span class="price-off">{{ _discount($rp->price, $rp->old_price) }}% OFF</span>
                @endif

                <span class="parcels">
                    {{ $rp->showParcels($rp) }}
                </span>

                <p class="title" title="{{ $rp->title }}">{{ $rp->title }}</p>
            </a>

            <a href="{{ route('show-store', $rp->store->slug) }}" class="store-name">{{ $rp->store->name }}</a>
        </div>
    </div>
@endforeach

@if ($related_products->lastPage() > 1 && $related_products->currentPage() < $related_products->lastPage())
    <div class="pagination">
        <a href="{{ route('related-products', [$product, true]) }}?page={{ $related_products->currentPage() + 1 }}">Exibir mais</a>
    </div>
@endif
