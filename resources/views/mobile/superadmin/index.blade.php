<?php
    $top_nav = true;
    $header_title = 'Início | naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-admin">
        <div class="no-results">
            <img src="{{ asset('images/icon-box.png') }}" />

            <p>Cadastre ou selecione uma loja para começar</p>
        </div>
    </div>
@endsection
