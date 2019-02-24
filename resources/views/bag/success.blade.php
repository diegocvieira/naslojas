@php
    $top_nav = true;
@endphp

@extends('base')

@section('content')
    <div class="container page-bag-success">
        <div class="row">
            <div class="col-xs-12">
                <span class="icon-check"></span>

                <h1>Pedido realizado com sucesso!</h1>

                @if ($order->freight_type == 1) <!--Retirar na loja-->
                    <p>Passe na loja no endereço e horário informado para retirar seu pedido.</p>

                    <span>
                        <b>Horário:</b> {{ $order->reserve_date }}
                    </span>

                    @foreach ($products as $product)
                        <span>
                            <b>{{ $product->product->store->name }}:</b>
                            {{ $product->product->store->street }}, {{ $product->product->store->number }}

                            @if ($product->product->store->complement)
                                - {{ $product->product->store->complement }}
                            @endif

                             - {{ $product->product->store->district }}
                             - {{ $product->product->store->city->title }}/{{ $product->product->store->city->state->letter }}

                             <a href="//maps.google.com/?q={{ $product->product->store->street }}, {{ $product->product->store->number }}, {{ $product->product->store->district }}, {{ $product->product->store->city->title }}, {{ $product->product->store->city->state->letter }}" target="_blank" class="map">
                 				ver no mapa
                 			</a>
                        </span>
                    @endforeach
                @else <!--Receber em casa-->
                    <p>Aguarde no endereço e horário informado para receber o seu pedido.</p>

                    <span><b>Horário:</b> {{ $order->reserve_date }}</span>
                    <span>
                        <b>Endereço:</b>
                        {{ $order->client_street }}, {{ $order->client_number }}

                        @if ($order->client_complement)
                            - {{ $order->client_complement }}
                        @endif

                         - {{ $order->district->name }}
                         - {{ $order->city->title }}/{{ $order->city->state->letter }}
                     </span>
                @endif

                <a href="{{ route('home') }}" class="link">VOLTAR PARA O INÍCIO</a>
            </div>
        </div>
    </div>
@endsection
