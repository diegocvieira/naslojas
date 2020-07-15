@extends('app', ['header_title' => 'Cadastro da loja - naslojas.com'])

@section('content')
    @include ('inc.header-simple')

    <div class="container page-login-register">
        <form method="POST" action="{{ route('admin.sindilojas.store.register') }}" style="margin-top: 100px;">
            @csrf

            <h1 style="margin-bottom: 50px;">Cadastre uma loja</h1>

            <div class="form-group">
                <input type="text" name="name" value="{{ old('name') }}" placeholder=" " required />
                <label for="">Nome da loja</label>
            </div>

            <div class="form-group">
                <input type="email" name="email" value="{{ old('email') }}" placeholder=" " required />
                <label for="">E-mail</label>
            </div>

            <input type="submit" value="CADASTRAR" />
        </form>
    </div>
@endsection
