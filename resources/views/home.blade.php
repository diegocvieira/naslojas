<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container page-home">
        <div class="container">
            <div class="know">
                <div class="col-xs-4">
                    <img src="{{ asset('images/know/shirt.png') }}" class="img-shirt" />
                    <p>Confira as ofertas<br>das lojas de Pelotas</p>
                </div>

                <div class="col-xs-4">
                    <img src="{{ asset('images/know/calendar.png') }}" />
                    <p>Reserve por 24hs o<br>produto que gostou</p>
                </div>

                <div class="col-xs-4">
                    <img src="{{ asset('images/know/store.png') }}" class="img-store" />
                    <p>Passe na loja para<br>finalizar a compra</p>
                </div>
            </div>

            <div class="product-filter">
                {!! Form::select('gender', $genders, $search_gender ?? null, ['class' => 'selectpicker', 'title' => 'Gênero']) !!}

                {!! Form::select('order', $orderby, $search_order ?? null, ['class' => 'selectpicker', 'title' => 'Ordenar']) !!}
            </div>

            <div class="list-products">
                @foreach ($products as $product)
                    <div class="product">
                        <a href="{{ route('show-product', $product->slug) }}" class="show-product">
                            <img src="{{ asset('uploads/' . $product->store->id . '/products/' . $product->images->first()->image) }}" class="image" alt="{{ $product->title }}" />
                        </a>

                        <div class="infos">
                            <a href="{{ route('show-product', $product->slug) }}" class="show-product">
                                @if ($product->reserve && $product->reserve_discount)
                                    <span class="reserve" title="R$ {{ number_format(_reservePrice($product->price, $product->reserve_discount), 2, ',', '.') }} na reserva pelo naslojas"><span>DESCONTO NA RESERVA</span></span>
                                @endif

                                @if ($product->old_price)
                                    <span class="old-price">de R$ {{ number_format($product->old_price, 2, ',', '.') }}</span>
                                @endif

                                <span class="price" title="{{ ($product->reserve && $product->reserve_discount) ? 'Preço normal cobrado pela loja' : '' }}"><span>R$</span> {{ number_format($product->price, 2, ',', '.') }}</span>

                                @if ($product->old_price)
        							<span class="price-off">{{ _discount($product->price, $product->old_price) }}% OFF</span>
        						@endif

                                @if ($product->installment && $product->installment_price)
                                    <span class="parcels">
                                        em até {{ $product->installment }}x de R$ {{ number_format($product->installment_price, 2, ',', '.') }}
                                        {{ _taxes($product->installment, $product->installment_price, $product->price) }}
                                    </span>
                                @endif

                                <p class="title" title="{{ $product->title }}">{{ $product->title }}</p>
                            </a>

                            <a href="{{ route('show-store', $product->store->slug) }}" class="store-name">{{ $product->store->name }}</a>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('pagination', ['paginator' => $products])
        </div>

        <div class="app-mobile col-xs-12">
            <div class="container">
                <div class="col-xs-4 text text-right">
                    <span>app</span>
                    <img src="{{ asset('images/logo-naslojas.png') }}" />
                    <p>feito para os humanos<br>de Pelotas</p>
                </div>

                <div class="col-xs-4 img">
                    <img src="{{ asset('images/app.png') }}" />
                </div>

                <div class="col-xs-4 links text-left">
                    <div class="col-xs-12">
                        <a href="#" class="android">baixe para android</a>
                    </div>

                    <div class="col-xs-12">
                        <a href="#" class="ios">em breve para iphone</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
