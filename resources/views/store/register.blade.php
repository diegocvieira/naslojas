<?php
    $top_simple = true;
    $header_title = 'Cadastro da loja - naslojas.com';
?>

@extends('base')

@section('content')
    <div class="container page-login-register">
        <div class="top">
            <a href="{{ route('client-register-get') }}">CLIENTE</a>

            <a href="{{ route('store-register-get') }}" class="active">LOJA</a>
        </div>

        {!! Form::open(['method' => 'POST', 'route' => 'store-register-post', 'id' => 'form-register-store']) !!}
            <h1>Divulgue suas ofertas</h1>

            <p class="sub">Nós fazemos o trabalho pesado</p>

            <div class="form-group">
                {!! Form::text('name', old('name'), ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'Nome da loja') !!}
            </div>

            <div class="form-group">
                {!! Form::email('email', old('email'), ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'E-mail') !!}
            </div>

            <div class="form-group">
                {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'required', 'id' => 'password']) !!}
                {!! Form::label('', 'Senha') !!}
            </div>

            <div class="form-group">
                {!! Form::input('password', 'password_confirmation', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'Repetir senha') !!}
            </div>

            {!! Form::submit('CADASTRAR') !!}

            <p class="description">Confirmo que li e concordo com as <a href="{{ route('rules') }}" target="_blank">regras para os anúncios</a>, os <a href="{{ route('terms-use') }}" target="_blank">termos de uso</a> e a <a href="{{ route('privacy-policy') }}" target="_blank">política de privacidade</a> do naslojas</p>

            <p class="link">Já é cadastrado? <a href="{{ route('store-login-get') }}">Entrar</a></p>
        {!! Form::close() !!}
    </div>
@endsection
