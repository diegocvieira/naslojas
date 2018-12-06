<?php
    $top_nav = true;
    $header_title = 'Como funciona | naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-how-works">
        <div class="slider" id="slider">
            <div class="holder">
                <div class="slide-wrapper">
                    <p>O <i>naslojas</i> é um site e app onde você pode conferir os produtos à venda nas lojas físicas de Pelotas</p>

                    <span class="swipe"><span class="text padding-left">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper padding">
                    <p>Você pode encontrar o que procura de um jeito rápido e fácil</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper">
                    <p>Reserve os produtos por 24hs e passe na loja somente para finalizar a compra</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper padding">
                    <p>Tire dúvidas sobre as ofertas diretamente com as lojas</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper">
                    <p>Economize tempo e dinheiro conferindo o <i>naslojas</i> antes de ir às compras</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper padding">
                    <p>Baixe o app do <i>naslojas</i> e confira no seu celular</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text padding-right">DESLIZE</span></span>
                </div>
            </div>
        </div>
    </div>
@endsection
