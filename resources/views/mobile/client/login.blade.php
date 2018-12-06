<?php
    $top_nav = true;
    $header_title = 'Login do cliente - naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'client-login-post', 'id' => 'form-login-client']) !!}
            <h1>Bem-vindo de volta!</h1>

            <p class="sub">Acesse seu perfil para começar</p>

            {!! Form::email('email', null, ['placeholder' => 'E-mail', 'required']) !!}

            {!! Form::input('password', 'password', null, ['placeholder' => 'Senha', 'required']) !!}

            {!! Form::submit('ENTRAR') !!}

            <?php /*<a href="#" class="recover-password">Recuperar senha</a>*/ ?>
        {!! Form::close() !!}
    </div>
@endsection
