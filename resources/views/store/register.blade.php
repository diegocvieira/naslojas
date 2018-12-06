<?php
    $top_simple = true;
    $header_title = 'Cadastro da loja - naslojas.com';
?>

@extends('base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'store-register-post', 'id' => 'form-register-store']) !!}
            <h1>Divulgue suas ofertas</h1>

            <p class="sub">Nós fazemos o trabalho pesado</p>

            {!! Form::text('store_name', null, ['placeholder' => 'Nome da loja', 'required']) !!}

            {!! Form::text('user_name', null, ['placeholder' => 'Seu nome', 'required']) !!}

            {!! Form::email('email', null, ['placeholder' => 'E-mail', 'required']) !!}

            {!! Form::text('phone', null, ['placeholder' => 'Telefone', 'required']) !!}

            {!! Form::submit('ENVIAR') !!}

            <p class="description">Ao se cadastrar você concorda com as <a href="{{ route('rules') }}" target="_blank">regras para os anúncios</a>, os <a href="{{ route('terms-use') }}" target="_blank">termos de uso</a> e a <a href="{{ route('privacy-policy') }}" target="_blank">política de privacidade</a> do naslojas.com</p>
        {!! Form::close() !!}
    </div>
@endsection
