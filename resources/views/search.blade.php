@php
    $top_nav = true;
    $body_class = 'bg-white';
@endphp

@extends('base')

@section('content')
    <div class="container">
        @include('inc.know')

        <div class="row">
            <div class="col-12">
                @include ('inc._search-navigation')
            </div>
        </div>

        @if ($products->count())
            <div class="row">
                <div class="col-12">
                    <div class="product-filter-orderby">
                        {!! Form::select('order', $orderby, $search_order ?? null, ['class' => 'selectpicker', 'title' => 'Ordenar']) !!}
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-xs-3">
                @include('inc.aside-filters')
            </div>

            <div class="col-xs-9">
                @if ($products->count())
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

                                        @if ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time))
                                            <span class="old-price">de <span>{{ number_format($product->price, 2, ',', '.') }}</span></span>
                                        @elseif ($product->off)
                                            <span class="old-price">de <span>{{ number_format(_oldPrice($product->price, $product->off), 2, ',', '.') }}</span></span>
                                        @endif

                                        <span class="price">
                                            <span>R$</span>
                                            {{ number_format(($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time)) ? _priceOff($product->price, $product->offtime->off) : $product->price, 2, ',', '.') }}
                                        </span>

                                        @if ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time))
                                            <span class="price-off">{{ $product->offtime->off }}% OFF</span>
                                        @elseif ($product->off)
                                            <span class="price-off">{{ $product->off }}% OFF</span>
                                        @endif

                                        <span class="parcels">
                                            {{ $product->showParcels($product) }}
                                        </span>

                                        <p class="title" title="{{ $product->title }}">{{ $product->title }}</p>

                                        @if ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time))
                                            <span class="offtime" data-date="{{ date('Y-m-d H:i:s', strtotime('+' . $product->offtime->time . ' hours', strtotime($product->offtime->created_at))) }}"></span>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>

                    @include('pagination', ['paginator' => $products])
                @else
                    <div class="no-results search">
                        <img src="{{ asset('images/icon-box.png') }}" />

                        <p>Não encontramos resultados. <br> Tente palavras-chave diferentes</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
