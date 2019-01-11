<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container">
        @if ($products->count())
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
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Não encontramos resultados. <br> Tente palavras-chave diferentes</p>
            </div>
        @endif
    </div>
@endsection
