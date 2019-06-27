@extends('mobile.base')

@section('content')
    <div class="container page-admin">
        <h1 class="page-title">Pedidos de entrega</h1>
        <p class="page-description">Confira as informações dos produtos e das entregas</p>

        {!! Form::open(['method' => 'GET', 'route' => 'central-search-orders', 'id' => 'form-orders-search']) !!}
            {!! Form::text('keyword', $keyword ?? null, ['placeholder' => 'Busque pelo nome da loja, cliente, ou produto', 'required']) !!}
            {!! Form::submit('') !!}
        {!! Form::close() !!}

        <div class="list-orders">
            @foreach ($orders as $order)
                <div class="order">
                    <div class="resume-infos">
                        @foreach ($order->products as $product)
                            <div class="product">
                                <img src="{{ asset('uploads/' . $order->store_id . '/products/' . $product->image) }}" alt="{{ $product->title }}" />

                                <h3>{{ $product->title }}</h3>

                                <span class="item size">
                                    T: <span>{{ $product->size }}</span>
                                </span>

                                <span class="item qtd">
                                    Q: <span>{{ $product->qtd }}</span>
                                </span>
                            </div>
                        @endforeach

                        <span class="item" style="font-size: 13px;">
                            Frete - <span style="font-size: 15px; font-weight: 700;">{{ $order->freight != 0.00 ? 'R$ ' . number_format($order->freight, 2, ',', '.') : 'grátis' }}</span>
                        </span>
                    </div>

                    <div class="complete-infos">
                        <div class="group">
                            <span class="item">
                                <span>Loja:</span>

                                Vendido e entregue por {{ $order->store->name }}
                            </span>

                            <span class="item">
                                <span>Endereço:</span>

                                {{ $order->store->street }}, {{ $order->store->number }}

                                @if ($order->store->complement)
                                    - {{ $order->store->complement }}
                                @endif

                                - {{ $order->store->district }}

                                - {{ $order->store->city->title }}/{{ $order->store->city->state->letter }}
                            </span>

                            <span class="item">
                                <span>Telefone:</span>

                                {{ $order->store->phone }}
                            </span>
                        </div>

                        <div class="group">
                            <span class="item">
                                <span>Data do pedido:</span>

                                {{ date('d/m/Y', strtotime($order->created_at)) . ' às ' . date('H:i', strtotime($order->created_at)) }}
                            </span>

                            <span class="item">
                                <span>Forma de pagamento:</span>

                                {{ _getPaymentMethod($order->payment) }}
                            </span>
                        </div>

                        <div class="group">
                            <span class="item">
                                <span>Cliente:</span>

                                {{ $order->client_name }}
                            </span>

                            <span class="item">
                                <span>Telefone:</span>

                                {{ $order->client_phone }}
                            </span>

                            @if ($order->client_ip)
                                <span class="item">
                                    <span>IP:</span>

                                    {{ $order->client_ip }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @include('pagination', ['paginator' => $orders])
    </div>
@endsection
