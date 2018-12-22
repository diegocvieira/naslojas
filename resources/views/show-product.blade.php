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
    <span class="identifier">#{{ $product->identifier }}</span>

    <div class="store-container">
        <span>Você encontra este produto na loja</span>

        <a href="{{ route('show-store', $product->store->slug) }}" class="store-show">{{ $product->store->name }}</a>

        <p class="store-address">{{ $product->store->street }}, {{ $product->store->number }} - {{ $product->store->district }} - {{ $product->store->city->title }}/{{ $product->store->city->state->letter }}</p>

        <a class="store-map" href="//maps.google.com/?q={{ $product->store->street }}, {{ $product->store->number }}, {{ $product->store->district }}, {{ $product->store->city->title }}, {{ $product->store->city->state->letter }}" target="_blank">
            ver no mapa
        </a>
    </div>

    <div class="price-container">
        @if ($product->old_price)
            <span class="old-price">de R$ {{ number_format($product->old_price, 2, ',', '.') }}</span>
        @endif

        <span class="price"><span>R$</span> {{ number_format($product->price, 2, ',', '.') }}</span>

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

    @if ($product->sizes->count() > 0)
        <div class="size-container">
            <span>Tamanhos disponíveis</span>

            @foreach ($product->sizes as $size)
                <div class="size">
                    {!! Form::checkbox('size', $size->size, false, ['autocomplete' => 'off', 'id' => 'size_' . $size->size]) !!}
                    {!! Form::label('size_' . $size->size, $size->size) !!}
                </div>
            @endforeach
        </div>
    @endif

    <h1 class="product-title">{{ $product->title }}</h1>

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
        <button type="button" class="btn-product-confirm" data-url="{{ route('create-product-confirm') }}" data-productid="{{ $product->id }}">CONFIRMAR</button>
        <span class="btn-tooltip">Confirmar se o produto ainda está disponível.</span>

        @if($product->store->reserve)
            <button type="button" class="btn-product-reserve" data-url="{{ route('create-product-reserve') }}" data-productid="{{ $product->id }}">RESERVAR</button>
            <span class="btn-tooltip">Você só precisa ir até a loja e informar o seu nome.</span>
        @else
            <button type="button" class="btn-disabled">RESERVA DESABILITADA</button>
            <span class="btn-tooltip">A loja desabilitou a reserva deste produto.</span>
        @endif
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
    <div class="col-xs-12 related_products">
        <h3 class="title-related">Produtos relacionados</h3>

        <div class="list-products">
            @foreach ($related_products as $rp)
                <div class="product">
                    <a href="{{ route('show-product', $rp->slug) }}" class="show-product">
                        <img src="{{ asset('uploads/' . $rp->store->id . '/products/' . $rp->images->first()->image) }}" class="image" alt="{{ $rp->title }}" />
                    </a>

                    <div class="infos">
                        <a href="{{ route('show-product', $rp->slug) }}" class="show-product">
                            @if ($rp->old_price)
                                <span class="old-price">de R$ {{ number_format($rp->old_price, 2, ',', '.') }}</span>
                            @endif

                            <span class="price"><span>R$</span> {{ number_format($rp->price, 2, ',', '.') }}</span>

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
        </div>
    </div>
@endif
