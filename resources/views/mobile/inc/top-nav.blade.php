<header>
    @if (isset($admin_search) && isset($keyword) || isset($back))
        <a href="{{ route('edit-products') }}" class="btn-back-search"></a>
    @else
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/icon-logo-naslojas.png') }}" alt="Logo naslojas.com" />
        </a>
    @endif

    @if (isset($store))
        {!! Form::open(['method' => 'GET', 'route' => ['search-store-products', $store->slug], 'id' => 'form-search', 'class' => Auth::guard('store')->check() ? 'store-logged' : '']) !!}
            {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Pesquisar na loja']) !!}
    @elseif (isset($admin_search))
        {!! Form::open(['method' => 'GET', 'route' => 'form-search-admin', 'id' => 'form-search', 'class' => Auth::guard('store')->check() ? 'store-logged' : '']) !!}
            {!! Form::text('keyword', $keyword ?? null, ['placeholder' => 'Pesquise aqui', 'required']) !!}
    @else
        {!! Form::open(['method' => 'GET', 'route' => ['search-products', Cookie::get('city_slug'), Cookie::get('state_letter_lc')], 'id' => 'form-search', 'class' => Auth::guard('store')->check() ? 'store-logged' : '']) !!}
            {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Pesquise aqui']) !!}
    @endif
            {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
            {!! Form::hidden('gender', $search_gender ?? '', ['id' => 'search-gender']) !!}
            {!! Form::hidden('min_price', $search_min_price ?? '', ['id' => 'search-min-price']) !!}
            {!! Form::hidden('max_price', $search_max_price ?? '', ['id' => 'search-max-price']) !!}
            {!! Form::hidden('size', $search_size ?? '', ['id' => 'search-size']) !!}
            {!! Form::hidden('off', $search_off ?? '', ['id' => 'search-off']) !!}
            {!! Form::hidden('installment', $search_installment ?? '', ['id' => 'search-installment']) !!}
            {!! Form::hidden('brand', $search_brand ?? '', ['id' => 'search-brand']) !!}
            {!! Form::hidden('freight', $search_freight ?? '', ['id' => 'search-freight']) !!}
            {!! Form::hidden('category', $search_category ?? '', ['id' => 'search-category']) !!}
            {!! Form::hidden('color', $search_color ?? '', ['id' => 'search-color']) !!}
        {!! Form::close() !!}

    @if (Auth::guard('store')->check())
        <nav class="nav navbar-nav nav-menu nav-store">
            <ul>
                <li>
                    <a href="{{ route('list-store-messages') }}" class="{{ (isset($section) && $section == 'message') ? 'active' : '' }}"></a>
                </li>
            </ul>
        </nav>
    @else
        <button class="open-bag" data-url="{{ route('bag-products') }}">{{ $count_bag }}</button>

        <nav class="nav navbar-nav nav-menu">
            <ul>
                <li>
                    <a href="#" class="open-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset((Auth::guard('client')->check() || Auth::guard('store')->check()) ? 'images/icon-profile.png' : 'images/icon-menu.png') }}" alt="Menu" />
                    </a>

                    <ul class="dropdown-menu">
                        <li style="border-bottom: 1px solid #e6e6e6;">
                            <a href="#" class="show-select-district">{{ session('client_district_name') ?? 'Selecione seu bairro' }}</a>
                        </li>

                        @if (Auth::guard('client')->check() || Auth::guard('superadmin')->check())
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
                            @elseif (Auth::guard('superadmin')->check())
                                <li>
                                    <a href="{{ route('superadmin-store-register') }}" class="store-register">Cadastrar loja</a>
                                </li>

                                <li>
                                    <a href="#" class="options" data-type="list-stores">Selecionar loja</a>
                                </li>

                                @if (Auth::guard('superadmin')->check() && Session::has('superadmin_store_status'))
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

                                @if (Auth::guard('superadmin')->check() && Auth::guard('superadmin')->user()->type == 1)
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
                            <!-- <li>
                                <a href="{{ route('how-works') }}" class="{{ (isset($section) && $section == 'how-works') ? 'active' : '' }}">Como funciona</a>
                            </li> -->

                            <li>
                                <a href="https://play.google.com/store/apps/details?id=app.naslojas" target="_blank">Baixe nosso app</a>
                            </li>

                            <!-- <li>
                                <a href="{{ route('store-advertise') }}">Vender online</a>
                            </li> -->

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
    @endif
</header>

<div class="select-district-container">
    <button class="close-select-district"></button>

    <h4 class="select-district-title">Bairros</h4>

    <ul>
        @foreach ($districts as $district)
            <li>
                <a href="{{ route('client-district-set', $district->id) }}">{{ $district->name }}</a>
            </li>
        @endforeach
    </ul>
</div>

@if (Auth::guard('store')->check())
    <ul class="store-navigation">
        @if (Auth::guard('store')->check() && Auth::guard('store')->user()->store->status)
            <li>
                <a href="{{ route('show-store', Auth::guard('store')->user()->store->slug) }}" class="store {{ (isset($section) && $section == 'store') ? 'active' : '' }}"></a>
            </li>
        @endif

        <li>
            <a href="{{ route('edit-products') }}" class="edit {{ (isset($section) && $section == 'edit') ? 'active' : '' }}"></a>
        </li>

        <li>
            <a href="{{ route('get-create-edit-product') }}" class="add {{ (isset($section) && $section == 'add') ? 'active' : '' }}"></a>
        </li>

        <li>
            <a href="{{ route('list-store-orders') }}" class="order {{ (isset($section) && $section == 'order') ? 'active' : '' }}"></a>
        </li>

        <li>
            <a href="{{ route('get-store-config') }}" class="config {{ (isset($section) && $section == 'config') ? 'active' : '' }}"></a>
        </li>
    </ul>
@endif

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
