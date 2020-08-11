@extends('app', ['body_class' => 'bg-white'])

@section('content')
    @include ('mobile.inc.header')

    <div class="container page-bag-products bag">
        @if (isset($cart))
            <div class="header-bag">
                <h1>Itens na sacola</h1>

                <p>Confira os produtos que você adicionou a sua sacola</p>
            </div>

            <div class="products">
                @foreach ($cart->stores as $store)
                    @foreach ($store->products as $product)
                        <div class="product">
                            <div class="top">
                                <img src="{{ asset('uploads/' . $store->id . '/products/' . $product->image) }}" alt="Produto {{ $product->name }}" />

                                <a href="{{ route('show-product', $product->slug) }}" class="show-product"></a>
                            </div>

                            <span class="sold-by">Produto vendido e entregue pela loja <a href="{{ route('show-store', $store->slug) }}">{{ $store->name }}</a></span>

                            <h3 class="title">{{ $product->name }}</h3>

                            <span class="price" data-price="{{ $product->price }}">R$ {{ number_format($product->price, 2, ',', '.') }}</span>

                            <div class="size">
                                <span class="label-select">Tamanho selecionado:</span>

                                <select name="size" data-productid="{{ $product->id }}" class="bag-change-size selectpicker" autocomplete="off">
                                    @foreach ($product->sizes as $size)
                                        <option value="{{ $size }}" {{ $product->size == $size ? 'selected' : '' }}>{{ $size }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="qtd">
                                <span class="label-select">Quantidade:</span>

                                <select name="qtd" data-productid="{{ $product->id }}" class="bag-change-qtd qtd selectpicker" autocomplete="off">
                                    @for ($quantity = 1; $quantity <= $store->max_quantity; $quantity++)
                                        <option value="{{ $quantity }}">{{ $quantity }}</option>
                                    @endfor
                                </select>
                            </div>

                            <a href="{{ route('bag-remove-product', $product->id) }}" class="bag-remove-product">Remover da sacola</a>
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

    @include ('mobile.inc.footer')
@endsection
