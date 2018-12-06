<?php
    $top_nav = true;
    $header_title = 'Divulgar ofertas | naslojas.com';
    $body_class = 'bg-white';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-store-advertise">
        <span class="year">201<span>9</span></span>

        <h1>Ano para inovar o comércio de Pelotas</h1>

        <img src="{{ asset('images/advise/race.png') }}" class="img-responsive img-race" />

        <a href="{{ route('store-register-get') }}" class="btn-advertise">DIVULGAR OFERTAS</a>

        <div class="cards">
            <div class="card">
                <p>Uma vitrine virtual <br> da sua loja</p>
            </div>

            <div class="card">
                <p>Suas ofertas disponíveis <br> 24hs por dia</p>
            </div>

            <div class="card">
                <p>Atraia mais clientes <br> para dentro da loja</p>
            </div>

            <div class="card">
                <p>Suas ofertas <br> no site e no app</p>
            </div>

            <div class="card">
                <p>Tecnologia e inovação <br> para a sua loja</p>
            </div>
        </div>

        <div class="steps">
            <div class="step">
                <img src="{{ asset('images/advise/shirt.png') }}" />
                <p>Nós buscamos os <br> produtos na sua loja</p>
            </div>

            <div class="step">
                <img src="{{ asset('images/advise/camera.png') }}" class="img-camera" />
                <p>Tiramos fotos lindas e <br> cadastramos no site</p>
            </div>

            <div class="step">
                <img src="{{ asset('images/advise/truck.png') }}" />
                <p>Devolvemos para <br> você em 1 dia útil</p>
            </div>
        </div>

        <div class="ready">
            <h3>PRONTO</h3>

            <p>Todos em Pelotas poderão <br> conferir suas ofertas 24hs por dia</p>

            <p class="bg">E para finalizar a compra <br> é só passar na loja</p>
        </div>

        <div class="price">
            <p>SEM MENSALIDADE</p>

            <h3>Apenas <span>1 real</span> <br> por produto</h3>

            <p class="taxe">+ taxa única de R$ 5,00 de transporte</p>
        </div>

        <div class="test">
            <p>Faça um teste de compromisso <br> As primeiras <b>50 ofertas</b> são</p>

            <h3>TOTALMENTE GRÁTIS</h3>

            <a href="{{ route('store-register-get') }}" class="btn-advertise">DIVULGAR OFERTAS</a>

            <p class="phone">Dúvidas no whatsApp da empresa <br> 53 9 9169 1716</p>
        </div>
    </div>
@endsection