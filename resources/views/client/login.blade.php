@extends('app', ['header_title' => 'Login do cliente - naslojas.com'])

@section('content')
    @include ('inc.header')

    <div class="container-fluid page-login-register">
        <div class="top">
            <a href="{{ route('client-login-get') }}" class="active">CLIENTE</a>

            <a href="{{ route('store-login-get') }}">LOJA</a>
        </div>

        {!! Form::open(['method' => 'POST', 'route' => 'client-login-post', 'id' => 'form-login-client']) !!}
            <h1>Bem-vindo de volta!</h1>

            <p class="sub">Acesse seu perfil para começar</p>

            <div class="form-group">
                {!! Form::email('email', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'E-mail') !!}
            </div>

            <div class="form-group">
                {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'required']) !!}
                {!! Form::label('', 'Senha') !!}
            </div>

            {!! Form::submit('ENTRAR') !!}

            <a href="#" class="password-recover" data-type="2">Recuperar senha</a>

            <p class="link">Não tem uma conta? <a href="{{ route('client-register-get') }}">Cadastrar</a></p>
        {!! Form::close() !!}
    </div>

    @include ('inc.footer')
@endsection
