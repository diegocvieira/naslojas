@php
    $top_nav = true;
    $body_class = 'bg-white';
@endphp

@extends('mobile.base')

@section('content')
    <div class="container page-admin page-store-config">
        <h1 class="page-title">Configurações</h1>
        <p class="page-description">Mantenha os dados da loja sempre atualizados</p>

        {!! Form::model($user->store, ['method' => 'POST', 'route' => 'set-store-config', 'id' => 'form-store-config']) !!}
            {!! Form::hidden('section', $navigation ?? 'store-profile') !!}

            <ul class="navigation">
                <li>
                    <a href="{{ route('get-store-config', 'store-profile') }}">Dados da loja</a>

                    @if (isset($navigation) && $navigation == 'store-profile')
                        <div class="fields">
                            <div class="form-group name">
                                {!! Form::text('name', null, ['placeholder' => ' ']) !!}
                                {!! Form::label('', 'Nome da loja') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('phone', null, ['placeholder' => ' ', 'class' => 'mask-phone']) !!}
                                {!! Form::label('', 'Telefone') !!}
                            </div>

                            <div class="form-group slug">
                                {!! Form::text('slug', null, ['placeholder' => ' ', 'id' => 'slug', 'class' => 'move-placeholder']) !!}
                                {!! Form::label('', 'Url') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('cnpj', null, ['placeholder' => ' ', 'class' => 'mask-cnpj']) !!}
                                {!! Form::label('', 'CNPJ') !!}
                            </div>

                            <div class="image-cover">
                                <span class="title">Imagem de capa (computador)</span>
                                <span class="desc">Tamanho ideal 1920 x 350 pixels</span>

                                {!! Form::file('image_cover_desktop', ['autocomplete' => 'off', 'accept' => 'image/*']) !!}

                                <div class="image">
                                    <img src="{{ asset($user->store->image_cover_desktop ? 'uploads/' . $user->store_id . '/' . $user->store->image_cover_desktop : 'images/image-cover-desktop.jpg')  }}" alt="Imagem de capa do desktop" />
                                </div>

                                {!! Form::radio('delete_image_cover_desktop', 1, null, ['autocomplete' => 'off']) !!}
                                <button type="button" class="delete-image-cover" @if (!$user->store->image_cover_desktop) style="display: none;" @endif></button>
                            </div>

                            <div class="image-cover image-cover-mobile">
                                <span class="title">Imagem de capa (celular)</span>
                                <span class="desc">Tamanho ideal 1080 x 600 pixels</span>

                                {!! Form::file('image_cover_mobile', ['autocomplete' => 'off', 'accept' => 'image/*']) !!}

                                <div class="image">
                                    <img src="{{ asset($user->store->image_cover_mobile ? 'uploads/' . $user->store_id . '/' . $user->store->image_cover_mobile : 'images/image-cover-mobile.jpg')  }}" alt="Imagem de capa do mobile" />
                                </div>

                                {!! Form::radio('delete_image_cover_mobile', 1, null, ['autocomplete' => 'off']) !!}
                                <button type="button" class="delete-image-cover mobile" @if (!$user->store->image_cover_mobile) style="display: none;" @endif></button>
                            </div>
                        </div>
                    @endif
                </li>

                <li>
                    <a href="{{ route('get-store-config', 'address') }}">Endereço</a>

                    @if (isset($navigation) && $navigation == 'address')
                        <div class="fields">
                            <div class="form-group cep">
                                {!! Form::text('cep', null, ['placeholder' => ' ', 'id' => 'cep']) !!}
                                {!! Form::label('', 'Cep') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('street', null, ['placeholder' => ' ', 'id' => 'street']) !!}
                                {!! Form::label('', 'Endereço') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('number', null, ['placeholder' => ' ', 'id' => 'number']) !!}
                                {!! Form::label('', 'Número') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('complement', null, ['placeholder' => ' ']) !!}
                                {!! Form::label('', 'Complemento') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('district', null, ['placeholder' => ' ', 'id' => 'district']) !!}
                                {!! Form::label('', 'Bairro') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('city', $user->store->city_id ? $user->store->city->title : null, ['placeholder' => ' ', 'id' => 'city']) !!}
                                {!! Form::label('', 'Cidade') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('state', $user->store->city_id ? $user->store->city->state->letter : null, ['placeholder' => ' ', 'id' => 'state']) !!}
                                {!! Form::label('', 'Estado') !!}
                            </div>
                        </div>
                    @endif
                </li>

                <li>
                    <a href="{{ route('get-store-config', 'freights') }}">Frete</a>

                    @if (isset($navigation) && $navigation == 'freights')
                        <div class="fields freights-fields">
                            @foreach ($districts as $key => $district)
                                <div class="form-group">
                                    {!! Form::hidden('district_id[]', $district->id) !!}

                                    @foreach ($user->store->freights as $store_freight)
                                        @php
                                            $accept_districts[] = $store_freight->district_id;
                                        @endphp

                                        @if ($store_freight->district_id == $district->id)
                                            {!! Form::text('freight_price[' . $key . ']', $store_freight->price, ['placeholder' => ' ', 'class' => 'mask-money']) !!}
                                        @endif
                                    @endforeach

                                    @if (!isset($accept_districts) || !in_array($district->id, $accept_districts))
                                        {!! Form::text('freight_price[' . $key . ']', null, ['placeholder' => ' ', 'class' => 'mask-money']) !!}
                                    @endif

                                    {!! Form::label('', $district->name) !!}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </li>

                <li>
                    <a href="{{ route('get-store-config', 'payment') }}">Formas de pagamento</a>

                    @if (isset($navigation) && $navigation == 'payment')
                        <div class="fields">
                            <div class="payment-methods">
                                @foreach(_paymentMethods() as $payment_key => $payment)
                                    @foreach($payment as $payment_type_key => $payment_type)
                                        <div class="payment-group">
                                            <span class="payment-type">
                                                {{ $payment_type_key }}
                                            </span>

                                            @foreach($payment_type as $payment_description_key => $payment_description)
                                                <div class="payment-description">
                                                    {!! Form::checkbox('payment[]', $payment_key . '-' . $payment_description_key, in_array($payment_key . '-' . $payment_description_key, $payments) ? true : false, ['id' => 'payment' . $payment_key . $payment_description_key, 'disabled' => ($payment_description == 'à vista' || $payment_description == 'Visa' || $payment_description == 'MasterCard') ? true : false]) !!}

                                                    {!! Form::label('payment' . $payment_key . $payment_description_key, $payment_description) !!}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                 @endforeach
                             </div>

                            <div class="form-group">
                                {!! Form::text('min_parcel_price', null, ['placeholder' => ' ', 'class' => 'mask-money']) !!}
                                {!! Form::label('', 'Valor da parcela mínima') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('max_parcel', null, ['placeholder' => ' ', 'class' => 'mask-number']) !!}
                                {!! Form::label('', 'Máximo de parcelas sem juros') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('max_product_unit', null, ['placeholder' => ' ', 'class' => 'mask-number']) !!}
                                {!! Form::label('', 'Máximo de unidades vendidas por produto') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('free_freight_price', null, ['placeholder' => ' ', 'class' => 'mask-money']) !!}
                                {!! Form::label('', 'Frete grátis nas compras acima de') !!}
                            </div>
                        </div>
                    @endif
                </li>

                <li>
                    <a href="{{ route('get-store-config', 'access') }}">Dados de acesso</a>

                    @if (isset($navigation) && $navigation == 'access')
                        <div class="fields">
                            <div class="form-group">
                                {!! Form::email('email', $user->email, ['placeholder' => ' ']) !!}
                                {!! Form::label('', 'E-mail') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::input('password', 'password', null, ['placeholder' => ' ', 'id' => 'password']) !!}
                                {!! Form::label('', 'Nova senha') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::input('password', 'password_confirmation', null, ['placeholder' => ' ']) !!}
                                {!! Form::label('', 'Confirmar nova senha') !!}
                            </div>
                        </div>
                    @endif
                </li>

                <li>
                    <a href="{{ route('get-store-config') }}" class="activate-profile">
                        @if ($user->store->status)
                            <span class="title">Loja ativada</span>
                            <span class="switch active-profile"></span>
                        @else
                            <span class="title">Loja desativada</span>
                            <span class="switch"></span>
                        @endif
                    </a>
                </li>

                <li>
                    <a href="{{ route('logout') }}">Sair</a>
                </li>

                <li>
                    <a href="{{ route('delete-store-account') }}" id="delete-store-account">Deletar conta</a>
                </li>
            </ul>

            {!! Form::submit('SALVAR ALTERAÇÕES') !!}
        {!! Form::close() !!}
    </div>
@endsection
