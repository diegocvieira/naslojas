@extends('app', ['body_class' => 'bg-white'])

@section('content')
    @include ('mobile.inc.header')

    <div class="container page-bag-success">
        <span class="icon-check"></span>

        <h1>Pedido realizado com sucesso!</h1>

        <p>Aguarde no endereço informado para receber o seu pedido.</p>

        <span><b>Entrega:</b> {{ _businessDay($order->created_at) }}</span>
        <span>
            <b>Endereço:</b>
            {{ $order->client_street }}, {{ $order->client_number }}

            @if ($order->client_complement)
                - {{ $order->client_complement }}
            @endif

             - {{ $order->district->name }}
             - {{ $order->city->title }}/{{ $order->city->state->letter }}
         </span>

        <a href="{{ route('home') }}" class="link">VOLTAR PARA O INÍCIO</a>
    </div>

    @include ('mobile.inc.footer')
@endsection
