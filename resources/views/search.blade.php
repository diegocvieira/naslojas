@php
    $top_nav = true;
    $body_class = 'bg-white';
@endphp

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

                            <div class="infos">
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
                            </div>
                        </a>
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
