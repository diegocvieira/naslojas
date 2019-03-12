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
                    <a href="{{ route('get-store-config', 'store-profile') }}" class="option {{ (!isset($navigation) || isset($navigation) && $navigation == 'store-profile') ? 'active' : '' }}">Dados da loja</a>

                    <div class="fields {{ (!isset($navigation) || isset($navigation) && $navigation == 'store-profile') ? 'show-fields' : '' }}">
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
                    </div>
                </li>

                <li>
                    <a href="{{ route('get-store-config', 'address') }}" class="option {{ (isset($navigation) && $navigation == 'address') ? 'active' : '' }}">Endereço</a>

                    <div class="fields {{ (isset($navigation) && $navigation == 'address') ? 'show-fields' : '' }}">
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
                </li>

                <li>
                    <a href="{{ route('get-store-config', 'freights') }}" class="option {{ (isset($navigation) && $navigation == 'freights') ? 'active' : '' }}">Frete</a>

                    <div class="fields freights-fields {{ (isset($navigation) && $navigation == 'freights') ? 'show-fields' : '' }}">
                        @foreach ($districts as $key => $district)
                            <div class="form-group">
                                {!! Form::hidden('district_id[]', $district->id) !!}

                                @foreach ($user->store->freights as $store_freight)
                                    @php
                                        $accept_districts[] = $store_freight->district_id;
                                    @endphp

                                    @if ($store_freight->district_id == $district->id)
                                        {!! Form::text('freight_price[' . $key . ']', $store_freight->price, ['placeholder' => ' ', 'class' => 'mask-money', 'required']) !!}
                                    @endif
                                @endforeach

                                @if (!isset($accept_districts) || !in_array($district->id, $accept_districts))
                                    {!! Form::text('freight_price[' . $key . ']', null, ['placeholder' => ' ', 'class' => 'mask-money', 'required']) !!}
                                @endif

                                {!! Form::label('', $district->name) !!}
                            </div>
                        @endforeach
                    </div>
                </li>

                <li>
                    <a href="{{ route('get-store-config', 'payment') }}" class="option {{ (isset($navigation) && $navigation == 'payment') ? 'active' : '' }}">Formas de pagamento</a>

                    <div class="fields {{ (isset($navigation) && $navigation == 'payment') ? 'show-fields' : '' }}">
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
                            {!! Form::label('', 'Valor mínimo da parcela') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('max_parcel', null, ['placeholder' => ' ', 'class' => 'mask-number']) !!}
                            {!! Form::label('', 'Máximo de parcelas') !!}
                        </div>

                        <div class="form-group">
                            {!! Form::text('max_product_unit', null, ['placeholder' => ' ', 'class' => 'mask-number']) !!}
                            {!! Form::label('', 'Máximo de unidades por produto') !!}
                        </div>
                    </div>
                </li>

                <li>
                    <a href="{{ route('get-store-config', 'access') }}" class="option {{ (isset($navigation) && $navigation == 'access') ? 'active' : '' }}">Dados de acesso</a>

                    <div class="fields {{ (isset($navigation) && $navigation == 'access') ? 'show-fields' : '' }}">
                        {!! Form::input('password', 'current_password') !!}

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
                </li>

                <li>
                    <a href="{{ route('get-store-config') }}" class="activate-profile">
                        Perfil da loja

                        <span class="switch {{ $user->store->status ? 'active-profile' : '' }}"></span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('delete-store-account') }}" id="delete-store-account">Deletar conta</a>
                </li>
            </ul>

            {!! Form::submit('SALVAR ALTERAÇÕES') !!}
        {!! Form::close() !!}
    </div>
@endsection
