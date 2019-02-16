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
                            {!! Form::label('phone', 'Telefone') !!}
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

                    <div class="row">
                        <div class="col-xs-3">
                            {!! Form::label('freight', 'Frete') !!}
                        </div>

                        <div class="col-xs-9 buttons">
                            {!! Form::radio('freight', '0', null, ['id' => 'freight-house', 'autocomplete' => 'off']) !!}
                            {!! Form::label('freight-house', 'Receber em casa') !!}

                            {!! Form::radio('freight', '1', null, ['id' => 'freight-store', 'autocomplete' => 'off']) !!}
                            {!! Form::label('freight-store', 'Retirar na loja') !!}
                        </div>
                    </div>

                    <div class="row store-adress freight-field freight-store">
                        <div class="col-xs-3">
                            {!! Form::label('payment', 'Endereço') !!}
                        </div>

                        <div class="col-xs-9">
                            @foreach ($bag_data as $key => $data)
                                <div class="item">
                                    <span class="order-number">Retirar pedido {{ $key + 1 }}</span>

                                    <span class="store-name">{{ $data['store'] }}</span>

                                    <span class="info-address">
                                        {{ $data['street'] }}, {{ $data['number'] }}

                                        @if ($data['complement'])
                                            - {{ $data['complement'] }}
                                        @endif
                                    </span>

                                    <span class="info-address">
                                        {{ $data['district'] }} - {{ $data['city'] }}/{{ $data['state'] }}
                                    </span>

                                    <a href="//maps.google.com/?q={{ $data['street'] }}, {{ $data['number'] }}, {{ $data['district'] }}, {{ $data['city'] }}, {{ $data['state'] }}" target="_blank">
                                        ver no mapa
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="row payment freight-field freight-house">
                        <div class="col-xs-3">
                            {!! Form::label('payment', 'Pagamento') !!}
                        </div>

                        <div class="col-xs-9 buttons">
                            {!! Form::radio('payment', '1', null, ['id' => 'payment-credit', 'class' => 'custom-validate']) !!}
                            {!! Form::label('payment-credit', 'Crédito') !!}

                            {!! Form::radio('payment', '2', null, ['id' => 'payment-debit', 'class' => 'custom-validate']) !!}
                            {!! Form::label('payment-debit', 'Débito') !!}

                            {!! Form::radio('payment', '3', null, ['id' => 'payment-money', 'class' => 'custom-validate']) !!}
                            {!! Form::label('payment-money', 'Dinheiro') !!}
                        </div>
                    </div>

                    <div class="row client-address freight-field freight-house">
                        <div class="col-xs-3">
                            {!! Form::label('payment', 'Endereço') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::text('cep', null, ['placeholder' => 'Cep', 'id' => 'cep', 'class' => 'custom-validate']) !!}

                            {!! Form::text('street', null, ['placeholder' => 'Logradouro', 'id' => 'street', 'class' => 'custom-validate']) !!}

                            {!! Form::text('number', null, ['placeholder' => 'Número', 'class' => 'half custom-validate']) !!}

                            {!! Form::text('complement', null, ['placeholder' => 'Complemento', 'class' => 'half margin']) !!}

                            {!! Form::select('district', $districts, $client->district_id, ['class' => 'selectpicker custom-validate', 'title' => 'Bairro']) !!}

                            {!! Form::text('city', $client->city_id ? $client->city->title : null, ['placeholder' => 'Cidade', 'id' => 'city', 'class' => 'half custom-validate']) !!}

                            {!! Form::text('state', $client->city_id ? $client->city->state->letter : null, ['placeholder' => 'Estado', 'id' => 'state', 'class' => 'half margin custom-validate']) !!}
                        </div>
                    </div>

                    <div class="row reserve_hour freight-field freight-house">
                        <div class="col-xs-3">
                            {!! Form::label('reserve_date', 'Data e horário') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::select('reserve_date', $reserve_hours, null, ['class' => 'selectpicker custom-validate', 'title' => 'Agendar para', 'data-size' => '8']) !!}
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

                                <span class="item update-freight">{{ $data['freight'] ? (is_numeric($data['freight']) ? 'R$ ' . number_format($data['freight'], 2, ',', '.') : 'grátis') : '-----' }}</span>

                                <span class="item update-subtotal" data-subtotal="{{ $data['subtotal'] }}">R$ {{ number_format($data['subtotal'] + $data['freight'], 2, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="footer-bag">
                        <img src="{{ asset('images/ssl.png') }}" class="logo-ssl" />

                        {!! Form::submit('ENVIAR PEDIDO') !!}

                        <p class="text-freight-store text-freight">Lembre-se que você deve retirar e pagar os pedidos em cada loja separadamente.</p>
                        <p class="text-freight-house text-freight">Lembre-se que você vai receber e pagar os pedidos de cada loja separadamente.</p>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
