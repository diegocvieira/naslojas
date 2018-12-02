<?php
    $header_title = 'Erro 500 - naslojas.com';
    $top_simple = true;
?>

@extends('base')

@section('content')
    <div class="container page-error">
        <div class="no-results">
            <img src="{{ asset('images/icon-box.png') }}" />

            <p>Erro 500 - Página não encontrada</p>
        </div>
    </div>
@endsection
