<?php
    $top_simple = true;
    $header_title = 'Acesso restrito - naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'superadmin-login']) !!}
            <h1>Acesso restrito</h1>

            <p class="sub">Você não deveria estar aqui</p>

            <div class="form-group">
                {!! Form::email('email', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'E-mail') !!}
            </div>

            <div class="form-group">
                {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'Senha') !!}
            </div>

            {!! Form::submit('ENTRAR') !!}
        {!! Form::close() !!}
    </div>
@endsection
