<?php
    $top_nav = true;
    $header_title = 'Entenda | naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-know">
        <div class="item">
            <img src="{{ asset('images/know/shirt.png') }}" />

            <p>Confira as ofertas <br> das lojas de Pelotas</p>
        </div>

        <div class="item">
            <img src="{{ asset('images/know/calendar.png') }}" />

            <p>Reserve o produto que <br> você gostou por 24hs</p>
        </div>

        <div class="item">
            <img src="{{ asset('images/know/store.png') }}" />

            <p>Passe na loja para <br> finalizar a compra</p>
        </div>
    </div>
@endsection
