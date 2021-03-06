<?php
    $top_nav = true;
    $body_class = 'bg-white';
?>

@extends('base')

@section('content')
    <div class="container page-bag-order-data">
        <div class="row">
            <div class="col-xs-12">
                <div class="header-bag">
                    <h1>Dados do pedido</h1>

                    <p>Informe como deseja receber o produto e a forma de pagamento</p>

                    <a href="{{ route('home') }}" class="keep-buying">Continuar comprando</a>
                </div>
            </div>
        </div>

        <div class="row">
            {!! Form::model($client, ['method' => 'POST', 'route' => 'bag-finish', 'id' => 'form-bag-finish']) !!}
                {!! Form::hidden('client_ip') !!}

                <div class="col-xs-6 fields">
                    <div class="row">
                        <div class="col-xs-12">
                            <span class="warning"></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            {!! Form::label('client', 'Cliente') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::text('name', null, ['id' => 'client', 'readonly']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            {!! Form::label('phone', 'Celular') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::text('phone', null, ['id' => 'phone', 'placeholder' => 'Digite aqui', 'class' => 'mask-phone']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            {!! Form::label('cpf', 'CPF') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::text('cpf', null, ['id' => 'cpf', 'placeholder' => 'Digite aqui', 'class' => 'mask-cpf']) !!}
                        </div>
                    </div>

                    <div class="row payment">
                        <div class="col-xs-3">
                            {!! Form::label('', 'Pagamento') !!}
                        </div>

                        <div class="col-xs-9 buttons">
                            {!! Form::radio('payment', '1', null, ['id' => 'payment-credit', 'autocomplete' => 'off']) !!}
                            {!! Form::label('payment-credit', 'Cr??dito') !!}

                            {!! Form::radio('payment', '2', null, ['id' => 'payment-debit', 'autocomplete' => 'off']) !!}
                            {!! Form::label('payment-debit', 'D??bito') !!}

                            {!! Form::radio('payment', '0', null, ['id' => 'payment-money', 'autocomplete' => 'off']) !!}
                            {!! Form::label('payment-money', 'Dinheiro') !!}
                        </div>
                    </div>

                    <div class="row payment-card">
                        <div class="col-xs-3">
                            {!! Form::label('', 'Cart??o') !!}
                        </div>

                        <div class="col-xs-9">
                            <select name="payment_card" class="selectpicker select-payment-card custom-validate" title="Bandeira">
                                @foreach(_paymentMethods() as $payment_key => $payment)
                                    @foreach($payment as $payment_type_key => $payment_type)
                                        @foreach($payment_type as $payment_description_key => $payment_description)
                                            @if (in_array($payment_key . '-' . $payment_description_key, $payments))
                                                <option value="{{ $payment_key . '-' . $payment_description_key }}" data-method="{{ $payment_key }}">{{ $payment_description }}</option>
                                            @endif
                                        @endforeach
                                    @endforeach
                                 @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row client-address">
                        <div class="col-xs-3">
                            {!! Form::label('', 'Endere??o') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::text('cep', null, ['placeholder' => 'Cep', 'id' => 'cep']) !!}

                            {!! Form::text('street', null, ['placeholder' => 'Endere??o', 'id' => 'street']) !!}

                            {!! Form::text('number', null, ['placeholder' => 'N??mero', 'class' => 'half']) !!}

                            {!! Form::text('complement', null, ['placeholder' => 'Complemento', 'class' => 'half margin']) !!}

                            {!! Form::select('district', $districts, $client->district_id, ['class' => 'selectpicker', 'title' => 'Bairro']) !!}

                            {!! Form::text('city', $client->city_id ? $client->city->title : null, ['placeholder' => 'Cidade', 'id' => 'city', 'class' => 'half']) !!}

                            {!! Form::text('state', $client->city_id ? $client->city->state->letter : null, ['placeholder' => 'Estado', 'id' => 'state', 'class' => 'half margin']) !!}
                        </div>
                    </div>

                    <div class="row reserve_hour">
                        <div class="col-xs-3">
                            {!! Form::label('', 'Entrega') !!}
                        </div>

                        <div class="col-xs-9">
                            <span class="date-reserve">{{ _businessDay() }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-xs-6 orders">
                    @foreach ($bag_data as $key => $data)
                        <div class="row order">
                            <span class="order-number">Pedido {{ $key + 1 }}</span>

                            <div class="col-xs-6 text-left">
                                <span class="item">{{ $data['store'] }}</span>

                                <span class="item">Frete</span>

                                <span class="item">Subtotal</span>
                            </div>

                            <div class="col-xs-6 text-right">
                                <span class="item">R$ {{ number_format($data['subtotal'], 2, ',', '.') }}</span>

                                <span class="item update-freight" data-freefreight="{{ $data['free_freight'] }}">
                                    @if (is_numeric($data['freight']) && $data['freight'] == 0)
                                        gr??tis
                                    @elseif (is_numeric($data['freight']) && $data['freight'] > 0)
                                        {{ number_format($data['freight'], 2, ',', '.') }}
                                    @else
                                        -----
                                    @endif
                                </span>

                                <span class="item update-subtotal" data-subtotal="{{ $data['subtotal'] }}">R$ {{ number_format($data['subtotal'] + $data['freight'], 2, ',', '.') }}</span>
                            </div>

                            <div class="col-xs-12 text-right">
                                <span class="parcels" data-minparcelprice="{{ $data['min_parcel_price'] }}" data-maxparcel="{{ $data['max_parcel'] }}"></span>
                            </div>
                        </div>
                    @endforeach

                    <div class="footer-bag">
                        <img src="{{ asset('images/ssl.png') }}" class="logo-ssl" />

                        {!! Form::submit('ENVIAR PEDIDO') !!}

                        <p class="text-freight">Lembre-se que voc?? vai receber e pagar os pedidos de cada loja separadamente.</p>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
