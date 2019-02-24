<div class="bag-preview bag">
    @if (isset($products))
        <h2 class="header-title">MINHA SACOLA</h2>

        <div class="products">
            @foreach ($products as $product)
                <div class="product">
                    {!! Form::select('qtd', [$product->product_qtd => $product->product_qtd], $product->product_qtd, ['class' => 'qtd']) !!}

                    <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $product->images->first()->image) }}" alt="Produto {{ $product->title }}" />

                    <h3 class="title">{{ $product->title }}</h3>

                    <span class="price" data-price="{{ $product->price }}">R$ {{ number_format($product->price, 2, ',', '.') }}</span>

                    <a href="{{ route('bag-remove-product', $product->id) }}" class="bag-remove-product"></a>
                </div>
            @endforeach
        </div>

        <div class="footer-bag">
            <span class="subtotal">Subtotal <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span></span>

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
