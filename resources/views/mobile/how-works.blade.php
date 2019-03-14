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
                    <p>O <i>naslojas</i> é um site e app onde você pode conferir as maiores ofertas lojas físicas de Pelotas</p>

                    <span class="swipe"><span class="text padding-left">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper">
                    <p>Você faz o pedido online e recebe o seu produto em até 24 horas</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper">
                    <p>Realize o pagamento somente ao receber o seu pedido</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper">
                    <p>Tire dúvidas sobre os produtos e realize a troca diretamente com as lojas</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text">DESLIZE</span><span class="arrow right"></span></span>
                </div>

                <div class="slide-wrapper">
                    <p>O jeito mais rápido e fácil de comprar e economizar em Pelotas</p>

                    <span class="swipe"><span class="arrow left"></span><span class="text padding-right">DESLIZE</span></span>
                </div>
            </div>
        </div>
    </div>
@endsection
