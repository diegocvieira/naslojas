<?php
    $top_nav = true;
    $header_title = 'Cadastro da loja - naslojas.com';
?>

@extends('mobile.base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'superadmin-store-register']) !!}
            <h1>Cadastre sua loja!</h1>

            <p class="sub">Totalmente grátis</p>

            <div class="form-group">
                {!! Form::text('name', old('name'), ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'Nome da loja') !!}
            </div>

            <div class="form-group">
                {!! Form::email('email', old('email'), ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'E-mail') !!}
            </div>

            <div class="form-group">
                {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'Senha') !!}
            </div>

            <div class="form-group">
                {!! Form::input('password', 'password_confirmation', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'Repetir senha') !!}
            </div>

            {!! Form::submit('CADASTRAR') !!}

            <p class="description">Ao se cadastrar você concorda com as <a href="{{ route('rules') }}" target="_blank">regras para os anúncios</a>, os <a href="{{ route('terms-use') }}" target="_blank">termos de uso</a> e a <a href="{{ route('privacy-policy') }}" target="_blank">política de privacidade</a> do naslojas.com</p>
        {!! Form::close() !!}
    </div>
@endsection
