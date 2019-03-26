<?php
    $top_nav = true;
    $header_title = 'Divulgar ofertas | naslojas.com';
    $body_class = 'bg-white';
?>

@extends('base')

@section('content')
    <div class="container page-store-advertise">
        <span class="year">201<span>9</span></span>

        <h1>Ano para inovar o comércio de Pelotas</h1>

        <img src="{{ asset('images/advise/people.png') }}" class="img-responsive img-people" />

        <a href="{{ route('store-register-get') }}" class="btn-advertise">VENDER ONLINE</a>

        <div class="cards">
            <div class="card">
                <p>Atraia mais clientes<br>para dentro da loja</p>
            </div>

            <div class="card">
                <p>Suas ofertas disponíveis<br>24hs por dia</p>
            </div>

            <div class="card">
                <p>Tenha uma vitrine<br>virtual da sua loja</p>
            </div>

            <div class="card">
                <p>Suas ofertas<br>no site e no app</p>
            </div>

            <div class="card">
                <p>Tecnologia e inovação<br>para a sua loja</p>
            </div>
        </div>

        <div class="steps">
            <div class="step">
                <img src="{{ asset('images/advise/store.png') }}" class="img-shirt" />
                <p>Nós buscamos os<br>produtos na sua loja</p>
            </div>

            <div class="step">
                <img src="{{ asset('images/advise/camera.png') }}" class="img-camera" />
                <p>Tiramos fotos lindas e<br>cadastramos no site</p>
            </div>

            <div class="step">
                <img src="{{ asset('images/advise/truck.png') }}" class="img-truck" />
                <p>Devolvemos para<br>você em 1 dia útil</p>
            </div>
        </div>

        <div class="ready">
            <h3>PRONTO!</h3>

            <p>Todos em Pelotas poderão visitar sua loja 24hs por dia</p>

            <p class="bg">Nunca foi tão fácil vender seus produtos online</p>
        </div>

        <div class="price">
            <p>SEM MENSALIDADE<br>Até 30 produtos por mês é</p>

            <h3>TOTALMENTE GRÁTIS</h3>

            <p class="taxe">Depois o custo é de apenas R$ 0,50 por oferta + taxa única de R$ 5,00 do transporte</p>
        </div>

        <div class="race">
            <span class="vertical-line"></span>

            <p>O que está esperando?<br>Saia na frente da concorrência este ano</p>

            <img src="{{ asset('images/advise/race.png') }}" />
        </div>

        <div class="test">
            <p>Faça um teste sem compromisso<br>Até <b>30 ofertas</b> por mês é</p>

            <h3>TOTALMENTE GRÁTIS</h3>

            <a href="{{ route('store-register-get') }}" class="btn-advertise">VENDER ONLINE</a>

            <p class="phone">Dúvidas no whatsapp da empresa<br>53 9 9178 6097</p>
        </div>
    </div>
@endsection
