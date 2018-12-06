<?php
    $top_nav = true;
    $header_title = 'Cadastro do cliente - naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'client-register-post', 'id' => 'form-register-client']) !!}
            <h1>Bem-vindo!</h1>

            <p class="sub">Cadastre-se totalmente grátis</p>

            {!! Form::text('name', null, ['placeholder' => 'Nome']) !!}

            {!! Form::email('email', null, ['placeholder' => 'E-mail', 'required']) !!}

            {!! Form::input('password', 'password', null, ['placeholder' => 'Senha', 'class' => 'half', 'id' => 'password']) !!}

            {!! Form::input('password', 'password_confirmation', null, ['placeholder' => 'Confirmar senha', 'class' => 'half']) !!}

            {!! Form::submit('CADASTRAR') !!}

            <p class="description">Ao se cadastrar você concorda com as <a href="{{ route('rules') }}" target="_blank">regras para os anúncios</a>, os <a href="{{ route('terms-use') }}" target="_blank">termos de uso</a> e a <a href="{{ route('privacy-policy') }}" target="_blank">política de privacidade</a> do naslojas.com</p>
        {!! Form::close() !!}
    </div>
@endsection