<header>
    <a href="{{ url('/') }}" id="logo-naslojas">
        <img src="{{ asset('images/icon-logo-naslojas.png') }}" />
    </a>

    @if(isset($store))
        {!! Form::open(['method' => 'GET', 'route' => 'form-search-store', 'id' => 'form-search']) !!}
            {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Pesquise na loja ' . $store->name]) !!}

            {!! Form::hidden('store_slug', $store->slug) !!}

            {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
            {!! Form::hidden('gender', $search_gender ?? 'todos', ['id' => 'search-gender']) !!}
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
                @if (Auth::guard('client')->check())
                    <a href="#" class="open-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('images/icon-profile.png') }}" alt="Menu" />
                    </a>

                    <ul class="dropdown-menu">
                        @isset($show_filter_products)
                            <li>
                                <a href="#" class="open-filter-products">Filtrar produtos</a>
                            </li>
                        @endisset

                        <li>
                            <a href="{{ route('get-client-config') }}" class="icon-account show-client-config">Minha conta</a>
                        </li>

                        <li>
                            <a href="{{ route('list-client-confirms') }}" class="icon-check">Confirmações</a>
                        </li>

                        <li>
                            <a href="{{ route('list-client-reserves') }}" class="icon-reserve">Reservas</a>
                        </li>

                        <li>
                            <a href="{{ route('list-client-messages') }}" class="icon-messages">Mensagens</a>
                        </li>

                        <li>
                            <a href="{{ route('logout') }}" class="icon-logout">Sair</a>
                        </li>
                    </ul>
                @else
                    <a href="#" class="open-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('images/icon-menu.png') }}" alt="Menu" />
                    </a>

                    <ul class="dropdown-menu">
                        @isset($show_filter_products)
                            <li>
                                <a href="#" class="open-filter-products">Filtrar produtos</a>
                            </li>
                        @endisset

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
                    </ul>
                @endif
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
