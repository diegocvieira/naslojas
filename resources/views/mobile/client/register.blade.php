@extends('app', ['header_title' => 'Cadastro do cliente - naslojas.com'])

@section('content')
    @include ('mobile.inc.header')

    <div class="container page-login-register">
        <div class="top">
            <a href="{{ route('client-register-get') }}" class="active">CLIENTE</a>

            <a href="{{ route('store-register-get') }}">LOJA</a>
        </div>

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

            <div class="form-group">
                {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'id' => 'password']) !!}
                {!! Form::label('', 'Senha') !!}
            </div>

            <div class="form-group">
                {!! Form::input('password', 'password_confirmation', null, ['placeholder' => ' ']) !!}
                {!! Form::label('', 'Confirmar senha') !!}
            </div>

            {!! Form::submit('CADASTRAR') !!}

            <p class="description">Concordo que li e concordo com as <a href="{{ route('rules') }}" target="_blank">regras para os anúncios</a>, os <a href="{{ route('terms-use') }}" target="_blank">termos de uso</a> e a <a href="{{ route('privacy-policy') }}" target="_blank">política de privacidade</a> do naslojas</p>

            <p class="link">Já é cadastrado? <a href="{{ route('client-login-get') }}">Entrar</a></p>

        {!! Form::close() !!}
    </div>

    @include ('mobile.inc.footer')
@endsection
