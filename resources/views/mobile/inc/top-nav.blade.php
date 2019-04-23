<header>
    @if (isset($admin_search) && isset($keyword) || isset($back))
        <a href="{{ route('edit-products') }}" class="btn-back-search"></a>
    @else
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/icon-logo-naslojas.png') }}" alt="Logo naslojas.com" />
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

    <button class="open-bag" data-url="{{ route('bag-products') }}">{{ $count_bag }}</button>

    <nav class="nav navbar-nav nav-menu">
        <ul>
            <li>
                <a href="#" class="open-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <img src="{{ asset((Auth::guard('client')->check() || Auth::guard('store')->check()) ? 'images/icon-profile.png' : 'images/icon-menu.png') }}" alt="Menu" />
                </a>

                <ul class="dropdown-menu">
                    @isset ($show_filter_products)
                        <li>
                            <a href="#" class="options" data-type="filter-products">Filtrar produtos</a>
                        </li>
                    @endisset

                    @if (Auth::guard('client')->check() || Auth::guard('store')->check() || Auth::guard('superadmin')->check())
                        @if (Auth::guard('client')->check())
                            <li>
                                <a href="{{ route('get-client-config') }}" class="{{ (isset($section) && $section == 'config') ? 'active' : '' }}">Minha conta</a>
                            </li>

                            <li>
                                <a href="{{ route('list-client-orders') }}" class="{{ (isset($section) && $section == 'order') ? 'active' : '' }}">Meus pedidos</a>
                            </li>

                            <li>
                                <a href="{{ route('list-client-messages') }}" class="{{ (isset($section) && $section == 'message') ? 'active' : '' }}">Mensagens</a>
                            </li>
                        @elseif (Auth::guard('store')->check() || Auth::guard('superadmin')->check())
                            @if (Auth::guard('superadmin')->check())
                                <li>
                                    <a href="{{ route('superadmin-store-register') }}" class="store-register">Cadastrar loja</a>
                                </li>

                                <li>
                                    <a href="#" class="options" data-type="list-stores">Selecionar loja</a>
                                </li>
                            @endif

                            @if (Auth::guard('store')->check() && Auth::guard('store')->user()->store->status)
                                <li>
                                    <a href="{{ route('show-store', Auth::guard('store')->user()->store->slug) }}" class="{{ (isset($section) && $section == 'store') ? 'active' : '' }}">Minha loja</a>
                                </li>
                            @elseif (Auth::guard('superadmin')->check() && Session::has('superadmin_store_status'))
                                <li>
                                    <a href="{{ route('show-store', session('superadmin_store_slug')) }}" class="{{ (isset($section) && $section == 'store') ? 'active' : '' }}">Minha loja</a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('get-create-edit-product') }}">Adicionar produtos</a>
                            </li>

                            <li>
                                <a href="{{ route('edit-products') }}" class="{{ (isset($section) && $section == 'edit') ? 'active' : '' }}">Editar produtos</a>
                            </li>

                            @if (Auth::guard('store')->check() || Auth::guard('superadmin')->check() && Auth::guard('superadmin')->user()->type == 1)
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
                        @endif

                        <li>
                            <a href="{{ route('logout') }}">Sair</a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('know') }}" class="{{ (isset($section) && $section == 'know') ? 'active' : '' }}">Entenda</a>
                        </li>

                        <li>
                            <a href="{{ route('how-works') }}" class="{{ (isset($section) && $section == 'how-works') ? 'active' : '' }}">Como funciona</a>
                        </li>

                        <li>
                            <a href="https://play.google.com/store/apps/details?id=app.naslojas" target="_blank">Baixe nosso app</a>
                        </li>

                        <li>
                            <a href="{{ route('client-register-get') }}" class="{{ (isset($section) && $section == 'client-register') ? 'active' : '' }}">Cadastrar</a>
                        </li>

                        <li>
                            <a href="{{ route('client-login-get') }}" class="{{ (isset($section) && $section == 'client-login') ? 'active' : '' }}">Entrar</a>
                        </li>
                    @endif
                </ul>
            </li>
        </ul>
    </nav>
</header>

<div class="filter-products">
    <div>
        <span>Ordenar</span>

        @foreach ($orderby as $ok => $o)
            <a href="#" data-value="{{ $ok }}" data-type="order" class="{{ (isset($search_order) && $search_order == $ok) ? 'active' : '' }}">{{ $o }}</a>
        @endforeach
    </div>

    <div>
        <span>Gênero</span>

        @foreach ($genders as $gk => $g)
            <a href="#" data-value="{{ $gk }}" data-type="gender" class="{{ (isset($search_gender) && $search_gender == $gk) ? 'active' : '' }}">{{ $g }}</a>
        @endforeach
    </div>
</div>

@if (Auth::guard('superadmin')->check())
    <div class="list-stores">
        <div>
            <span>Selecionar loja</span>

            @foreach ($superadmin_stores as $superadmin_store)
                <a href="{{ route('superadmin-set-store', $superadmin_store->id) }}" class="{{ session('superadmin_store_id') == $superadmin_store->id ? 'active' : '' }}">{{ $superadmin_store->name }}</a>
            @endforeach
        </div>
    </div>
@endif
