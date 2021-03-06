@foreach ($related_products as $rp)
    <div class="product">
        <a href="{{ route('show-product', $rp->slug) }}" class="show-product">
            <img src="{{ asset('uploads/' . $rp->store->id . '/products/' . $rp->images->first()->image) }}" class="image" alt="{{ $rp->title }}" />

            <div class="infos">
                @if ($rp->free_freight)
                    <div class="free-freight">
                        <span>FRETE GRÁTIS</span>
                    </div>
                @endif

                @if ($rp->offtime && _checkDateOff($rp->offtime->created_at, $rp->offtime->time))
                    <span class="old-price">de <span>{{ number_format($rp->price, 2, ',', '.') }}</span></span>
                @elseif ($rp->off)
                    <span class="old-price">de <span>{{ number_format(_oldPrice($rp->price, $rp->off), 2, ',', '.') }}</span></span>
                @endif

                <span class="price">
                    <span>R$</span>
                    {{ number_format(($rp->offtime && _checkDateOff($rp->offtime->created_at, $rp->offtime->time)) ? _priceOff($rp->price, $rp->offtime->off) : $rp->price, 2, ',', '.') }}
                </span>

                @if ($rp->offtime && _checkDateOff($rp->offtime->created_at, $rp->offtime->time))
                    <span class="price-off">{{ $rp->offtime->off }}% OFF</span>
                @elseif ($rp->off)
                    <span class="price-off">{{ $rp->off }}% OFF</span>
                @endif

                <span class="parcels">
                    {{ $rp->showParcels($rp) }}
                </span>

                <p class="title" title="{{ $rp->title }}">{{ $rp->title }}</p>

                @if ($rp->offtime && _checkDateOff($rp->offtime->created_at, $rp->offtime->time))
                    <span class="offtime" data-date="{{ date('Y-m-d H:i:s', strtotime('+' . $rp->offtime->time . ' hours', strtotime($rp->offtime->created_at))) }}"></span>
                @endif
            </div>
        </a>
    </div>
@endforeach

@if ($related_products->lastPage() > 1 && $related_products->currentPage() < $related_products->lastPage())
    <div class="pagination">
        <a href="{{ route('related-products', [$product, true]) }}?page={{ $related_products->currentPage() + 1 }}">Exibir mais</a>
    </div>
@endif
