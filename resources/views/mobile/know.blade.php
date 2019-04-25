<?php
    $top_nav = true;
    $header_title = 'Entenda | naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-know">
        <div class="item">
            <img src="{{ asset('images/know/shirt.png') }}" />

            <p>Confira as ofertas<br>das lojas de Pelotas</p>
        </div>

        <div class="item">
            <img src="{{ asset('images/know/calendar.png') }}" />

            <p>Receba seu pedido<br>em até 24 horas</p>
        </div>

        <div class="item">
            <img src="{{ asset('images/know/truck.png') }}" />

            <p>Frete para toda a<br>cidade por R$ 5,00</p>
        </div>

        <div class="item">
            <img src="{{ asset('images/know/card.png') }}" class="img-card" />

            <p>Pague somente ao<br>receber o produto</p>
        </div>
    </div>
@endsection
