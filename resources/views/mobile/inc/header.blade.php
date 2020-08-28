<header id="header-top">
    @if (isset($admin_search) && isset($keyword) || isset($back))
        <a href="{{ route('edit-products') }}" class="btn-back-search"></a>
    @else
        <a href="{{ route('home') }}" id="logo-naslojas">
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
        {!! Form::open(['method' => 'GET', 'route' => ['search-products', Cookie::get('city_slug'), Cookie::get('state_letter')], 'id' => 'form-search', 'class' => Auth::guard('store')->check() ? 'store-logged' : '']) !!}
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
        <a href="{{ route('bag-products') }}" class="open-bag {{ session('bag') ? 'cart-has-products' : '' }}"></a>

        <nav class="nav navbar-nav nav-menu">
            <ul>
                <li>
                    <a href="#" class="open-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset((Auth::guard('client')->check() || Auth::guard('store')->check()) ? 'images/icon-profile.png' : 'images/icon-menu.png') }}" alt="Menu" />
                    </a>

                    <ul class="dropdown-menu">
                        <li style="border-bottom: 1px solid #e6e6e6;">
                            <a href="#" class="show-header-cities">{{ _cityIsSet() ? Cookie::get('city_name') . '-' . Cookie::get('state_letter') : 'Selecione sua cidade' }}</a>
                        </li>

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

                            <li>
                                <a href="{{ route('logout') }}">Sair</a>
                            </li>
                        @else
                            <!-- <li>
                                <a href="https://play.google.com/store/apps/details?id=app.naslojas" target="_blank">Baixe nosso app</a>
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

@if (Auth::guard('client')->check())
    <header id="header-bottom">
        <nav>
            <ul>
                <li>
                    <a href="#">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <g>
	                            <g>
		                            <path d="M256,0C156.011,0,74.667,81.344,74.667,181.333c0,96.725,165.781,317.099,172.843,326.443 c1.984,2.667,5.163,4.224,8.491,4.224c3.328,0,6.507-1.557,8.491-4.224c7.061-9.344,172.843-229.717,172.843-326.443 C437.333,81.344,355.989,0,256,0z M256,277.333c-52.928,0-96-43.072-96-96c0-52.928,43.072-96,96-96s96,43.072,96,96 C352,234.261,308.928,277.333,256,277.333z"/>
	                            </g>
                            </g>
                        </svg>

                        <div class="nav-icon-content">Sua cidade</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('bag-products') }}" class="open-bag {{ session('bag') ? 'cart-has-products' : '' }}">
                        <svg viewBox="-35 0 512 512.00102" xmlns="http://www.w3.org/2000/svg">
                            <path d="m443.054688 495.171875-38.914063-370.574219c-.816406-7.757812-7.355469-13.648437-15.15625-13.648437h-73.140625v-16.675781c0-51.980469-42.292969-94.273438-94.273438-94.273438-51.984374 0-94.277343 42.292969-94.277343 94.273438v16.675781h-73.140625c-7.800782 0-14.339844 5.890625-15.15625 13.648437l-38.9140628 370.574219c-.4492192 4.292969.9453128 8.578125 3.8320308 11.789063 2.890626 3.207031 7.007813 5.039062 11.324219 5.039062h412.65625c4.320313 0 8.4375-1.832031 11.324219-5.039062 2.894531-3.210938 4.285156-7.496094 3.835938-11.789063zm-285.285157-400.898437c0-35.175782 28.621094-63.796876 63.800781-63.796876 35.175782 0 63.796876 28.621094 63.796876 63.796876v16.675781h-127.597657zm-125.609375 387.25 35.714844-340.097657h59.417969v33.582031c0 8.414063 6.824219 15.238282 15.238281 15.238282s15.238281-6.824219 15.238281-15.238282v-33.582031h127.597657v33.582031c0 8.414063 6.824218 15.238282 15.238281 15.238282 8.414062 0 15.238281-6.824219 15.238281-15.238282v-33.582031h59.417969l35.714843 340.097657zm0 0"/>
                        </svg>

                        <!-- <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M467.952,427.383l-25.3-245.673c-1.045-10.187-9.627-17.93-19.867-17.93h-332.9c-10.213,0-18.789,7.717-19.861,17.877 L44.044,427.45c-2.144,21.452,5.007,43.011,19.614,59.156C78.266,502.739,98.986,512,120.511,512h271.653 c21.425,0,41.952-9.075,56.293-24.914C462.999,471.06,470.103,449.328,467.952,427.383z M418.876,460.261 c-6.898,7.603-16.385,11.791-26.712,11.791H120.518c-10.26,0-20.187-4.468-27.238-12.257c-7.051-7.79-10.506-18.13-9.494-28.257 l24.075-227.803h296.9l23.449,227.657C429.276,442.198,425.953,452.451,418.876,460.261z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M259.663,0c-63.144,0-114.518,51.373-114.518,114.518v69.243h39.948v-69.243c0-41.12,33.45-74.57,74.57-74.57 c41.12,0,74.576,33.45,74.576,74.57v69.243h39.941v-69.243C374.181,51.373,322.808,0,259.663,0z"/>
                                </g>
                            </g>
                        </svg> -->

                        <div class="nav-icon-content">Sacola</div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M256,0C114.842,0,0,114.842,0,256s114.842,256,256,256s256-114.842,256-256S397.158,0,256,0z M256,471.472 c-118.814,0-215.472-96.665-215.472-215.472S137.186,40.528,256,40.528c118.807,0,215.472,96.672,215.472,215.479 S374.807,471.472,256,471.472z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M256,301.425c-71.997,0-139.361,36.103-189.67,101.65l32.152,24.668c42.466-55.32,98.408-85.79,157.518-85.79 s115.051,30.47,157.511,85.79l32.152-24.668C395.354,337.528,327.997,301.425,256,301.425z"/>
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path d="M256,91.863c-58.103,0-105.372,47.573-105.372,106.047S197.897,303.958,256,303.958s105.372-47.573,105.372-106.047 S314.103,91.863,256,91.863z M256,263.43c-35.752,0-64.844-29.389-64.844-65.52s29.092-65.52,64.844-65.52 s64.844,29.396,64.844,65.52S291.752,263.43,256,263.43z"/>
                                </g>
                            </g>
                        </svg>

                        <div class="nav-icon-content">Sua conta</div>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
@endif

<div id="header-cities-container">
    <div class="header-cities-top">
        <input type="hidden" value="{{ $cities }}" id="header-cities" />

        <input type="text" name="city_id" placeholder="Selecione a sua cidade" id="header-search-city" autocomplete="off" />

        <button class="close-header-cities"></button>
    </div>

    <div id="header-list-cities"></div>
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
