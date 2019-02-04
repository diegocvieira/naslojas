<header id="top-nav-store">
    <div class="container">
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/logo-naslojas.png') }}" />
        </a>

        @if (Auth::guard('superadmin')->check())
            <div class="search-stores">
                <button type="button" class="open-search-stores"></button>

                <div class="dropdown">
                    <a href="{{ route('superadmin-store-register') }}" class="store-register">CADASTRAR LOJA</a>

                    @foreach ($superadmin_stores as $superadmin_store)
                        <a href="{{ route('superadmin-set-store', $superadmin_store->id) }}" class="{{ session('superadmin_store_id') == $superadmin_store->id ? 'active-store' : '' }}">{{ $superadmin_store->name }}</a>
                    @endforeach
                </div>
            </div>
        @endif

        <nav class="nav navbar-nav nav-menu">
            <ul>
                @if (Auth::guard('store')->check() && Auth::guard('store')->user()->store->status)
                    <li>
                        <a href="{{ route('show-store', Auth::guard('store')->user()->store->slug) }}">Minha loja</a>
                    </li>
                @elseif (Auth::guard('superadmin')->check() && Session::has('superadmin_store_status'))
                    <li>
                        <a href="{{ route('show-store', session('superadmin_store_slug')) }}">Minha loja</a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('product-images') }}" class="{{ (isset($section) && $section == 'add') ? 'active' : '' }}">Adicionar produtos</a>
                </li>

                <li>
                    <a href="{{ route('edit-products') }}" class="{{ (isset($section) && $section == 'edit') ? 'active' : '' }}">Editar produtos</a>
                </li>

                @if (Auth::guard('store')->check() || Auth::guard('superadmin')->check() && Auth::guard('superadmin')->user()->type == 1)
                    <li>
                        <a href="{{ route('list-store-confirms') }}" class="{{ (isset($section) && $section == 'confirm') ? 'active' : '' }}">Confirmações</a>
                    </li>

                    <li>
                        <a href="{{ route('list-store-reserves') }}" class="{{ (isset($section) && $section == 'reserve') ? 'active' : '' }}">Reservas</a>
                    </li>

                    <li>
                        <a href="{{ route('list-store-messages') }}" class="{{ (isset($section) && $section == 'message') ? 'active' : '' }}">Mensagens</a>
                    </li>
                @endif

                <li>
                    <a href="{{ route('get-store-config') }}" class="{{ (isset($section) && $section == 'config') ? 'active' : '' }}">Configurações</a>
                </li>

                <li>
                    <a href="{{ route('logout') }}">Sair</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
