<header id="header-store">
    <div class="container-fluid">
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/logo-naslojas.png') }}" />
        </a>

        <nav class="nav navbar-nav nav-menu">
            <ul>
                @if (Auth::guard('store')->check() && Auth::guard('store')->user()->store->status)
                    <li>
                        <a href="{{ route('show-store', Auth::guard('store')->user()->store->slug) }}">Minha loja</a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('product-images') }}" class="{{ (isset($section) && $section == 'add') ? 'active' : '' }}">Adicionar produtos</a>
                </li>

                <li>
                    <a href="{{ route('edit-products') }}" class="{{ (isset($section) && $section == 'edit') ? 'active' : '' }}">Editar produtos</a>
                </li>

                @if (Auth::guard('store')->check())
                    <li>
                        <a href="{{ route('list-store-orders') }}" class="{{ (isset($section) && $section == 'order') ? 'active' : '' }}">Pedidos</a>
                    </li>

                    <li>
                        <a href="{{ route('list-store-messages') }}" class="{{ (isset($section) && $section == 'message') ? 'active' : '' }}">Mensagens</a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('get-store-config') }}" class="{{ (isset($section) && $section == 'config') ? 'active' : '' }}">Configurações</a>
                </li>

                <li>
                    <a href="{{ route('tutorials', 'adicionar-produtos') }}" class="{{ (isset($section) && $section == 'tutorial') ? 'active' : '' }}">Tutoriais</a>
                </li>

                <li>
                    <a href="{{ route('logout') }}">Sair</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
