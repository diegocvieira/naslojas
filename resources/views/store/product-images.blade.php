<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-product-images">
        <div class="top-images">
            <div class="col-xs-6">
                <p>Selecione as imagens de um mesmo produto e clique em agrupar imagens</p>
            </div>

            <div class="col-xs-6 text-right">
                <button type="button" class="btn-agroup">AGRUPAR IMAGENS</button>

                <button type="button" class="btn-finish">PRÓXIMO</button>
            </div>
        </div>

        {!! Form::open(['route' => 'save-products', 'class' => 'dropzone', 'id' => 'form-images-dropzone', 'files' => 'true']) !!}
            <div class="dz-message">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Clique para carregar as imagens dos produtos <br> ou arraste e solte as imagens aqui <span>(Máximo de 50 imagens por vez)</span></p>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
