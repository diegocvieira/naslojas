<header id="top-simple">
    <div class="container">
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/logo-naslojas.png') }}" />
        </a>

        @if (session('central_logged'))
            <a href="{{ route('central-logout') }}" class="logout">Sair</a>
        @endif
    </div>
</header>
