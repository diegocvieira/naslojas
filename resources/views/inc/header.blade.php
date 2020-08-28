<header>
    <div class="container-fluid">
        <a href="{{ route('home') }}" id="logo-naslojas">
            <img src="{{ asset('images/logo-naslojas.png') }}" />
        </a>

        @if (isset($store))
            {!! Form::open(['method' => 'GET', 'route' => ['search-store-products', $store->slug], 'id' => 'form-search']) !!}
                {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Digite aqui o produto que você procura na loja ' . $store->name]) !!}
        @else
            {!! Form::open(['method' => 'GET', 'route' => ['search-products', Cookie::get('city_slug'), Cookie::get('state_letter')], 'id' => 'form-search']) !!}
                {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Digite aqui o produto que você procura']) !!}
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

                {!! Form::submit('') !!}
            {!! Form::close() !!}

        <div id="header-cities-container">
            <input type="hidden" value="{{ $cities }}" id="header-cities" />

            <input type="text" name="city_id" placeholder="{{ _cityIsSet() ? Cookie::get('city_name') . ' - ' . Cookie::get('state_letter') : 'Selecione a sua cidade' }}" id="header-search-city" autocomplete="off" />

            <div id="header-list-cities"></div>
        </div>

        <nav class="nav navbar-nav nav-menu">
            <ul>
                @if (Auth::guard('store')->check())
                    <li>
                        <a href="#" class="logged" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span>{{ Auth::guard('store')->check() ? Auth::guard('store')->user()->store->name : 'Admin' }}</span>

                            <img src="{{ asset('images/icon-profile.png') }}" alt="Foto de perfil de {{ Auth::guard('store')->check() ? Auth::guard('store')->user()->store->name : 'Admin' }}" />
                        </a>

                        <ul class="dropdown-menu dropdown-store">
                            @if (Auth::guard('store')->check() && Auth::guard('store')->user()->store->status)
                                <li>
                                    <a href="{{ route('show-store', Auth::guard('store')->user()->store->slug) }}">Minha loja</a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('product-images') }}">Adicionar produtos</a>
                            </li>

                            <li>
                                <a href="{{ route('edit-products') }}">Editar produtos</a>
                            </li>

                            @if (Auth::guard('store')->check())
                                <li>
                                    <a href="{{ route('list-store-orders') }}">Pedidos</a>
                                </li>

                                <li>
                                    <a href="{{ route('list-store-messages') }}">Mensagens</a>
                                </li>
                            @endif

                            <li>
                                <a href="{{ route('get-store-config') }}">Configurações</a>
                            </li>

                            <li>
                                <a href="{{ route('tutorials', 'adicionar-produtos') }}">Tutoriais</a>
                            </li>

                            <li>
                                <a href="{{ route('logout') }}">Sair</a>
                            </li>
                        </ul>
                    </li>
                @elseif (Auth::guard('client')->check())
                    <li>
                        <a href="{{ route('bag-products') }}" class="open-bag">
                            Sacola

                            <span class="bag-container {{ session('bag') ? 'cart-has-products' : '' }}">
                                <img src="{{ asset('images/icon-bag.png') }}" alt="Sacola" />
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="#" class="logged" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span>{{ Auth::guard('client')->user()->name }}</span>

                            <img src="{{ asset('images/icon-profile.png') }}" alt="Foto de perfil de {{ Auth::guard('client')->user()->name }}" />
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('get-client-config') }}" class="icon-account show-client-config">Minha conta</a>
                            </li>

                            <li>
                                <a href="{{ route('list-client-orders') }}" class="icon-check">Meus pedidos</a>
                            </li>

                            <li>
                                <a href="{{ route('list-client-messages') }}" class="icon-messages">Mensagens</a>
                            </li>

                            <li>
                                <a href="{{ route('logout') }}" class="icon-logout">Sair</a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ route('client-register-get') }}">Cadastrar</a>
                    </li>

                    <li>
                        <a href="{{ route('client-login-get') }}">Entrar</a>
                    </li>

                    <li>
                        <a href="{{ route('bag-products') }}" class="open-bag">
                            <span class="bag-container {{ session('bag') ? 'cart-has-products' : '' }}">
                                <img src="{{ asset('images/icon-bag.png') }}" alt="Sacola" />
                            </span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</header>

<div id="warning-cookies">
    <div class="container">
        <p>
            Utilizamos cookies para aprimorar a sua experiência de navegação. Se permanecer no nosso site, você estará concordando com a nossa Política de Cookies, <a href="{{ route('privacy-policy') }}">Política de Privacidade</a> e <a href="{{ route('terms-use') }}">Termos de uso</a>.
        </p>

        <button type="button" class="confirm-warning-cookies">OK</button>
    </div>
</div>
