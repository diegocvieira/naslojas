@extends('app', ['body_class' => 'bg-white'])

@section('content')
    @include ('mobile.inc.header')

    <?php /*<ul class="search-navigation">
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?freight=grátis' }}">FRETE GRÁTIS</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?color=preto' }}">TUDO PRETO</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?max_price=100.00' }}">ATÉ CEMZINHO</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?gender=feminino' }}">PARA MOÇAS</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?gender=masculino' }}">PARA RAPAZES</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?off=10' }}">DESCONTINHOS</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=esporte' }}">ESPORTE</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=casual' }}">CASUAL</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=acessorios' }}">ACESSÓRIOS</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=estilo' }}">ESTILOSOS</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=roupas' }}">ROUPAS</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=calcados' }}">CALÇADOS</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?brand=nike' }}">NIKE</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?brand=adidas' }}">ADIDAS</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?brand=melissa' }}">MELISSA</a></li>
        <li><a href="https://play.google.com/store/apps/details?id=app.naslojas">BAIXAR APP</a></li>
        <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) }}">TUDO</a></li>
    </ul>*/ ?>

    @include('mobile.inc.filter-products')

    <div class="container">
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

            @include('mobile.pagination', ['paginator' => $products])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Não encontramos resultados. <br> Tente palavras-chave diferentes</p>
            </div>
        @endif
    </div>

    @include ('mobile.inc.footer')
@endsection
