@php
    $top_nav = true;
    $body_class = 'bg-white';
@endphp

@extends('mobile.base')

@section('content')
    <div class="container page-admin">
        @if ($orders->count())
            <h1 class="page-title">Meus pedidos</h1>
            <p class="page-description">Confira os dados e o status dos pedidos que você realizou</p>

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

                                    <span class="item">
                                        @if ($product->status == 0 || $product->status == 3)
                                            <span class="red"><b>PEDIDO CANCELADO</b></span>
                                        @elseif ($product->status == 1)
                                            <span class="green"><b>PEDIDO CONFIRMADO</b></span>
                                        @else
                                            <span class="pending"><b>PEDIDO PENDENTE</b></span>
                                        @endif
                                    </span>
                                </div>
                            @endforeach

                            @php
                                $subtotal = 0;
                            @endphp

                            @foreach ($order->products as $key => $product)
                                <span class="item">
                                    {{ $order->products->count() > 1 ? 'Produto ' . ($key + 1) : 'Produto' }}

                                    @if ($product->status == 0 || $product->status == 3)
                                        <span><b>CANCELADO</b></span>
                                    @else
                                        <span>R$ {{ number_format($product->price, 2, ',', '.') }}</span>

                                        @php
                                            $subtotal += $product->price * $product->qtd;
                                        @endphp
                                    @endif
                                </span>
                            @endforeach

                            <span class="item">
                                Frete <span>{{ $order->freight != 0.00 ? 'R$ ' . number_format($order->freight, 2, ',', '.') : 'grátis' }}</span>
                            </span>

                            <span class="item total">
                                Total <span>R$ {{ number_format($subtotal + $order->freight, 2, ',', '.') }}</span>
                            </span>
                        </div>

                        <div class="complete-infos">
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
