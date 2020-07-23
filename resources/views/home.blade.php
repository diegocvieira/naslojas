@extends('app', ['body_class' => 'bg-white'])

@section('content')
    @include('inc.header')

    <div class="container page-home">
        <section class="section-content bg-grey">
            <div class="banner-home">
                <div class="slick-home">
                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/1.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/2.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/3.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/4.jpg') }}" alt="Banner" />
                    </div>

                    <div class="item">
                        <img src="{{ asset('images/banner-desktop/5.jpg') }}" alt="Banner" />
                    </div>
                </div>
            </div>

            <div class="container">
                @include('inc.know')

                <section class="row stores">
                    <form method="GET" action="{{ route('location.set') }}" id="form-location" class="col-xs-12">
                        <input type="hidden" value="{{ $cities }}" id="cities" />
                        <input type="hidden" value="{{ $districts }}" id="districts" />

                        <input type="hidden" name="city_id" id="city-id" />
                        <input type="hidden" name="district_id" id="district-id" />

                        <div class="form-group">
                            <input type="text" id="search-city" placeholder=" " />
                            <label for="search-city">Busque sua cidade</label>
                        </div>

                        <div class="form-group">
                            <input type="text" id="search-district" placeholder=" " />
                            <label for="search-district">Busque seu bairro</label>
                        </div>

                        <button type="submit" disabled>SELECIONAR</button>
                    </form>

                    <div class="list-location"></div>
                </section>
            </div>
        </section>
    </div>

    @include('inc.footer')
@endsection
