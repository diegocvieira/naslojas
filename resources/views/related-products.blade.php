@foreach ($related_products as $rp)
    <div class="product">
        <a href="{{ route('show-product', $rp->slug) }}" class="show-product">
            <img src="{{ asset('uploads/' . $rp->store->id . '/products/' . $rp->images->first()->image) }}" class="image" alt="{{ $rp->title }}" />
        </a>

        <div class="infos">
            <a href="{{ route('show-product', $rp->slug) }}" class="show-product">
                @if ($rp->reserve && $rp->reserve_discount)
                    <span class="reserve" title="R$ {{ number_format(_reservePrice($rp->price, $rp->reserve_discount), 2, ',', '.') }} na reserva pelo naslojas"><span>DESCONTO NA RESERVA</span></span>
                @endif

                @if ($rp->old_price)
                    <span class="old-price">de R$ {{ number_format($rp->old_price, 2, ',', '.') }}</span>
                @endif

                <span class="price" title="{{ ($rp->reserve && $rp->reserve_discount) ? 'Preço normal cobrado pela loja' : '' }}"><span>R$</span> {{ number_format($rp->price, 2, ',', '.') }}</span>

                @if ($rp->old_price)
                    <span class="price-off">{{ _discount($rp->price, $rp->old_price) }}% OFF</span>
                @endif

                @if ($rp->installment && $rp->installment_price)
                    <span class="parcels">
                        em até {{ $rp->installment }}x de R$ {{ number_format($rp->installment_price, 2, ',', '.') }}
                        {{ _taxes($rp->installment, $rp->installment_price, $rp->price) }}
                    </span>
                @endif

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
