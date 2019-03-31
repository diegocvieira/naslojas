@php
    $top_nav = true;
@endphp

@extends('base')

@section('content')
    <div class="page page-home">
        <div class="container">
            <div class="know row text-center mobile-off">
                <div class="col-sm-4">
                    <img src="{{ asset('images/know/shirt.png') }}" class="img-shirt" />

                    <p>Confira as ofertas<br>das lojas de Pelotas</p>
                </div>

                <div class="col-sm-4">
                    <img src="{{ asset('images/know/calendar.png') }}" />

                    <p>Receba seu pedido<br>em até 24 horas</p>
                </div>

                <div class="col-sm-4">
                    <img src="{{ asset('images/know/card.png') }}" class="img-card" />

                    <p>Pague somente ao<br>receber o produto</p>
                </div>
            </div>

            <div class="row product-filter mobile-off">
                <div class="col-sm-12 text-right">
                    {!! Form::select('gender', $genders, $search_gender ?? null, ['class' => 'selectpicker', 'title' => 'Gênero']) !!}

                    {!! Form::select('order', $orderby, $search_order ?? null, ['class' => 'selectpicker', 'title' => 'Ordenar']) !!}
                </div>
            </div>

            <div class="list-products row">
                @foreach ($products as $product)
                    <div class="product col">
                        <a href="{{ route('show-product', $product->slug) }}" class="show-product">
                            <img src="{{ asset('uploads/' . $product->store->id . '/products/' . $product->images->first()->image) }}" class="image" alt="{{ $product->title }}" />
                        </a>

                        <div class="infos">
                            <a href="{{ route('show-product', $product->slug) }}" class="show-product">
                                @if ($product->old_price)
                                    <span class="old-price">de <span>{{ number_format($product->old_price, 2, ',', '.') }}</span></span>
                                @endif

                                <span class="price"><span>R$</span> {{ number_format($product->price, 2, ',', '.') }}</span>

                                @if ($product->old_price)
        							<span class="price-off">{{ _discount($product->price, $product->old_price) }}% OFF</span>
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

        <div class="app-mobile container-fluid">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 text text-right">
                        <span>app</span>
                        <img src="{{ asset('images/logo-naslojas.png') }}" alt="App" />
                        <p>as ofertas de Pelotas<br>sempre com você</p>
                    </div>

                    <div class="col-lg-6 img">
                        <img src="{{ asset('images/app.png') }}" />
                    </div>

                    <div class="col-lg-3 links text-left">
                        <div class="link-container">
                            <a href="https://play.google.com/store/apps/details?id=app.naslojas" class="android" target="_blank">baixe para android</a>
                        </div>

                        <div class="link-container">
                            <a href="#" class="ios show-app">em breve para iphone</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
