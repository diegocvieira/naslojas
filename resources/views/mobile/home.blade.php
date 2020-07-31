@extends('app')

@section('content')
    @include ('mobile.inc.header')

    <div class="page-home">
        <section class="banner-home">
            <div class="slick-home-banner">
                <div class="item">
                    <img src="{{ asset('images/banner-mobile/1.jpg') }}" alt="Banner" />
                </div>

                <div class="item">
                    <img src="{{ asset('images/banner-mobile/2.jpg') }}" alt="Banner" />
                </div>

                <div class="item">
                    <img src="{{ asset('images/banner-mobile/3.jpg') }}" alt="Banner" />
                </div>

                <div class="item">
                    <img src="{{ asset('images/banner-mobile/4.jpg') }}" alt="Banner" />
                </div>

                <div class="item">
                    <img src="{{ asset('images/banner-mobile/5.jpg') }}" alt="Banner" />
                </div>
            </div>
        </section>

        @include ('mobile.inc._know')

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

        <!-- <section class="newsletter text-center">
            <div class="text">
                <h2>1 e-mail por semana</h2>
                <h3>Com as maiores ofertas, promoções e novidades das lojas da sua cidade</h3>
                <span>CANCELE QUANDO QUISER</span>
            </div>

            {!! Form::open(['method' => 'POST', 'route' => 'newsletter-register', 'id' => 'form-newsletter-register']) !!}
                {!! Form::email('email', null, ['placeholder' => 'Seu e-mail']) !!}

                {!! Form::submit('ENVIAR') !!}
            {!! Form::close() !!}
        </section> -->
    </div>

    @include ('mobile.inc.footer')
@endsection
