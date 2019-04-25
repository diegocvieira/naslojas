@php
    $top_nav = true;
@endphp

@extends('base')

@section('content')
    <div class="container page-home">
        <div class="banner-home">
            <div class="slick-home">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/' . $i . '.jpg') }}" alt="Banner {{ $i }}" />
                    </div>
                @endfor
            </div>
        </div>

        <div class="container">
            <div class="know">
                <div class="col-xs-3">
                    <img src="{{ asset('images/know/shirt.png') }}" class="img-shirt" />

                    <span>Confira as ofertas<br>das lojas de Pelotas</span>
                </div>

                <div class="col-xs-3">
                    <img src="{{ asset('images/know/calendar.png') }}" />

                    <span>Receba seu pedido<br>em até 24 horas</span>
                </div>

                <div class="col-xs-3">
                    <img src="{{ asset('images/know/truck.png') }}" class="img-truck" />

                    <span>Frete para toda a<br>cidade por R$ 5,00</span>
                </div>

                <div class="col-xs-3">
                    <img src="{{ asset('images/know/card.png') }}" class="img-card" />

                    <span>Pague somente ao<br>receber o produto</span>
                </div>
            </div>

            <div class="stores">
                <div class="slick-stores">
                    <div class="item">
                        <a href="{{ route('show-store', 'krause') }}">
                            <img src="{{ asset('images/stores/krause.png') }}" alt="Krause" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('show-store', 'clubemelissa') }}">
                            <img src="{{ asset('images/stores/melissa.png') }}" alt="Clube Melissa" />
                        </a>
                    </div>
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
                                @if ($product->free_freight)
                                    <div class="free-freight">
                                        <span>FRETE GRÁTIS</span>
                                    </div>
                                @endif

                                @if ($product->off)
                                    <span class="old-price">de <span>{{ number_format(_oldPrice($product->price, $product->off), 2, ',', '.') }}</span></span>
                                @endif

                                <span class="price"><span>R$</span> {{ number_format($product->price, 2, ',', '.') }}</span>

                                @if ($product->off)
        							<span class="price-off">{{ $product->off }}% OFF</span>
        						@endif

                                <span class="parcels">
                                    {{ $product->showParcels($product) }}
                                </span>

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
                    <p>as ofertas de Pelotas<br>sempre com você</p>
                </div>

                <div class="col-xs-4 img">
                    <img src="{{ asset('images/app.png') }}" />
                </div>

                <div class="col-xs-4 links text-left">
                    <div class="col-xs-12">
                        <a href="https://play.google.com/store/apps/details?id=app.naslojas" class="android" target="_blank">baixe para android</a>
                    </div>

                    <div class="col-xs-12">
                        <a href="#" class="ios show-app">em breve para iphone</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
