<header id="top-nav-store">
    <div class="container">
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/logo-naslojas.png') }}" />
        </a>

        <nav class="nav navbar-nav nav-menu">
            <ul>
                @if(Auth::guard('store')->user()->store->status)
                    <li>
                        <a href="{{ route('show-store', Auth::guard('store')->user()->store->slug) }}">Minha loja</a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('product-images') }}" class="{{ $section == 'add' ? 'active' : '' }}">Adicionar produtos</a>
                </li>

                <li>
                    <a href="{{ route('edit-products') }}" class="{{ $section == 'edit' ? 'active' : '' }}">Editar produtos</a>
                </li>

                <li>
                    <a href="#">Confirmações</a>
                </li>

                <li>
                    <a href="#">Reservas</a>
                </li>

                <li>
                    <a href="#">Mensagens</a>
                </li>

                <li>
                    <a href="{{ route('get-store-config') }}" class="show-store-config">Configurações</a>
                </li>

                <li>
                    <a href="{{ route('logout') }}">Sair</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
