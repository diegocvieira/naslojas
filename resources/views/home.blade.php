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

                            <p class="title" title="{{ $product->title }}">{{ $product->title }}</p>
                        </a>

                        <a href="{{ route('show-store', $product->store->slug) }}" class="store-name">{{ $product->store->name }}</a>
                    </div>
                </div>
            @endforeach
        </div>

        @include('pagination', ['paginator' => $products])
    </div>
@endsection
