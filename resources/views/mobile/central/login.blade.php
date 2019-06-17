@extends('mobile.base')

@section('content')
    <div class="container page-login-register">
        {!! Form::open(['method' => 'POST', 'route' => 'central-login']) !!}
            <h1>Admin da central</h1>

            <p class="sub">Acesse sua conta para visualizar os pedidos</p>

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
