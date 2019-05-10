@php
    $top_nav = true;
    $body_class = 'bg-white';
@endphp

@extends('base')

@section('content')
    <div class="container page-home">
        <section class="section-content">
            <div class="banner-home">
                <div class="slick-home">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="item">
                            <img src="{{ asset('images/banner-desktop/' . $i . '.jpg') }}" alt="Banner {{ $i }}" />
                        </div>
                    @endfor
                </div>
            </div>

            <div class="container-fluid bg-grey">
                <div class="container">
                    <section class="row know">
                        <div class="col-xs-3">
                            <img src="{{ asset('images/know/shirt.png') }}" class="img-shirt" />

                            <span>Confira as ofertas<br>das lojas de Pelotas</span>
                        </div>

                        <div class="col-xs-3">
                            <img src="{{ asset('images/know/calendar.png') }}" />

                            <span>Receba seu pedido<br>em até 24 horas</span>
                        </div>

                        <div class="col-xs-3">
                            <img src="{{ asset('images/know/truck.png') }}" class="img-truck" />

                            <span>Frete para toda a<br>cidade por R$ 5,00</span>
                        </div>

                        <div class="col-xs-3">
                            <img src="{{ asset('images/know/card.png') }}" class="img-card" />

                            <span>Pague somente ao<br>receber o produto</span>
                        </div>
                    </section>

                    <section class="row">
                        <div class="col-xs-12">
                            <h2 class="section-title">PRODUTOS EM DESTAQUE</h2>
                        </div>

                        <div class="col-xs-12">
                            <div class="slick-products list-products">
                                @foreach ($featured_products as $featured_product)
                                    <div class="product">
                                        <a href="{{ route('show-product', $featured_product->slug) }}" class="show-product">
                                            <img src="{{ asset('uploads/' . $featured_product->store->id . '/products/' . $featured_product->images->first()->image) }}" class="image" alt="{{ $featured_product->title }}" />
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="row">
                        <div class="col-xs-3">
                            <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=50.00' }}">
                                <img src="{{ asset('images/price50.jpg') }}" class="img-responsive" alt="Produtos com até R$ 50,00" />
                            </a>
                        </div>

                        <div class="col-xs-3">
                            <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=100.00' }}">
                                <img src="{{ asset('images/price100.jpg') }}" class="img-responsive" alt="Produtos com até R$ 100,00" />
                            </a>
                        </div>

                        <div class="col-xs-3">
                            <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=150.00' }}">
                                <img src="{{ asset('images/price150.jpg') }}" class="img-responsive" alt="Produtos com até R$ 150,00" />
                            </a>
                        </div>

                        <div class="col-xs-3">
                            <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=200.00' }}">
                                <img src="{{ asset('images/price200.jpg') }}" class="img-responsive" alt="Produtos com até R$ 200,00" />
                            </a>
                        </div>
                    </section>

                    <section class="row">
                        <div class="col-xs-12">
                            <h2 class="section-title">MAIORES OFERTAS</h2>
                        </div>

                        <div class="col-xs-12">
                            <div class="slick-products list-products">
                                @foreach ($offers as $offer)
                                    <div class="product">
                                        <a href="{{ route('show-product', $offer->slug) }}" class="show-product">
                                            <img src="{{ asset('uploads/' . $offer->store->id . '/products/' . $offer->images->first()->image) }}" class="image" alt="{{ $offer->title }}" />
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="row stores">
                        <div class="col-xs-12">
                            <h2 class="section-title">LOJAS EM DESTAQUE</h2>
                        </div>

                        <div class="col-xs-12">
                            <div class="slick-stores">
                                <div class="item">
                                    <a href="{{ route('show-store', 'krause') }}">
                                        <img src="{{ asset('images/stores/krause.png') }}" alt="Krause" />
                                    </a>
                                </div>

                                <div class="item">
                                    <a href="{{ route('show-store', 'maisonbiansini') }}">
                                        <img src="{{ asset('images/stores/mb.png') }}" alt="Maison Biansini" />
                                    </a>
                                </div>

                                <div class="item">
                                    <a href="{{ route('show-store', 'clubemelissa') }}">
                                        <img src="{{ asset('images/stores/melissa.png') }}" alt="Clube Melissa" />
                                    </a>
                                </div>

                                <div class="item">
                                    <a href="{{ route('show-store', 'clubeminimelissa') }}">
                                        <img src="{{ asset('images/stores/mini-melissa.png') }}" alt="Clube Mini Melissa" />
                                    </a>
                                </div>

                                <div class="item">
                                    <a href="{{ route('show-store', 'hercilio') }}">
                                        <img src="{{ asset('images/stores/hercilio.png') }}" alt="Hercílio" />
                                    </a>
                                </div>

                                <div class="item">
                                    <a href="{{ route('show-store', 'myshoes') }}" class="store-disabled">
                                        <img src="{{ asset('images/stores/my-shoes.png') }}" alt="My Shoes" />
                                    </a>
                                </div>

                                <div class="item">
                                    <a href="{{ route('show-store', 'emilice') }}">
                                        <img src="{{ asset('images/stores/emilice.png') }}" alt="Emilice" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="container">
                <section class="row images-filter">
                    <div class="content">
                        <h3 class="images-title">FEMININO</h3>

                        <a href="{{ route('search-products', ['pelotas', 'rs']) . '?gender=feminino' }}">
                            <img src="{{ asset('images/female.png') }}" alt="Feminino" />
                        </a>
                    </div>

                    <div class="content">
                        <h3 class="images-title">MASCULINO</h3>

                        <a href="{{ route('search-products', ['pelotas', 'rs']) . '?gender=masculino' }}">
                            <img src="{{ asset('images/male.png') }}" alt="Masculino" />
                        </a>
                    </div>

                    <div class="content">
                        <h3 class="images-title">ESPORTE</h3>

                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=esporte' }}">
                            <img src="{{ asset('images/sport.png') }}" alt="Esporte" />
                        </a>
                    </div>

                    <div class="content">
                        <h3 class="images-title">CASUAL</h3>

                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=casual' }}">
                            <img src="{{ asset('images/casual.png') }}" alt="Casual" />
                        </a>
                    </div>

                    <div class="content">
                        <h3 class="images-title">ACESSÓRIOS</h3>

                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=acessorios' }}">
                            <img src="{{ asset('images/accessories.png') }}" alt="Acessórios" />
                        </a>
                    </div>
                </section>

                <section class="row brands">
                    <div class="col-xs-12 text-center">
                        <h2 class="section-title">MARCAS EM DESTAQUE</h2>
                    </div>

                    <div class="colxs-12 text-center">
                        @foreach ($brands as $brand)
                            <div class="content">
                                <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?keyword=' . $brand }}">
                                    <img src="{{ asset('images/brands/' . $brand . '.jpg') }}" alt="{{ $brand }}" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="row trending-words">
                    <div class="col-xs-12 text-center">
                        <h2 class="section-title">MAIS BUSCADOS</h2>
                    </div>

                    <div class="colxs-12 text-center">
                        @foreach ($trending_words as $key => $trending_word)
                            <a href="{{ ($trending_word == 'masculino' || $trending_word == 'feminino') ? route('search-products', ['pelotas', 'rs']) . '?gender=' . $trending_word : route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?keyword=' . $trending_word }}">{{ $trending_word }}</a>

                            @if ($key == 8 || $key == 18 || $key == 25)
                                <br>
                            @endif
                        @endforeach
                    </div>
                </section>

                <section class="row banners">
                    <div class="col-xs-12">
                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?keyword=nike' }}">
                            <img src="{{ asset('images/banner-nike.jpg') }}" alt="Nike" class="img-responsive" />
                        </a>

                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=estilo' }}">
                            <img src="{{ asset('images/banner-shoes.jpg') }}" alt="Sapatos" class="img-responsive" />
                        </a>
                    </div>
                </section>
            </div>

            <div class="app-mobile col-xs-12">
                <div class="container">
                    <div class="col-xs-4 text text-right">
                        <span>app</span>
                        <img src="{{ asset('images/logo-naslojas.png') }}" />
                        <p>as ofertas de Pelotas<br>sempre com você</p>
                    </div>

                    <div class="col-xs-4 img">
                        <img src="{{ asset('images/app.png') }}" />
                    </div>

                    <div class="col-xs-4 links text-left">
                        <div class="col-xs-12">
                            <a href="https://play.google.com/store/apps/details?id=app.naslojas" class="android" target="_blank">baixe para android</a>
                        </div>

                        <div class="col-xs-12">
                            <a href="#" class="ios show-app">em breve para iphone</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
