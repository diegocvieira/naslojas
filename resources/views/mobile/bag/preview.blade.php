<div class="bag-preview bag">
    @if (isset($cart) && $cart)
        <h2 class="header-title">MINHA SACOLA</h2>

        <div class="products">
            @foreach ($cart->stores as $store)
                @foreach ($store->products as $product)
                    <div class="product">
                        <img src="{{ asset('uploads/' . $store->id . '/products/' . $product->image) }}" alt="Produto {{ $product->name }}" />

                        <div class="infos">
                            <h3 class="title">{{ $product->name }}</h3>

                            <span class="price" data-price="{{ $product->price }}">R$ {{ number_format($product->price, 2, ',', '.') }}</span>

                            <a href="{{ route('bag-remove-product', $product->id) }}" class="bag-remove-product"></a>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <div class="footer-bag">
            <span class="subtotal">Subtotal <span>R$ {{ number_format($cart->subtotal, 2, ',', '.') }}</span></span>

            <a href="{{ route('bag-products') }}">VER SACOLA</a>

            <a href="{{ route('bag-data') }}" class="close-order">FECHAR PEDIDO</a>
        </div>
    @else
        <div class="no-results">
            <img src="{{ asset('images/icon-box.png') }}" alt="Sacola vazia" />

            <p>Sua sacola está vazia!</p>
        </div>
    @endif
</div>
