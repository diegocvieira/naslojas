@extends('app')

@section('content')
    @include ('inc.header-store')

    <div class="container-fluid page-product-images">
        <div class="top-images">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6">
                        <p>Selecione as imagens de um mesmo produto e clique em agrupar imagens</p>
                    </div>

                    <div class="col-xs-6 text-right">
                        <button type="button" class="btn-agroup">AGRUPAR IMAGENS</button>

                        <button type="button" class="btn-finish">PRÓXIMO</button>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::open(['route' => 'save-products', 'class' => 'dropzone', 'id' => 'form-images-dropzone', 'files' => 'true']) !!}
            <div class="dz-message">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Clique para carregar as imagens dos<br>produtos ou arraste e solte-as aqui <span>Máximo de 100 imagens por vez<br>Máximo de 5mb por imagem</span></p>
            </div>
        {!! Form::close() !!}
    </div>

    @include('inc.footer')
@endsection
