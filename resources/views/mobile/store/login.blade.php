<?php
    $top_simple = true;
    $header_title = 'Login da loja | naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-login-register">
        <div class="top">
            <a href="{{ route('client-login-get') }}">CLIENTE</a>

            <a href="{{ route('store-login-get') }}" class="active">LOJA</a>
        </div>

        {!! Form::open(['method' => 'POST', 'route' => 'store-login-post', 'id' => 'form-login-store']) !!}
            <h1>Admin da loja</h1>

            <p class="sub">Acesse sua conta para começar</p>

            <div class="form-group">
                {!! Form::email('email', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'E-mail') !!}
            </div>

            <div class="form-group">
                {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'Senha') !!}
            </div>

            {!! Form::submit('ENTRAR') !!}

            <a href="#" class="password-recover" data-type="1">Recuperar senha</a>

            <p class="link">Não tem uma conta? <a href="{{ route('store-register-get') }}">Cadastrar</a></p>
        {!! Form::close() !!}
    </div>
@endsection
