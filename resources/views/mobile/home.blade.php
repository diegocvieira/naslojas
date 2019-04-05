<?php
    $top_nav = true;
    $show_filter_products = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container">
        <div class="banner-home">
            <div class="slick-home">
                <div class="item">
                    <img src="{{ asset('images/banner-mobile/1.jpg') }}" />
                </div>

                <div class="item">
                    <img src="{{ asset('images/banner-mobile/2.jpg') }}" />
                </div>

                <div class="item">
                    <img src="{{ asset('images/banner-mobile/3.jpg') }}" />
                </div>

                <div class="item">
                    <a href="https://play.google.com/store/apps/details?id=app.naslojas" target="_blank">
                        <img src="{{ asset('images/banner-mobile/4.jpg') }}" />
                    </a>
                </div>

                <div class="item">
                    <img src="{{ asset('images/banner-mobile/5.jpg') }}" />
                </div>
            </div>
        </div>

        <div class="list-products">
            @foreach ($products as $product)
                <div class="product">
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

        @include('mobile.pagination', ['paginator' => $products])
    </div>
@endsection
