<?php
    $top_nav = true;
    $show_filter_products = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container">
        @if ($products->count())
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

            @include('mobile.pagination', ['paginator' => $products])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Não encontramos resultados. <br> Tente palavras-chave diferentes</p>
            </div>
        @endif
    </div>
@endsection
