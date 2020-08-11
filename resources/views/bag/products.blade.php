@extends('app', ['header_title' => 'Itens na sacola | naslojas.com', 'body_class' => 'bg-white'])

@section('content')
    @include ('inc.header')

    <div class="container-fluid page-bag-products bag">
        @if ($cart)
            <div class="header-bag">
                <h1>Itens na sacola</h1>

                <p>Confira os produtos que você adicionou a sua sacola</p>

                <a href="{{ route('home') }}" class="keep-buying">Continuar comprando</a>
            </div>

            <div class="products">
                @foreach ($cart->stores as $store)
                    @foreach ($store->products as $product)
                        <div class="product">
                            <div class="col-xs-2">
                                <img src="{{ asset('uploads/' . $store->id . '/products/' . $product->image) }}" alt="Produto {{ $product->name }}" />
                            </div>

                            <div class="col-xs-6">
                                <span class="sold-by">Produto vendido e entregue pela loja <a href="{{ route('show-store', $store->slug) }}">{{ $store->name }}</a></span>

                                <h3 class="title">{{ $product->name }}</h3>

                                <div class="size">
                                    <span class="label-select">Tamanho selecionado:</span>

                                    <select name="size" data-productid="{{ $product->id }}" class="bag-change-size selectpicker" autocomplete="off">
                                        @foreach ($product->sizes as $size)
                                            <option value="{{ $size }}" {{ $product->size == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <a href="{{ route('bag-remove-product', $product->id) }}" class="bag-remove-product">Remover da sacola</a>
                            </div>

                            <div class="col-xs-2">
                                <div class="qtd">
                                    <select name="qtd" data-productid="{{ $product->id }}" class="bag-change-qtd qtd selectpicker" autocomplete="off">
                                        @for ($quantity = 1; $quantity <= $store->max_quantity; $quantity++)
                                            <option value="{{ $quantity }}" {{ $product->qtd == $quantity ? 'selected' : '' }}>{{ $quantity }}</option>
                                        @endfor
                                    </select>

                                    <span class="label-select">Quantidade:</span>
                                </div>
                            </div>

                            <div class="col-xs-2">
                                <span class="price" data-price="{{ $product->price }}">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <div class="footer-bag">
                <span class="subtotal">SUBTOTAL <span>R$ {{ number_format($cart->subtotal, 2, ',', '.') }}</span></span>

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

    @include ('inc.footer')
@endsection
