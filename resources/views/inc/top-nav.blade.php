<header>
    <div class="container">
        <a href="{{ url('/') }}" id="logo-naslojas">
            <img src="{{ asset('images/logo-naslojas.png') }}" />
        </a>

        @if(isset($store))
            {!! Form::open(['method' => 'GET', 'route' => 'form-search-store', 'id' => 'form-search']) !!}
                {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Digite aqui o produto que voce procura na loja ' . $store->name]) !!}

                {!! Form::hidden('store_slug', $store->slug) !!}

                {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
                {!! Form::hidden('gender', $search_gender ?? 'todos', ['id' => 'search-gender']) !!}

                {!! Form::submit('') !!}
            {!! Form::close() !!}
        @else
            {!! Form::open(['method' => 'GET', 'route' => 'form-search', 'id' => 'form-search']) !!}
                {!! Form::text('keyword', $keyword ?? '', ['placeholder' => Cookie::get('sessao_cidade_title') ? 'Digite aqui o produto que voce procura nas lojas de ' . Cookie::get('sessao_cidade_title') : 'Digite aqui o produto que voce procura nas lojas de Pelotas']) !!}

                {!! Form::hidden('order', $search_order ?? '', ['id' => 'search-order']) !!}
                {!! Form::hidden('gender', $search_gender ?? 'todos', ['id' => 'search-gender']) !!}

                {!! Form::submit('') !!}
            {!! Form::close() !!}
        @endif

        <nav class="nav navbar-nav nav-menu">
            <ul>
                @if (Auth::guard('store')->check())
                    <li>
                        <a href="#" class="logged" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span>{{ Auth::guard('store')->user()->store->name }}</span>

                            <img src="{{ asset('images/icon-profile.png') }}" alt="Foto de perfil de {{ Auth::guard('store')->user()->store->name }}" />
                        </a>

                        <ul class="dropdown-menu">
                            @if(Auth::guard('store')->user()->store->status)
                                <li>
                                    <a href="{{ route('show-store', Auth::guard('store')->user()->store->slug) }}" class="icon-store">Minha loja</a>
                                </li>
                            @endif

                            <li>
                                <a href="#" class="icon-add">Adicionar produtos</a>
                            </li>

                            <li>
                                <a href="#" class="icon-edit">Editar produtos</a>
                            </li>

                            <li>
                                <a href="#" class="icon-check">Confirmações</a>
                            </li>

                            <li>
                                <a href="#" class="icon-reserve">Reservas</a>
                            </li>

                            <li>
                                <a href="#" class="icon-messages">Mensagens</a>
                            </li>

                            <li>
                                <a href="{{ route('get-store-config') }}" class="icon-config show-store-config">Configurações</a>
                            </li>

                            <li>
                                <a href="{{ route('logout') }}" class="icon-logout">Sair</a>
                            </li>
                        </ul>
                    </li>
                @elseif (Auth::guard('client')->check())
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
                                <a href="#" class="icon-reserve">Reservas</a>
                            </li>

                            <li>
                                <a href="#" class="icon-messages">Mensagens</a>
                            </li>

                            <li>
                                <a href="{{ route('logout') }}" class="icon-logout">Sair</a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="#" class="open-how-works">Como funciona</a>
                    </li>

                    <li>
                        <a href="{{ route('client-register-get') }}">Cadastrar</a>
                    </li>

                    <li>
                        <a href="{{ route('client-login-get') }}">Entrar</a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</header>
