<?php
    $top_nav = true;
    $show_filter_products = true;
?>

@extends('mobile.base')

@section('content')
    <div class="banner-home">
        <div class="slick-home">
            <div class="item">
                <img src="{{ asset('images/banner-mobile/1.jpg') }}" alt="Banner 1" />
            </div>

            <div class="item">
                <a href="{{ route('show-store', 'krause') }}">
                    <img src="{{ asset('images/banner-mobile/krause.jpg') }}" alt="Banner Krause" />
                </a>
            </div>

            <div class="item">
                <img src="{{ asset('images/banner-mobile/2.jpg') }}" alt="Banner 2" />
            </div>

            <div class="item">
                <img src="{{ asset('images/banner-mobile/3.jpg') }}" alt="Banner 3" />
            </div>

            <div class="item">
                <a href="https://play.google.com/store/apps/details?id=app.naslojas" target="_blank">
                    <img src="{{ asset('images/banner-mobile/4.jpg') }}" alt="Banner 4" />
                </a>
            </div>

            <div class="item">
                <img src="{{ asset('images/banner-mobile/5.jpg') }}" alt="Banner 5" />
            </div>
        </div>
    </div>

    <div class="container">
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

        @include('mobile.pagination', ['paginator' => $products])
    </div>
@endsection
