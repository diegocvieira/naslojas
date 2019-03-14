@php
    $top_nav = true;
    $body_class = 'bg-white';
@endphp

@extends('mobile.base')

@section('content')
    <div class="container page-bag-order-data">
        <div class="header-bag">
            <h1>Dados do pedido</h1>

            <p>Informe como deseja receber o produto e a forma de pagamento</p>

            <span class="warning">TROCA FÁCIL DIRETAMENTE NA LOJA</span>
        </div>

            {!! Form::model($client, ['method' => 'POST', 'route' => 'bag-finish', 'id' => 'form-bag-finish']) !!}
                <div class="fields">
                    <div class="group">
                        {!! Form::label('client', 'Cliente') !!}

                        {!! Form::text('name', null, ['id' => 'client', 'readonly']) !!}
                    </div>

                    <div class="group">
                        {!! Form::label('phone', 'Telefone') !!}

                        {!! Form::text('phone', null, ['id' => 'phone', 'placeholder' => 'Digite aqui', 'class' => 'mask-phone']) !!}
                    </div>

                    <div class="group">
                        {!! Form::label('cpf', 'CPF') !!}

                        {!! Form::text('cpf', null, ['id' => 'cpf', 'placeholder' => 'Digite aqui', 'class' => 'mask-cpf']) !!}
                    </div>

                    <div class="group payment">
                        {!! Form::label('', 'Pagamento') !!}

                        <div class="buttons">
                            {!! Form::radio('payment', '1', null, ['id' => 'payment-credit', 'autocomplete' => 'off']) !!}
                            {!! Form::label('payment-credit', 'Crédito') !!}

                            {!! Form::radio('payment', '2', null, ['id' => 'payment-debit', 'autocomplete' => 'off']) !!}
                            {!! Form::label('payment-debit', 'Débito') !!}

                            {!! Form::radio('payment', '0', null, ['id' => 'payment-money', 'autocomplete' => 'off']) !!}
                            {!! Form::label('payment-money', 'Dinheiro') !!}
                        </div>
                    </div>

                    <div class="group payment-card">
                        {!! Form::label('', 'Cartão') !!}

                        <select name="payment_card" class="selectpicker custom-validate" title="Bandeira">
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

                    <div class="group client-address">
                        {!! Form::label('', 'Endereço') !!}

                        {!! Form::text('cep', null, ['placeholder' => 'Cep', 'id' => 'cep']) !!}

                        {!! Form::text('street', null, ['placeholder' => 'Logradouro', 'id' => 'street']) !!}

                        {!! Form::text('number', null, ['placeholder' => 'Número', 'class' => 'half']) !!}

                        {!! Form::text('complement', null, ['placeholder' => 'Complemento', 'class' => 'half margin']) !!}

                        {!! Form::select('district', $districts, $client->district_id, ['class' => 'selectpicker', 'title' => 'Bairro']) !!}

                        {!! Form::text('city', $client->city_id ? $client->city->title : null, ['placeholder' => 'Cidade', 'id' => 'city', 'class' => 'half']) !!}

                        {!! Form::text('state', $client->city_id ? $client->city->state->letter : null, ['placeholder' => 'Estado', 'id' => 'state', 'class' => 'half margin']) !!}
                    </div>

                    <div class="group reserve_hour">
                        {!! Form::label('', 'Entrega') !!}

                        {{ _businessDay() }}
                    </div>
                </div>

                <div class="orders">
                    @foreach ($bag_data as $key => $data)
                        <div class="order">
                            <span class="order-number">Pedido {{ $key + 1 }}</span>

                            <div class="left">
                                <span class="item">{{ $data['store'] }}</span>

                                <span class="item">Frete</span>

                                <span class="item">Subtotal</span>
                            </div>

                            <div class="right">
                                <span class="item">R$ {{ number_format($data['subtotal'], 2, ',', '.') }}</span>

                                <span class="item update-freight">{{ $data['freight'] ? number_format($data['freight'], 2, ',', '.') : '-----' }}</span>

                                <span class="item update-subtotal" data-subtotal="{{ $data['subtotal'] }}">R$ {{ number_format($data['subtotal'] + $data['freight'], 2, ',', '.') }}</span>
                            </div>

                            <span class="parcels" data-minparcelprice="{{ $data['min_parcel_price'] }}" data-maxparcel="{{ $data['max_parcel'] }}"></span>
                        </div>
                    @endforeach

                    <div class="footer-bag">
                        {!! Form::submit('ENVIAR PEDIDO') !!}

                        <p>Lembre-se que você vai receber e pagar os pedidos de cada loja separadamente.</p>
                    </div>
                </div>
            {!! Form::close() !!}
    </div>
@endsection
