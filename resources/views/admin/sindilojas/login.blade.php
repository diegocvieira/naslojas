@extends('app', ['header_title' => 'Login do Sindilojas - naslojas.com'])

@section('content')
    @include ('inc.header-simple')

    <div class="container page-login-register">
        <form method="POST" action="{{ route('admin.sindilojas.login') }}" style="margin-top: 100px;">
            @csrf

            <h1 style="margin-bottom: 50px;">Admin do Sindilojas</h1>

            <div class="form-group">
                <input type="email" name="email" value="{{ old('email') }}" placeholder=" " required />
                <label for="">E-mail</label>
            </div>

            <div class="form-group">
                <input type="password" name="password" placeholder=" " required />
                <label for="">Senha</label>
            </div>
            <input type="submit" value="ENTRAR" />
        </form>
    </div>
@endsection
