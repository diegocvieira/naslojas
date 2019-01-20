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

            <div class="form-group">
                {!! Form::text('name', null, ['placeholder' => ' ']) !!}
                {!! Form::label('', 'Nome') !!}
            </div>

            <div class="form-group">
                {!! Form::email('email', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'E-mail') !!}
            </div>

            <div class="form-group half margin">
                {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'id' => 'password']) !!}
                {!! Form::label('', 'Senha') !!}
            </div>

            <div class="form-group half">
                {!! Form::input('password', 'password_confirmation', null, ['placeholder' => ' ']) !!}
                {!! Form::label('', 'Confirmar senha') !!}
            </div>

            {!! Form::submit('CADASTRAR') !!}

            <p class="description">Ao se cadastrar você concorda com as <a href="{{ route('rules') }}" target="_blank">regras para os anúncios</a>, os <a href="{{ route('terms-use') }}" target="_blank">termos de uso</a> e a <a href="{{ route('privacy-policy') }}" target="_blank">política de privacidade</a> do naslojas.com</p>
        {!! Form::close() !!}
    </div>
@endsection
