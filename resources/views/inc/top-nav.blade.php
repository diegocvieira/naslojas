<header>
    <div class="container">
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/logo-naslojas.png') }}" alt="Logo naslojas" class="logo-desktop" />

            <img src="{{ asset('images/icon-logo-naslojas.png') }}" alt="Logo naslojas" class="logo-mobile" />
        </a>

        <?php /*@if (isset($store))
            {!! Form::open(['method' => 'GET', 'route' => 'form-search-store', 'id' => 'form-search']) !!}
                {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Digite aqui o produto que voce procura na loja ' . $store->name]) !!}

                {!! Form::hidden('store_slug', $store->slug) !!}

                {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
                {!! Form::hidden('gender', $search_gender ?? 'todos', ['id' => 'search-gender']) !!}

                {!! Form::submit('') !!}
            {!! Form::close() !!}
        @else
            {!! Form::open(['method' => 'GET', 'route' => 'form-search', 'id' => 'form-search']) !!}
                {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Digite aqui o produto que voce procura nas lojas de Pelotas']) !!}

                {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
                {!! Form::hidden('gender', $search_gender ?? 'todos', ['id' => 'search-gender']) !!}

                {!! Form::submit('') !!}
            {!! Form::close() !!}
        @endif*/ ?>

        {!! Form::open(['method' => 'GET', 'route' => (isset($store) ? 'form-search-store' : 'form-search'), 'id' => 'form-search']) !!}
            {!! Form::text('keyword', $keyword ?? '', ['placeholder' => (isset($store) ? 'Digite aqui o produto que voce procura na loja ' . $store->name : 'Digite aqui o produto que voce procura nas lojas de Pelotas')]) !!}

            @isset ($store)
                {!! Form::hidden('store_slug', $store->slug) !!}
            @endisset

            {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
            {!! Form::hidden('gender', $search_gender ?? 'todos', ['id' => 'search-gender']) !!}

            {!! Form::submit('') !!}
        {!! Form::close() !!}

        <nav>
            @if (Auth::guard('store')->check() || Auth::guard('superadmin')->check())
                <button class="open-menu logged" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::guard('store')->check() ? Auth::guard('store')->user()->store->name : 'Admin' }}
                </button>

                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('product-images') }}">Adicionar produtos</a>
                    </li>

                    <li>
                        <a href="{{ route('edit-products') }}">Editar produtos</a>
                    </li>

                    @if (Auth::guard('store')->check() || Auth::guard('superadmin')->check() && Auth::guard('superadmin')->user()->type == 1)
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
                        <a href="{{ route('logout') }}">Sair</a>
                    </li>
                </ul>
            @elseif (Auth::guard('client')->check())
                <button class="open-menu logged" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <span>{{ Auth::guard('client')->user()->name }} iooasido aisdb iasbdio basi boisab ibsai biaosb dibsa iodbia sbiob asid iobiodb ib</span>
                </button>

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
            @else
                <button class="open-menu unlogged" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"></button>

                <ul class="dropdown-menu">
                    <li>
                        <a href="#" class="open-how-works">Como funciona</a>
                    </li>

                    <li>
                        <a href="https://play.google.com/store/apps/details?id=app.naslojas" target="_blank">Baixe nosso app</a>
                    </li>

                    <li>
                        <a href="{{ route('client-register-get') }}">Cadastrar</a>
                    </li>

                    <li>
                        <a href="{{ route('client-login-get') }}">Entrar</a>
                    </li>
                </ul>
            @endif

            <a href="{{ route('bag-products') }}" class="open-bag {{ Auth::guard('client')->check() ? 'bag-logged' : '' }}">{{ $count_bag }}</a>
        </nav>
    </div>
</header>
