@extends('app', ['body_class' => 'bg-white'])

@section('content')
    @include('inc.header')

    <div class="container-fluid page-home">
        <section class="section-content bg-grey">
            <div class="banner-home">
                <div class="slick-home">
                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/6.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/1.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/2.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/6.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/3.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/4.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/6.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/5.jpg') }}" alt="Banner" />
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                @include('inc.know')

                <section class="row stores">
                    <div class="col-xs-12">
                        <h2 class="section-title">LOJAS</h2>
                    </div>

                    @foreach ($stores as $s)
                        <div class="col-xs-4">
                            <div class="item">
                                <a href="{{ route('show-store', $s->slug) }}" class="city-verify">
                                    <img src="{{ asset($s->image_cover_desktop ? 'uploads/' . $s->id . '/' . $s->image_cover_desktop : 'images/image-cover-desktop.jpg') }}" alt="{{ $s->name }}"  />

                                    <h3>{{ $s->name }}</h3>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </section>

                <?php /*<section class="row newsletter text-center">
                    <div class="col-xs-12">
                        <h2>1 e-mail por semana</h2>
                        <h3>Com as maiores ofertas, promoções e novidades das lojas da sua cidade</h3>
                        <span>CANCELE QUANDO QUISER</span>
                    </div>

                    <div class="col-xs-12">
                        {!! Form::open(['method' => 'POST', 'route' => 'newsletter-register', 'id' => 'form-newsletter-register']) !!}
                            {!! Form::email('email', null, ['placeholder' => 'Seu e-mail']) !!}

                            {!! Form::submit('ENVIAR') !!}
                        {!! Form::close() !!}
                    </div>
                </section>*/ ?>
            </div>

            <?php /*<div class="app-mobile col-xs-12">
                <div class="container">
                    <div class="col-xs-4 text text-right">
                        <span>app</span>
                        <img src="{{ asset('images/logo-naslojas.png') }}" />
                        <p>as ofertas da cidade<br>sempre com você</p>
                    </div>

                    <div class="col-xs-4 img">
                        <img src="{{ asset('images/app.png') }}" />
                    </div>

                    <div class="col-xs-4 links text-left">
                        <div class="col-xs-12">
                            <a href="https://play.google.com/store/apps/details?id=app.naslojas" class="android" target="_blank">baixe para android</a>
                        </div>

                        <div class="col-xs-12">
                            <a href="https://apps.apple.com/br/app/naslojas/id1468999330" class="ios" target="_blank">baixe para iphone</a>
                        </div>
                    </div>
                </div>
            </div>*/ ?>
        </section>
    </div>

    @include('inc.footer')
@endsection
