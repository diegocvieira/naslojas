<?php
    $top_nav = true;
    $show_filter_products = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container page-show-store">
        @if ($products->count())
            <div class="store-infos">
                <a href="{{ route('show-store', $store->slug) }}">
                    <h1>{{ $store->name }}</h1>

                    <p>
                        {{ $store->street }}, {{ $store->number }} - {{ $store->district }} - {{ $store->city->title }}/{{ $store->city->state->letter }}
                    </p>
                </a>

                <a href="//maps.google.com/?q={{ $store->street }}, {{ $store->number }}, {{ $store->district }}, {{ $store->city->title }}, {{ $store->city->state->letter }}" target="_blank" class="map">
    				ver no mapa
    			</a>
            </div>

            <div class="list-products">
                @foreach($products as $product)
                    <div class="product">
                        <a href="{{ route('show-product', $product->slug) }}" class="show-product">
                            <img src="{{ asset('uploads/' . $product->store->id . '/products/' . $product->images->first()->image) }}" class="image" alt="{{ $product->title }}" />
                        </a>

                        <div class="infos">
                            <a href="#">
                                @if($product->old_price)
                                    <span class="old-price">R$ {{ number_format($product->old_price, 2, ',', '.') }}</span>
                                @endif

                                <span class="price"><span>R$</span> {{ number_format($product->price, 2, ',', '.') }}</span>

                                @if($product->old_price)
        							<span class="price-off">{{ _discount($product->price, $product->old_price) }}% OFF</span>
        						@endif

                                @if($product->installment && $product->installment_price)
                                    <span class="parcels">
                                        em até {{ $product->installment }}x de R$ {{ number_format($product->installment_price, 2, ',', '.') }}
                                        {{ _taxes($product->installment, $product->installment_price, $product->price) }}
                                    </span>
                                @endif

                                @if ($product->reserve && $product->reserve_discount)
                                    <span class="reserve"><span>R$ {{ number_format(_reservePrice($product->price, $product->reserve_discount), 2, ',', '.') }}</span> NA RESERVA</span>
                                @endif

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

                 @if ($products->count() == 0 && (isset($keyword) || isset($search_gender)))
                     <p>Não encontramos resultados. <br> Tente palavras-chave diferentes</p>
                 @else
                     <p>Nenhum produto cadastrado</p>
                 @endif
             </div>
         @endif
    </div>
@endsection
