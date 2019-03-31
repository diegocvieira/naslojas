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

        <img src="{{ asset('images/advise/people.png') }}" class="img-responsive img-people" />

        <a href="{{ route('store-register-get') }}" class="btn-advertise">VENDER ONLINE</a>

        <div class="cards">
            <div class="card">
                <p>Venda seus produtos<br>online em Pelotas</p>
            </div>

            <div class="card">
                <p>Suas ofertas no<br>site e no app</p>
            </div>

            <div class="card">
                <p>Aumente as vendas<br>e fature mais</p>
            </div>

            <div class="card">
                <p>Frete por R$5,00 para<br>toda cidade</p>
            </div>

            <div class="card">
                <p>Entregas em no<br>máximo 24hs</p>
            </div>
        </div>

        <div class="how-works">
            <div class="section-title">
                <h2>COMO FUNCIONA</h2>
            </div>

            <div class="item">
                <img src="{{ asset('images/advise/hw1.png') }}" class="img-responsive" />
                <p>O cliente encontra os seus produtos no naslojas e faz o pedido online</p>
            </div>

            <div class="item">
                <img src="{{ asset('images/advise/hw2.png') }}" class="img-responsive" />
                <p>A loja recebe um e-mail avisando do pedido e faz a confirmação da entrega</p>
            </div>

            <div class="item">
                <img src="{{ asset('images/advise/hw3.png') }}" class="img-responsive" />
                <p>O entregador pega o produto na loja, leva para o cliente e volta na loja com o pagamento</p>
            </div>
        </div>

        <div class="steps">
            <div class="section-title">
                <h2>COMO PARTICIPAR</h2>
                <h3>FAZEMOS O CADASTRO COMPLETO DA SUA LOJA!</h3>
            </div>

            <div class="step">
                <img src="{{ asset('images/advise/store.png') }}" class="img-shirt" />
                <p>Nós buscamos os<br>produtos na sua loja</p>
            </div>

            <div class="step ml">
                <img src="{{ asset('images/advise/camera.png') }}" class="img-camera" />
                <p>Tiramos fotos lindas e<br>cadastramos no site</p>
            </div>

            <div class="step ml">
                <img src="{{ asset('images/advise/truck.png') }}" class="img-truck" />
                <p>Devolvemos para<br>a loja em 1 dia útil</p>
            </div>
        </div>

        <div class="ready">
            <h3>PRONTO!</h3>

            <p>Todos em Pelotas poderão<br>comprar na sua loja 24hs por dia</p>

            <p class="bg">Nunca foi tão fácil vender<br>seus produtos online</p>
        </div>

        <div class="price">
            <p>SEM MENSALIDADE E SEM COMISSÃO NAS VENDAS</p>

            <p>Até 30 produtos por mês é</p>

            <h3>TOTALMENTE GRÁTIS</h3>

            <p class="taxe">Depois o custo é de apenas R$ 0,50 por oferta + taxa única de R$ 5,00 do transporte</p>
        </div>

        <div class="race">
            <span class="vertical-line"></span>

            <p>O que está esperando?<br>Saia na frente da concorrência<br>este ano</p>

            <img src="{{ asset('images/advise/race.png') }}" class="img-responsive" />
        </div>

        <div class="test">
            <p>Faça um teste sem compromisso<br>Até <b>30 ofertas</b> por mês é</p>

            <h3>TOTALMENTE GRÁTIS</h3>

            <a href="{{ route('store-register-get') }}" class="btn-advertise">VENDER ONLINE</a>

            <p class="phone">Dúvidas no whatsapp da empresa<br>53 9 9178 6097</p>
        </div>
    </div>
@endsection
