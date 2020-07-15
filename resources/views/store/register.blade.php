@extends('app', ['header_title' => 'Cadastro da loja - naslojas.com'])

@section('content')
    @include ('inc.header')

    <div class="container page-login-register">
        <div class="top">
            <a href="{{ route('client-register-get') }}">CLIENTE</a>

            <a href="{{ route('store-register-get') }}" class="active">LOJA</a>
        </div>

        <form>
            <h1>Cadastre sua loja</h1>

            <p class="sub" style="margin-top: 30px;">Para realizar o seu cadastro, é necessário entrar em contato com o Sindilojas.</p>

            <p class="link">Já é cadastrado? <a href="{{ route('store-login-get') }}">Entrar</a></p>
        </form>
    </div>

    @include ('inc.footer')
@endsection
