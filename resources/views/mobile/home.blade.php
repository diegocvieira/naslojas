<?php
    $top_nav = true;
    $show_filter_products = true;
    $body_class = 'bg-white';
?>

@extends('mobile.base')

@section('content')
    <div class="page-home">
        <div class="bg-grey">
            <section class="banner-home">
                <div class="slick-home-banner">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="item">
                            <img src="{{ asset('images/banner-mobile/' . $i . '.jpg') }}" alt="Banner {{ $i }}" />
                        </div>
                    @endfor
                </div>
            </section>

            @include ('mobile.inc._know')

            <?php /*<section class="products-home">
                <h2 class="section-title">PRODUTOS EM DESTAQUE</h2>

                <div class="slick-home-products list-products">
                    @foreach ($featured_products as $featured_product)
                        <div class="product">
                            <a href="{{ route('show-product', $featured_product->slug) }}" class="show-product">
                                <img src="{{ asset('uploads/' . $featured_product->store->id . '/products/' . $featured_product->images->first()->image) }}" class="image" alt="{{ $featured_product->title }}" />
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="prices">
                <div class="slick-home-prices">
                    <div class="item">
                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=50.00' }}">
                            <img src="{{ asset('images/price50.jpg') }}" class="img-responsive" alt="Produtos com até R$ 50,00" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=100.00' }}">
                            <img src="{{ asset('images/price100.jpg') }}" class="img-responsive" alt="Produtos com até R$ 100,00" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=150.00' }}">
                            <img src="{{ asset('images/price150.jpg') }}" class="img-responsive" alt="Produtos com até R$ 150,00" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=200.00' }}">
                            <img src="{{ asset('images/price200.jpg') }}" class="img-responsive" alt="Produtos com até R$ 200,00" />
                        </a>
                    </div>
                </div>
            </section>

            <section class="products-home">
                <h2 class="section-title">OFERTAS EM DESTAQUE</h2>

                <div class="slick-home-products list-products">
                    @foreach ($offers as $offer)
                        <div class="product">
                            <a href="{{ route('show-product', $offer->slug) }}" class="show-product">
                                <img src="{{ asset('uploads/' . $offer->store->id . '/products/' . $offer->images->first()->image) }}" class="image" alt="{{ $offer->title }}" />
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="stores-home">
                <h2 class="section-title">LOJAS EM DESTAQUE</h2>

                <div class="slick-home-stores">
                    <div class="item">
                        <a href="{{ route('show-store', 'krause') }}">
                            <img src="{{ asset('images/stores-mobile/krause.png') }}" alt="Krause" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('show-store', 'maisonbiansini') }}">
                            <img src="{{ asset('images/stores-mobile/mb.png') }}" alt="Maison Biansini" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('show-store', 'clubemelissa') }}">
                            <img src="{{ asset('images/stores-mobile/melissa.png') }}" alt="Clube Melissa" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('show-store', 'clubeminimelissa') }}">
                            <img src="{{ asset('images/stores-mobile/mini-melissa.png') }}" alt="Clube Mini Melissa" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('show-store', 'hercilio') }}">
                            <img src="{{ asset('images/stores-mobile/hercilio.png') }}" alt="Hercílio" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('show-store', 'myshoes') }}" class="store-disabled">
                            <img src="{{ asset('images/stores-mobile/my-shoes.png') }}" alt="My Shoes" />
                        </a>
                    </div>

                    <div class="item">
                        <a href="{{ route('show-store', 'emilice') }}">
                            <img src="{{ asset('images/stores-mobile/emilice.png') }}" alt="Emilice" />
                        </a>
                    </div>
                </div>
            </section>*/ ?>

            <section class="stores-home">
                <h2 class="section-title">LOJAS</h2>

                @foreach ($stores as $s)
                    <div class="item">
                        <a href="{{ route('show-store', $s->slug) }}">
                            <img src="{{ asset($s->image_cover_mobile ? 'uploads/' . $s->id . '/' . $s->image_cover_mobile : 'images/image-cover-mobile.jpg') }}" alt="Krause" />

                            <h3>{{ $s->name }}</h3>
                        </a>
                    </div>
                @endforeach
            </section>
        </div>

        <?php /*<section class="images-filter">
            <div class="content">
                <h3 class="section-title">FEMININO</h3>

                <a href="{{ route('search-products', ['pelotas', 'rs']) . '?gender=feminino' }}">
                    <img src="{{ asset('images/female.png') }}" alt="Feminino" class="img-responsive" />
                </a>
            </div>

            <div class="content">
                <h3 class="section-title">MASCULINO</h3>

                <a href="{{ route('search-products', ['pelotas', 'rs']) . '?gender=masculino' }}">
                    <img src="{{ asset('images/male.png') }}" alt="Masculino" class="img-responsive" />
                </a>
            </div>

            <div class="content">
                <h3 class="section-title">ESPORTE</h3>

                <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=esporte' }}">
                    <img src="{{ asset('images/sport.png') }}" alt="Esporte" class="img-responsive" />
                </a>
            </div>

            <div class="content">
                <h3 class="section-title">CASUAL</h3>

                <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=casual' }}">
                    <img src="{{ asset('images/casual.png') }}" alt="Casual" class="img-responsive" />
                </a>
            </div>

            <div class="content">
                <h3 class="section-title">ACESSÓRIOS</h3>

                <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=acessorios' }}">
                    <img src="{{ asset('images/accessories.png') }}" alt="Acessórios" class="img-responsive" />
                </a>
            </div>
        </section>

        <section class="brands">
            <h2 class="section-title">MARCAS EM DESTAQUE</h2>

            <div class="slick-home-brands">
                @foreach ($brands as $key_brand => $brand)
                    <div class="content">
                        <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?keyword=' . $brand }}">
                            <img src="{{ asset('images/brands/' . $brand . '.jpg') }}" alt="{{ $brand }}" class="img-responsive" />
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="trending-words">
            <h2 class="section-title">MAIS BUSCADOS</h2>

            <div class="slick-trending-words">
                @foreach ($trending_words as $key => $trending_word)
                    <div class="item">
                        <a href="{{ ($trending_word == 'masculino' || $trending_word == 'feminino') ? route('search-products', ['pelotas', 'rs']) . '?gender=' . $trending_word : route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?keyword=' . $trending_word }}">{{ $trending_word }}</a>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="banners">
            <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?brand=nike' }}">
                <img src="{{ asset('images/banner-nike-mobile.jpg') }}" alt="Nike" class="img-responsive" />
            </a>

            <a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=estilo' }}">
                <img src="{{ asset('images/banner-shoes-mobile.jpg') }}" alt="Sapatos" class="img-responsive" />
            </a>
        </section>*/ ?>

        <section class="newsletter text-center">
            <div class="text">
                <h2>1 e-mail por semana</h2>
                <h3>Com as maiores ofertas, promoções e novidades das lojas de Pelotas</h3>
                <span>CANCELE QUANDO QUISER</span>
            </div>

            {!! Form::open(['method' => 'POST', 'route' => 'newsletter-register', 'id' => 'form-newsletter-register']) !!}
                {!! Form::email('email', null, ['placeholder' => 'Seu e-mail']) !!}

                {!! Form::submit('ENVIAR') !!}
            {!! Form::close() !!}
        </section>
    </div>
@endsection

@section('script')
    @if (session('session_modal_home') == 'true')
        <div class="modal fade" id="modal-home" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button type="button" data-dismiss="modal" class="close-modal"></button>

                    <img src="{{ asset('images/modal-home-mobile.png') }}" class="img-responsive" alt="Modal home" />
                </div>
            </div>
        </div>

        <script>
            $(function() {
                $('#modal-home').modal('show');
            });
        </script>
    @endif
@endsection
