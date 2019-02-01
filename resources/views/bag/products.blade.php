<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container page-bag-products bag">
        @if (isset($products))
            <div class="header-bag">
                <h1>Itens na sacola</h1>

                <p>Confira os produtos que você adicionou a sua sacola</p>

                <a href="{{ route('home') }}" class="keep-buying">Continuar comprando</a>
            </div>

            <div class="products">
                <?php $subtotal = 0; ?>

                @foreach ($products as $product)
                    @foreach (session('bag')['stores'] as $bag_store)
                        @foreach ($bag_store['products'] as $bag_product)
                            @if ($bag_product['id'] == $product->id)
                                <?php
                                    $product_qtd = $bag_product['qtd'];
                                    $product_size = $bag_product['size'];
                                    $subtotal += $product_qtd * $product->price;
                                ?>
                            @endif
                        @endforeach
                    @endforeach

                    <div class="product">
                        <div class="col-xs-2">
                            <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $product->images->first()->image) }}" alt="Produto {{ $product->title }}" />
                        </div>

                        <div class="col-xs-6">
                            <span class="sold-by">Produto vendido e entregue pela loja <a href="{{ route('show-store', $product->store->slug) }}">{{ $product->store->name }}</a></span>

                            <h3 class="title">{{ $product->title }}</h3>

                            <div class="size">
                                <span class="label-select">Tamanho selecionado:</span>

                                {!! Form::select('size', $product->sizes->pluck('size', 'size'), $product_size, ['class' => 'bag-change-size selectpicker', 'data-productid' => $product->id, 'autocomplete' => 'off']) !!}
                            </div>

                            <a href="{{ route('bag-remove-product', $product->id) }}" class="bag-remove-product">Remover da sacola</a>
                        </div>

                        <div class="col-xs-2">
                            <div class="qtd">
                                {!! Form::select('qtd', $qtd, $product_qtd, ['class' => 'bag-change-qtd qtd selectpicker', 'data-productid' => $product->id, 'autocomplete' => 'off']) !!}

                                <span class="label-select">Quantidade:</span>
                            </div>
                        </div>

                        <div class="col-xs-2">
                            <span class="price" data-price="{{ $product->price }}">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="footer-bag">
                <span class="subtotal">SUBTOTAL <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span></span>

                <a href="#" class="close-order">FECHAR PEDIDO</a>

                <a href="{{ route('home') }}" class="keep-buying">Continuar comprando</a>
            </div>
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" alt="Sem resultados" />

                <p>Sua sacola está vazia!</p>
            </div>
        @endif
    </div>
@endsection
