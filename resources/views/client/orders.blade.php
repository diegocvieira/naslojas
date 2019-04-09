@php
    $top_nav = true;
@endphp

@extends('base')

@section('content')
    <div class="container page-admin">
        @if ($orders->count())
            <div class="page-header">
                <h1>Meus pedidos</h1>

                <p>Confira os dados e o status dos pedidos que você realizou</p>
            </div>

            <div class="list-orders">
                @foreach ($orders as $order)
                    <div class="row order">
                        <div class="col-xs-12 resume-infos">
                            <div class="row">
                                <div class="col-xs-9 line">
                                    @foreach ($order->products as $product)
                                        <div class="row product">
                                            <div class="col-xs-2">
                                                <img src="{{ asset('uploads/' . $product->product->store_id . '/products/' . $product->image) }}" alt="{{ $product->title }}" class="product-image img-responsive" />
                                            </div>

                                            <div class="col-xs-7">
                                                <h3>{{ $product->title }}</h3>

                                                <span class="item">
                                                    Tamanho selecionado:

                                                    <span>{{ $product->size }}</span>
                                                </span>

                                                @if ($product->status == 0 || $product->status == 3)
                                                    <span class="item">
                                                        <span class="red">PEDIDO CANCELADO</span>
                                                    </span>
                                                @elseif ($product->status == 1)
                                                    <span class="item">
                                                        <span class="green">PEDIDO CONFIRMADO</span>
                                                    </span>
                                                @else
                                                    <span class="item">
                                                        <span class="pending">PEDIDO PENDENTE</span>
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="col-xs-3 text-center">
                                                <span class="item">
                                                    Quantidade:

                                                    <span>{{ $product->qtd }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-xs-3 prices">
                                    @php
                                        $subtotal = 0;
                                    @endphp

                                    @foreach ($order->products as $key => $product)
                                        <span class="item">
                                            <span>{{ $order->products->count() > 1 ? 'produto ' . ($key + 1) : 'produto' }}</span>

                                            @if ($product->status == 0 || $product->status == 3)
                                                <b>CANCELADO</b>
                                            @else
                                                R$ {{ number_format($product->price, 2, ',', '.') }}

                                                @php
                                                    $subtotal += $product->price * $product->qtd;
                                                @endphp
                                            @endif
                                        </span>
                                    @endforeach

                                    <span class="item">
                                        <span>frete</span>

                                        {{ $order->freight != 0.00 ? 'R$ ' . number_format($order->freight, 2, ',', '.') : 'grátis' }}
                                    </span>

                                    <span class="item total">
                                        <span>total</span>

                                        R$ {{ number_format($subtotal + $order->freight, 2, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 complete-infos">
                            @foreach ($order->products as $key => $product)
                                <div class="group">
                                    <span class="item">
                                        <span>{{ $order->products->count() > 1 ? 'Produto ' . ($key + 1) : 'Produto' }}:</span>

                                        #{{ $product->product->identifier }}
                                    </span>

                                    <span class="item">
                                        <span>Informação:</span>

                                        @if ($product->status == 0 || $product->status == 3)
                                            Cancelado - A loja não possui mais o tamanho selecionado deste produto. Ele foi removido e a loja foi notificada
                                        @elseif ($product->status == 1)
                                            Confirmado - Certifique-se que haverá alguém para receber o produto e realizar o pagamento
                                        @else
                                            Pendente - Aguarde a confirmação da loja
                                        @endif
                                    </span>
                                </div>
                            @endforeach

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

                                    <a href="//maps.google.com/?q={{ $order->store->street }}, {{ $order->store->number }}, {{ $order->store->district }}, {{ $order->store->city->title }}, {{ $order->store->city->state->letter }}" target="_blank" class="map">
                                        ver no mapa
                                    </a>
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
                                    <span>Entrega:</span>

                                    {{ _businessDay($order->created_at) }}
                                </span>

                                <span class="item">
                                    <span>Endereço:</span>

                                    {{ $order->client_street }}, {{ $order->client_number }}

                                    @if ($order->client_complement)
                                        - {{ $order->client_complement }}
                                    @endif

                                    - {{ $order->district->name }}

                                    - {{ $order->city->title }}/{{ $order->city->state->letter }}
                                </span>

                                <span class="item">
                                    <span>Forma de pagamento:</span>

                                    {{ _getPaymentMethod($order->payment) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('pagination', ['paginator' => $orders])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Você ainda não possui nenhum <br> pedido de produto</p>
            </div>
        @endif
    </div>
@endsection
