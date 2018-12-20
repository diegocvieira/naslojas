<?php
    $top_simple = true;
    $header_title = 'Login do cliente - naslojas.com';
?>

@extends('base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'client-login-post', 'id' => 'form-login-client']) !!}
            <h1>Bem-vindo de volta!</h1>

            <p class="sub">Acesse seu perfil para começar</p>

            {!! Form::email('email', null, ['placeholder' => 'E-mail', 'required']) !!}

            {!! Form::input('password', 'password', null, ['placeholder' => 'Senha', 'required']) !!}

            {!! Form::submit('ENTRAR') !!}

            <a href="#" class="password-recover" data-type="2">Recuperar senha</a>
        {!! Form::close() !!}
    </div>
@endsection
