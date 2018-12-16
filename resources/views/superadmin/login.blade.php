<?php
    $top_simple = true;
    $header_title = 'Acesso restrito - naslojas.com';
?>

@extends('base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'superadmin-login']) !!}
            <h1>Acesso restrito</h1>

            <p class="sub">Você não deveria estar aqui</p>

            {!! Form::email('email', null, ['placeholder' => 'E-mail', 'required']) !!}

            {!! Form::input('password', 'password', null, ['placeholder' => 'Senha', 'required']) !!}

            {!! Form::submit('ENTRAR') !!}
        {!! Form::close() !!}
    </div>
@endsection
