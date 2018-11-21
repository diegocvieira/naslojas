<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container">
        <div class="product-filter">
            {!! Form::select('gender', $genders, $search_gender ?? null, ['class' => 'selectpicker', 'title' => 'Gênero']) !!}

            {!! Form::select('order', $orderby, $search_order ?? null, ['class' => 'selectpicker', 'title' => 'Ordenar']) !!}
        </div>

        <div class="list-products">
            @foreach($products as $product)
                <div class="product">
                    <a href="#">
                        <img src="{{ asset('uploads/' . $product->store->id . '/produtos/' . $product->images->first()->imagem) }}" class="image" />
                    </a>

                    <div class="infos">
                        <a href="#">
                            @if($product->preco_antigo)
                                <span class="old-price">R$ {{ number_format($product->preco_antigo, 2, ',', '.') }}</span>
                            @endif

                            <span class="price"><span>R$</span> {{ number_format($product->preco, 2, ',', '.') }}</span>

                            @if($product->preco_antigo)
    							<span class="price-off">{{ _discount($product->preco, $product->preco_antigo) }}% OFF</span>
    						@endif

                            @if($product->parcelamento && $product->parcel_price)
                                <span class="parcels">
                                    em até {{ $product->parcelamento }}x de R$ {{ number_format($product->parcel_price, 2, ',', '.') }}
                                    {{ _taxes($product->parcelamento, $product->parcel_price, $product->preco) }}
                                </span>
                            @endif

                            <p class="title" title="{{ $product->titulo }}">{{ $product->titulo }}</p>
                        </a>

                        <a href="{{ route('show-store', $product->store->slug) }}" class="store-name">{{ $product->store->nome }}</a>
                    </div>
                </div>
            @endforeach
        </div>

        @include('pagination', ['paginator' => $products])
    </div>
@endsection
