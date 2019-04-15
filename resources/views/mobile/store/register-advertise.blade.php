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
                <p>Tiramos fotos lindas<br>para cadastrar no site</p>
            </div>

            <div class="step ml">
                <img src="{{ asset('images/advise/truck.png') }}" class="img-truck" />
                <p>Devolvemos para a<br>loja em 48 horas</p>
            </div>
        </div>

        <div class="ready">
            <h3>PRONTO!</h3>

            <p>Todos em Pelotas poderão<br>comprar na sua loja 24hs por dia</p>

            <p class="bg">Nunca foi tão fácil vender<br>seus produtos online</p>
        </div>

        <div class="price">
            <p>SEM MENSALIDADE E SEM COMISSÃO NAS VENDAS</p>

            <p>Os primeiros 50 produtos são</p>

            <h3>TOTALMENTE GRÁTIS</h3>

            <p class="taxe">Depois o custo é de apenas R$ 1,00 por produto + taxa única de R$ 10,00 do transporte<br>Ou cadastre você mesmo sem nenhum custo e sem limite de produtos</p>
        </div>

        <div class="race">
            <span class="vertical-line"></span>

            <p>O que está esperando?<br>Saia na frente da concorrência<br>este ano</p>

            <img src="{{ asset('images/advise/race.png') }}" class="img-responsive" />
        </div>

        <div class="test">
            <p>Faça um teste sem compromisso<br>Os primeiros <b>50 produtos</b> são</p>

            <h3>TOTALMENTE GRÁTIS</h3>

            <a href="{{ route('store-register-get') }}" class="btn-advertise">VENDER ONLINE</a>

            <p class="phone">Dúvidas no whatsapp da empresa<br>53 9 9178 6097</p>
        </div>
    </div>
@endsection
