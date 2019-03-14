<div class="col-xs-7">
    <div id="images">
        <div id="image-destaque">
            <img src="{{ asset('uploads/' . $product->store_id . '/products/' . _originalImage($product->images->first()->image)) }}" id="photo-zoom" />
        </div>

        <div id="image-thumbs">
            @foreach ($product->images as $image)
                <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $image->image) }}" class="image-thumb" />
            @endforeach
        </div>
    </div>

    <div id="product-messages">
        {!! Form::open(['method' => 'POST', 'id' => 'form-question-message', 'route' => 'create-client-message']) !!}
            <h3>Fale com a loja...</h3>
            <span>Você também receberá a resposta por e-mail</span>

            <span class="message-counter">300</span>

            {!! Form::hidden('product_id', $product->id) !!}

            {!! Form::textarea('message', null, ['placeholder' => 'Digite sua mensagem aqui', 'maxlength' => '300']) !!}

            {!! Form::submit('ENVIAR MENSAGEM') !!}
        {!! Form::close() !!}

        @if ($product->messages->count() > 0)
            <div id="list-product-messages">
                @foreach ($product->messages as $message)
                    <div class="conversation">
                        <div class="client-message">
                            <h4>{{ $message->client->name }}</h4>

                            <span>{{ _dateFormat($message->created_at) }}</span>

                            <p>{{ $message->question }}</p>
                        </div>

                        @if ($message->response)
                            <div class="store-message">
                                <span>{{ _dateFormat($message->answered_at) }}</span>

                                <h4>{{ $message->product->store->name }}</h4>

                                <p>{{ $message->response }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="col-xs-5">
    <span class="advice">TROCA FÁCIL DIRETAMENTE NA LOJA</span>

    <span class="identifier">Produto #{{ $product->identifier }}</span>

    <div class="store-container">
        <span>Vendido e entregue por <a href="{{ route('show-store', $product->store->slug) }}">{{ $product->store->name }}</a></span>

        <p class="store-address">{{ $product->store->street }}, {{ $product->store->number }} - {{ $product->store->district }} - {{ $product->store->city->title }}/{{ $product->store->city->state->letter }}</p>

        <a class="store-map" href="//maps.google.com/?q={{ $product->store->street }}, {{ $product->store->number }}, {{ $product->store->district }}, {{ $product->store->city->title }}, {{ $product->store->city->state->letter }}" target="_blank">
            ver no mapa
        </a>
    </div>

    <h1 class="product-title">{{ $product->title }}</h1>

    <div class="price-container">
        @if ($product->old_price)
            <span class="old-price">de <span>{{ number_format($product->old_price, 2, ',', '.') }}</span></span>
        @endif

        <span class="price" title="{{ ($product->reserve && $product->reserve_discount) ? 'Preço normal do produto' : '' }}"><span>R$</span> {{ number_format($product->price, 2, ',', '.') }}</span>

        @if ($product->old_price)
            <span class="price-off">{{ _discount($product->price, $product->old_price) }}% OFF</span>
        @endif

        @if ($product->installment && $product->installment_price)
            <span class="parcels">
                em até {{ $product->installment }}x de R$ {{ number_format($product->installment_price, 2, ',', '.') }}
                {{ _taxes($product->installment, $product->installment_price, $product->price) }}
            </span>
        @endif
    </div>

    <div class="freights-container">
        <span class="freight-selected">Entrega a partir de 2hs em toda a cidade</span>

        <select title="Calcular frete" class="freights selectpicker" autocomplete="off" data-live-search="true" data-live-search-placeholder="Pesquise aqui">
            <option value="0.00">Retirar na loja</option>
            @foreach ($product->store->freights as $store_freight)
                <option value="{{ $store_freight->price }}">{{ $store_freight->district->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="qtd-container">
        <span class="label-select">Quantidade:</span>

        {!! Form::select('qtd', $qtd, null, ['class' => 'qtd selectpicker', 'autocomplete' => 'off']) !!}
    </div>

    <div class="size-container">
        <span>Tamanhos disponíveis</span>

        @foreach ($product->sizes as $size)
            <div class="size">
                {!! Form::radio('size', $size->size, false, ['autocomplete' => 'off', 'id' => 'size_' . $size->size]) !!}
                {!! Form::label('size_' . $size->size, $size->size) !!}
            </div>
        @endforeach
    </div>

    <div class="rating-container">
        @isset($product_rating->rating)
            <div class="rating-value-container">
                <span class="rating-value">{{ $product_rating->rating }}</span>
                <span class="rating-number">({{ $product_rating->rating_number }} {{ $product_rating->rating_number > 1 ? ' avaliações' : ' avaliação' }})</span>
            </div>
        @endisset

        {!! Form::open(['method' => 'post', 'route' => 'rating-product', 'class' => 'rating', 'id' => 'form-rating-product']) !!}
            {!! Form::hidden('product_id', $product->id) !!}

            @for ($i = 5; $i >= 1; $i--)
    			{!! Form::radio('rating', $i, @!empty($i == $client_rating->rating), ['id' => 'rating' . $i, 'required']) !!}
    			{!! Form::label('rating' . $i, ' ') !!}
            @endfor
        {!! Form::close() !!}

        <span class="rating-title">avalie este produto</span>
    </div>

    <div class="btn-container">
        <button type="button" class="bag-add-product" data-url="{{ route('bag-add-product') }}" data-productid="{{ $product->id }}">COLOCAR NA SACOLA</button>

        <button type="button" class="bag-add-product redirect" data-url="{{ route('bag-add-product') }}" data-productid="{{ $product->id }}">PEDIR AGORA</button>
    </div>

    @if ($more_colors->count() > 0)
        <div class="more-colors-container">
            <span>Mais cores</span>

            @foreach ($more_colors as $more_color)
                <a href="{{ route('show-product', $more_color->slug) }}" class="show-product">
                    <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $more_color->images->first()->image) }}" />
                </a>
            @endforeach
        </div>
    @endif

    @if ($product->description)
        <div class="description-container">
            <span>Descrição do produto</span>

            <p>{{ $product->description }}</p>
        </div>
    @endif
</div>

@if ($related_products->count() > 0)
    <div class="col-xs-12 related-products">
        <h3 class="title-related">Produtos relacionados</h3>

        <div class="list-products">
            @include('related-products')
        </div>
    </div>
@endif
