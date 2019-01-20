<?php
    $top_nav = true;
    $show_filter_products = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container">
        <div class="list-products">
            @foreach ($products as $product)
                <div class="product">
                    <a href="{{ route('show-product', $product->slug) }}" class="show-product">
                        <img src="{{ asset('uploads/' . $product->store->id . '/products/' . $product->images->first()->image) }}" class="image" alt="{{ $product->title }}" />
                    </a>

                    <div class="infos">
                        <a href="{{ route('show-product', $product->slug) }}" class="show-product">
                            @if ($product->reserve && $product->reserve_discount)
                                <span class="reserve"><span>DESCONTO NA RESERVA</span></span>
                            @endif

                            @if ($product->old_price)
                                <span class="old-price">de <span>{{ number_format($product->old_price, 2, ',', '.') }}</span></span>
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
