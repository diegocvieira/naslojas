<header>
    @if(isset($admin_search) && isset($keyword))
        <a href="{{ route('edit-products') }}" class="btn-back-search"></a>
    @else
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/icon-logo-naslojas.png') }}" />
        </a>
    @endif

    @if (isset($store))
        {!! Form::open(['method' => 'GET', 'route' => 'form-search-store', 'id' => 'form-search']) !!}
            {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Pesquise dentro da sua loja']) !!}

            {!! Form::hidden('store_slug', $store->slug) !!}

            {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
            {!! Form::hidden('gender', $search_gender ?? 'todos', ['id' => 'search-gender']) !!}
        {!! Form::close() !!}
    @elseif (isset($admin_search))
        {!! Form::open(['method' => 'GET', 'route' => 'form-search-admin', 'id' => 'form-search']) !!}
            {!! Form::text('keyword', $keyword ?? null, ['placeholder' => 'Pesquise dentro da sua loja', 'required']) !!}
        {!! Form::close() !!}
    @else
        {!! Form::open(['method' => 'GET', 'route' => 'form-search', 'id' => 'form-search']) !!}
            {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Pesquise aqui']) !!}

            {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
            {!! Form::hidden('gender', $search_gender ?? 'todos', ['id' => 'search-gender']) !!}
        {!! Form::close() !!}
    @endif

    <nav class="nav navbar-nav nav-menu">
        <ul>
            <li>
                <a href="#" class="open-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset(Auth::check() ? 'images/icon-profile.png' : 'images/icon-menu.png') }}" alt="Menu" />
                </a>

                <ul class="dropdown-menu">
                    @isset ($show_filter_products)
                        <li>
                            <a href="#" class="open-filter-products">Filtrar produtos</a>
                        </li>
                    @endisset

                    @if (Auth::check())
                        @if (Auth::guard('client')->check())
                            <li>
                                <a href="{{ route('get-client-config') }}">Minha conta</a>
                            </li>

                            <li>
                                <a href="{{ route('list-client-confirms') }}">Confirmações</a>
                            </li>

                            <li>
                                <a href="{{ route('list-client-reserves') }}">Reservas</a>
                            </li>

                            <li>
                                <a href="{{ route('list-client-messages') }}">Mensagens</a>
                            </li>
                        @elseif (Auth::guard('store')->check())
                            <li>
                                <a href="{{ route('edit-products') }}">Produtos</a>
                            </li>

                            <li>
                                <a href="{{ route('list-store-confirms') }}">Confirmações</a>
                            </li>

                            <li>
                                <a href="{{ route('list-store-reserves') }}">Reservas</a>
                            </li>

                            <li>
                                <a href="{{ route('list-store-messages') }}">Mensagens</a>
                            </li>

                            <li>
                                <a href="{{ route('get-store-config') }}">Configurações</a>
                            </li>
                        @endif

                        <li>
                            <a href="{{ route('logout') }}">Sair</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('know') }}">Entenda</a>
                        </li>

                        <li>
                            <a href="{{ route('how-works') }}">Como funciona</a>
                        </li>

                        <li>
                            <a href="#" class="show-app">Baixe nosso app</a>
                        </li>

                        <li>
                            <a href="{{ route('client-register-get') }}">Cadastrar</a>
                        </li>

                        <li>
                            <a href="{{ route('client-login-get') }}">Entrar</a>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
    </nav>
</header>

<div class="filter-products">
    <div class="filter">
        <span>Ordenar</span>

        @foreach ($orderby as $ok => $o)
            <a href="#" data-value="{{ $ok }}" data-type="order" class="{{ (isset($search_order) && $search_order == $ok) ? 'active' : '' }}">{{ $o }}</a>
        @endforeach
    </div>

    <div class="filter">
        <span>Gênero</span>

        @foreach ($genders as $gk => $g)
            <a href="#" data-value="{{ $gk }}" data-type="gender" class="{{ (isset($search_gender) && $search_gender == $gk) ? 'active' : '' }}">{{ $g }}</a>
        @endforeach
    </div>
</div>
