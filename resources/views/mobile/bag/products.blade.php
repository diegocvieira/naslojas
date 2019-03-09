@php
    $top_nav = true;
@endphp

@extends('mobile.base')

@section('content')
    <div class="container page-bag-products bag">
        @if (isset($products))
            <div class="header-bag">
                <h1>Itens na sacola</h1>

                <p>Confira os produtos que você adicionou a sua sacola</p>
            </div>

            <div class="products">
                @foreach ($products as $product)
                    <div class="product">
                        <div class="top">
                            <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $product->images->first()->image) }}" alt="Produto {{ $product->title }}" />

                            <a href="{{ route('show-product', $product->slug) }}" class="show-product"></a>
                        </div>

                        <span class="sold-by">Produto vendido e entregue pela loja <a href="{{ route('show-store', $product->store->slug) }}">{{ $product->store->name }}</a></span>

                        <h3 class="title">{{ $product->title }}</h3>

                        <span class="price" data-price="{{ $product->price }}">R$ {{ number_format($product->price, 2, ',', '.') }}</span>

                        <div class="size">
                            <span class="label-select">Tamanho selecionado:</span>

                            {!! Form::select('size', $product->sizes->pluck('size', 'size'), $product->size, ['class' => 'bag-change-size selectpicker', 'data-productid' => $product->id, 'autocomplete' => 'off']) !!}
                        </div>

                        <div class="qtd">
                            <span class="label-select">Quantidade:</span>

                            {!! Form::select('qtd', $product->store_qtd, $product->product_qtd, ['class' => 'bag-change-qtd qtd selectpicker', 'data-productid' => $product->id, 'autocomplete' => 'off']) !!}
                        </div>

                        <a href="{{ route('bag-remove-product', $product->id) }}" class="bag-remove-product">Remover da sacola</a>
                    </div>
                @endforeach
            </div>

            <div class="footer-bag">
                <span class="subtotal">SUBTOTAL <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span></span>

                <a href="{{ route('bag-data') }}" class="close-order">FECHAR PEDIDO</a>

                <a href="{{ route('home') }}" class="keep-buying">Continuar comprando</a>
            </div>
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" alt="Sacola vazia" />

                <p>Sua sacola está vazia!</p>
            </div>
        @endif
    </div>
@endsection
