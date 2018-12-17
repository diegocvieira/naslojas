<?php
    $header_title = 'Erro 404 - naslojas.com';
    $top_simple = true;
    $body_class = 'page-error';
?>

@extends('base')

@section('content')
    <div class="container">
        <div class="no-results">
            <img src="{{ asset('images/icon-box.png') }}" />

            <p>Erro 404 - Página não encontrada</p>
        </div>
    </div>
@endsection
