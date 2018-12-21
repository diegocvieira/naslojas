<?php
    $top_simple = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'password-change']) !!}
            <h1>Recuperar acesso</h1>

            <p class="sub">Cadastre uma nova senha abaixo</p>

            {!! Form::hidden('email', $password_recover->email) !!}
            {!! Form::hidden('type', $password_recover->type) !!}

            {!! Form::input('password', 'password', null, ['placeholder' => 'Senha', 'required']) !!}

            {!! Form::input('password', 'password_confirmation', null, ['placeholder' => 'Repetir senha', 'required']) !!}

            {!! Form::submit('ENVIAR') !!}
        {!! Form::close() !!}
    </div>
@endsection
