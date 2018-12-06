<?php
    $top_nav = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container page-client-config">
        {!! Form::model($client, ['method' => 'POST', 'route' => 'set-client-config', 'id' => 'form-client-config']) !!}
            <h1>Minha conta</h1>

            {!! Form::input('password', 'current_password') !!}

            {!! Form::text('name', null, ['placeholder' => 'Nome']) !!}

            {!! Form::email('email', null, ['placeholder' => 'E-mail']) !!}

            {!! Form::input('password', 'password', null, ['placeholder' => 'Nova senha', 'id' => 'password', 'class' => 'margin half']) !!}

            {!! Form::input('password', 'password_confirmation', null, ['placeholder' => 'Confirmar nova senha', 'class' => 'half']) !!}

            {!! Form::submit('SALVAR') !!}

            <a href="{{ route('delete-client-account') }}" id="delete-client-account">Deletar conta</a>
        {!! Form::close() !!}
    </div>
@endsection
