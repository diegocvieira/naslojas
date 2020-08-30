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
            {!! Form::text('keyword', $keyword ?? null, ['placeholder' => 'Procure no Naslojas', 'required']) !!}
    @else
        {!! Form::open(['method' => 'GET', 'route' => ['search-products', Cookie::get('city_slug'), Cookie::get('state_letter')], 'id' => 'form-search', 'class' => Auth::guard('store')->check() ? 'store-logged' : '']) !!}
            {!! Form::text('keyword', $keyword ?? '', ['placeholder' => 'Procure no Naslojas']) !!}
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
                    <a href="{{ route('home') }}" class="section-active">
                        <svg viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;">
                            <path d="M439.481,183.132V75.29h-89.184v39.234l-94.298-72.542L0,238.919l53.634,69.718l26.261-20.202v181.583h151.519V336.973 h55.151v133.045h145.543V288.435l26.261,20.202L512,238.92L439.481,183.132z M402.072,439.983h-85.473V306.938H201.378v133.045 h-91.449V265.329L256,152.965l146.071,112.364V439.983z M452.875,266.518L256,115.064L59.125,266.518l-17.006-22.106L256,79.876 l124.333,95.648v-70.199h29.114v92.596l60.433,46.491L452.875,266.518z" />
                        </svg>

                        <div class="nav-icon-content">Início</div>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <svg viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;">
                            <path stroke-width="10" d="M256,0C148.477,0,61,87.477,61,195c0,69.412,21.115,97.248,122.581,231.01C201.194,449.229,221.158,475.546,244,506 c2.833,3.777,7.279,6,12.001,6c4.722,0,9.167-2.224,12-6.002c22.708-30.29,42.585-56.507,60.123-79.638 C429.834,292.209,451,264.292,451,195C451,87.477,363.523,0,256,0z M304.219,408.235c-14.404,18.998-30.383,40.074-48.222,63.789 c-17.961-23.867-34.031-45.052-48.515-64.146C108.784,277.766,91,254.321,91,195c0-90.981,74.019-165,165-165s165,74.019,165,165 C421,254.205,403.17,277.722,304.219,408.235z" />
                            <path stroke-width="10" d="M256,90c-57.897,0-105,47.103-105,105c0,57.897,47.103,105,105,105c57.897,0,105-47.103,105-105 C361,137.103,313.897,90,256,90z M256,270c-41.355,0-75-33.645-75-75s33.645-75,75-75c41.355,0,75,33.645,75,75 S297.355,270,256,270z" />
                        </svg>

                        <div class="nav-icon-content">Cidade</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('bag-products') }}" class="open-bag {{ session('bag') ? 'cart-has-products' : '' }}">
                        <svg viewBox="-35 0 512 512.00102">
                            <path stroke-width="10" d="m443.054688 495.171875-38.914063-370.574219c-.816406-7.757812-7.355469-13.648437-15.15625-13.648437h-73.140625v-16.675781c0-51.980469-42.292969-94.273438-94.273438-94.273438-51.984374 0-94.277343 42.292969-94.277343 94.273438v16.675781h-73.140625c-7.800782 0-14.339844 5.890625-15.15625 13.648437l-38.9140628 370.574219c-.4492192 4.292969.9453128 8.578125 3.8320308 11.789063 2.890626 3.207031 7.007813 5.039062 11.324219 5.039062h412.65625c4.320313 0 8.4375-1.832031 11.324219-5.039062 2.894531-3.210938 4.285156-7.496094 3.835938-11.789063zm-285.285157-400.898437c0-35.175782 28.621094-63.796876 63.800781-63.796876 35.175782 0 63.796876 28.621094 63.796876 63.796876v16.675781h-127.597657zm-125.609375 387.25 35.714844-340.097657h59.417969v33.582031c0 8.414063 6.824219 15.238282 15.238281 15.238282s15.238281-6.824219 15.238281-15.238282v-33.582031h127.597657v33.582031c0 8.414063 6.824218 15.238282 15.238281 15.238282 8.414062 0 15.238281-6.824219 15.238281-15.238282v-33.582031h59.417969l35.714843 340.097657zm0 0"/>
                        </svg>

                        <div class="nav-icon-content">Sacola</div>
                    </a>
                </li>

                <li>
                    <a href="">
                        <svg viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;">
                            <path d="M256,0C114.842,0,0,114.842,0,256s114.842,256,256,256s256-114.842,256-256S397.158,0,256,0z M256,471.472 c-118.814,0-215.472-96.665-215.472-215.472S137.186,40.528,256,40.528c118.807,0,215.472,96.672,215.472,215.479 S374.807,471.472,256,471.472z" />
                            <path d="M256,301.425c-71.997,0-139.361,36.103-189.67,101.65l32.152,24.668c42.466-55.32,98.408-85.79,157.518-85.79 s115.051,30.47,157.511,85.79l32.152-24.668C395.354,337.528,327.997,301.425,256,301.425z" />
                            <path d="M256,91.863c-58.103,0-105.372,47.573-105.372,106.047S197.897,303.958,256,303.958s105.372-47.573,105.372-106.047 S314.103,91.863,256,91.863z M256,263.43c-35.752,0-64.844-29.389-64.844-65.52s29.092-65.52,64.844-65.52 s64.844,29.396,64.844,65.52S291.752,263.43,256,263.43z" />
                        </svg>

                        <div class="nav-icon-content">Conta</div>
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
