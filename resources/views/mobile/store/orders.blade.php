@php
    $top_nav = true;
    $body_class = 'bg-white';
@endphp

@extends('mobile.base')

@section('content')
    <div class="container page-admin">
        @if ($products->count())
            <h1 class="page-title">Pedidos</h1>
            <p class="page-description">Confira os dados e responda as solicitações de pedidos</p>

            <div class="list-orders">
                @foreach ($products as $product)
                    <div class="order">
                        <div class="resume-infos">
                            <div class="top">
                                <img src="{{ asset('uploads/' . $product->product->store_id . '/products/' . $product->image) }}" alt="{{ $product->title }}" />

                                @if (!$product->product->deleted_at && $product->product->status == 1)
                                    <a href="{{ route('show-product', $product->product->slug) }}" class="show-product"></a>
                                @endif
                            </div>

                            <h3>{{ $product->title }}</h3>

                            <span class="item">
                                <span>R$ {{ number_format(($product->price + $product->freight_price) * $product->qtd, 2, ',', '.') }}</span>
                            </span>

                            <span class="item">
                                Tamanho selecionado:

                                <span>{{ $product->size }}</span>
                            </span>

                            <span class="item">
                                Quantidade:

                                <span>{{ $product->qtd }}</span>
                            </span>

                            <span class="item">
                                @if ($product->status == 0)
                                    <span class="status red">PEDIDO RECUSADO</span>
                                @elseif ($product->status == 1)
                                    <span class="status green">PEDIDO CONFIRMADO</span>
                                @else
                                    <span class="status pending">PEDIDO PENDENTE</span>
                                @endif
                            </span>

                            <button class="show-more-infos">ver mais</button>
                        </div>

                        <div class="complete-infos">
                            <div class="group">
                                <span class="item">
                                    <span>Produto:</span>

                                    #{{ $product->product->identifier }}
                                </span>
                            </div>

                            <div class="group">
                                <span class="item">
                                    <span>Data do pedido:</span>

                                    {{ date('d/m/Y', strtotime($product->order->created_at)) . ' às ' . date('H:i', strtotime($product->order->created_at)) }}
                                </span>
                            </div>

                            <div class="group">
                                <span class="item">
                                    <span>Cliente:</span>

                                    {{ $product->order->client_name }}
                                </span>

                                <span class="item">
                                    <span>Telefone:</span>

                                    {{ $product->order->client_phone }}
                                </span>

                                <span class="item">
                                    <span>CPF:</span>

                                    {{ $product->order->client_cpf }}
                                </span>
                            </div>

                            <div class="group">
                                <span class="item">
                                    <span>Forma de pagamento:</span>

                                    {{ _getPaymentMethod($product->order->payment) }}
                                </span>

                                <span class="item">
                                    <span>Endereço:</span>

                                    {{ $product->order->client_street }}, {{ $product->order->client_number }}

                                    @if ($product->order->client_complement)
                                        - {{ $product->order->client_complement }}
                                    @endif

                                    - {{ $product->order->district->name }}

                                    - {{ $product->order->city->title }}/{{ $product->order->city->state->letter }}
                                </span>

                                <span class="item">
                                    <span>Entrega:</span>

                                    {{ _businessDay($product->order->created_at) }}
                                </span>
                            </div>

                            <div class="group">
                                <span class="item">
                                    <span>Informação:</span>

                                    @if ($product->status == 0)
                                        O cliente foi notificado e o tamanho {{ $product->size }} do produto foi removido do naslojas.
                                        <br>
                                        Mantenha os produtos atualizados para não perder relevância no site.
                                        <br>
                                        Se isso for recorrente todos os seus produtos serão removidos.
                                    @elseif ($product->status == 1 && $product->order->freight_type == 1)
                                        Reserve o pedido na loja até a data e horário indicado acima.
                                        <br>
                                        O cliente irá até a loja para finalizar a compra.
                                    @elseif ($product->status == 1 && $product->order->freight_type == 0)
                                        Chame um entregador e o oriente com as informações necessárias para
                                        <br>
                                        realizar a entrega do pedido no endereço e horário indicado acima.
                                    @else
                                        Certifique-se que o produto foi separado antes de confirmar a solicitação.
                                    @endif
                                </span>
                            </div>

                            <div class="group btns">
                                @if ($product->status == 2)
                                    <button data-url="{{ route('confirm-order', $product->id) }}" class="confirm-order" type="button">ACEITAR ENTREGA</button>

                                    <button data-url="{{ route('refuse-order', $product->id) }}" class="refuse-order" type="button">RECUSAR ENTREGA</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('pagination', ['paginator' => $products])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Você ainda não possui nenhum <br> pedido de produto</p>
            </div>
        @endif
    </div>
@endsection
