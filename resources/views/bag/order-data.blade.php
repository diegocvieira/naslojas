<?php
    $top_nav = true;
    $body_class = 'bg-white';
?>

@extends('base')

@section('content')
    <div class="container page-bag-order-data">
        <div class="header-bag">
            <h1>Dados do pedido</h1>

            <p>Informe como deseja receber o produto e a forma de pagamento</p>

            <a href="{{ route('home') }}" class="keep-buying">Continuar comprando</a>
        </div>

        {!! Form::model($client, ['method' => 'POST']) !!}

            <div class="row">
                <div class="col-xs-6 fields">
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
                            {!! Form::text('phone', null, ['id' => 'phone', 'placeholder' => 'Digite aqui']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            {!! Form::label('cpf', 'CPF') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::text('cpf', null, ['id' => 'cpf', 'placeholder' => 'Digite aqui']) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3">
                            {!! Form::label('freight', 'Frete') !!}
                        </div>

                        <div class="col-xs-9 buttons">
                            {!! Form::radio('freight', '0', null, ['id' => 'freight-house']) !!}
                            {!! Form::label('freight-house', 'Receber em casa') !!}

                            {!! Form::radio('freight', '1', null, ['id' => 'freight-store']) !!}
                            {!! Form::label('freight-store', 'Retirar na loja') !!}
                        </div>
                    </div>

                    <div class="row payment">
                        <div class="col-xs-3">
                            {!! Form::label('payment', 'Pagamento') !!}
                        </div>

                        <div class="col-xs-9 buttons">
                            {!! Form::radio('payment', '1', null, ['id' => 'payment-credit']) !!}
                            {!! Form::label('payment-credit', 'Crédito') !!}

                            {!! Form::radio('payment', '2', null, ['id' => 'payment-debit']) !!}
                            {!! Form::label('payment-debit', 'Débito') !!}

                            {!! Form::radio('payment', '3', null, ['id' => 'payment-money']) !!}
                            {!! Form::label('payment-money', 'Dinheiro') !!}
                        </div>
                    </div>

                    <div class="row address">
                        <div class="col-xs-3">
                            {!! Form::label('payment', 'Endereço') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::text('cep', null, ['placeholder' => 'Cep', 'id' => 'cep']) !!}

                            {!! Form::text('street', null, ['placeholder' => 'Logradouro', 'id' => 'street']) !!}

                            {!! Form::text('number', null, ['placeholder' => 'Número', 'class' => 'half']) !!}

                            {!! Form::text('complement', null, ['placeholder' => 'Complemento', 'class' => 'half margin']) !!}

                            {!! Form::select('district', $districts, null, ['class' => 'selectpicker', 'title' => 'Bairro']) !!}

                            {!! Form::text('city', null, ['placeholder' => 'Cidade', 'id' => 'city', 'class' => 'half']) !!}

                            {!! Form::text('state', null, ['placeholder' => 'Estado', 'id' => 'state', 'class' => 'half margin']) !!}
                        </div>
                    </div>

                    <div class="row reserve_hour">
                        <div class="col-xs-3">
                            {!! Form::label('reserve_hour', 'Data e horário') !!}
                        </div>

                        <div class="col-xs-9">
                            {!! Form::select('reserve_hour', $reserve_hours, null, ['class' => 'selectpicker', 'title' => 'Agendar para']) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-6 orders">
                    @foreach ($bag_data as $key => $data)
                        <div class="row order">
                            <span class="order-number">Pedido {{ $key + 1 }}</span>

                            <div class="col-xs-6 text-left">
                                <span class="item">Loja {{ $data['store'] }}</span>

                                <span class="item">Frete</span>

                                <span class="item">Subtotal</span>
                            </div>

                            <div class="col-xs-6 text-right">
                                <span class="item">R$ {{ number_format($data['subtotal'], 2, ',', '.') }}</span>

                                <span class="item">{{ $data['freight'] ? 'R$ ' . number_format($data['freight'], 2, ',', '.') : '-----' }}</span>

                                <span class="item">R$ {{ number_format($data['subtotal'] + $data['freight'], 2, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach

                    <div class="row">
                        <div class="col-xs-6">
                            ssl
                        </div>

                        <div class="col-xs-6">
                            {!! Form::submit('ENVIAR PEDIDO') !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <p>Lembre-se que você vai receber e pagar os pedidos de cada loja separadamente.</p>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
