@php
    $top_nav_store = true;
@endphp

@extends('base')

@section('content')
    <div class="container page-admin">
        @if ($products->count())
            <div class="page-header">
                <h1>Pedidos</h1>

                <p class="page-subtitle">Confira os dados e responda as solicitações de pedidos</p>
            </div>

            <div class="list-orders">
                @foreach ($products as $product)
                    <div class="row order">
                        <div class="resume-infos">
                            <div class="col-xs-1 image">
                                <img src="{{ asset('uploads/' . $product->product->store_id . '/products/' . $product->image) }}" alt="{{ $product->title }}" />
                            </div>

                            <div class="col-xs-7">
                                <h3>{{ $product->title }}</h3>

                                <span class="item">
                                    Tamanho selecionado:
                                    <span>{{ $product->size }}</span>
                                </span>
                            </div>

                            <div class="col-xs-2 text-center">
                                <span class="item">
                                    Quantidade:
                                    <span>{{ $product->qtd }}</span>
                                </span>
                            </div>

                            <div class="col-xs-2 text-center">
                                <span class="item">
                                    <span>R$ {{ number_format(($product->price + $product->freight_price) * $product->qtd, 2, ',', '.') }}</span>
                                </span>
                            </div>
                        </div>

                        <div class="complete-infos">
                            <div class="col-xs-10 col-xs-offset-1">
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

                                    <span class="item">
                                        <span>Status:</span>

                                        @if ($product->status == 0)
                                            <span class="status red">Pedido recusado</span>
                                        @elseif ($product->status == 1)
                                            <span class="status green">Pedido confirmado</span>
                                        @else
                                            <span class="status pending">Pedido pendente</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="group">
                                    <span class="item">
                                        <span>Forma de pagamento:</span>
                                        {{ _getPaymentMethod($product->order->payment) }}
                                    </span>

                                    <span class="item">
                                        <span>Frete:</span>

                                        {{ $product->order->freight_type == 0 ? 'Receber em casa' : 'Retirar na loja' }}
                                    </span>

                                    @if ($product->order->freight_type == 0)
                                        <span class="item">
                                            <span>Endereço:</span>

                                            {{ $product->order->client_street }}, {{ $product->order->client_number }}

                                            @if ($product->order->client_complement)
                                                - {{ $product->order->client_complement }}
                                            @endif

                                            - {{ $product->order->district->name }}

                                            - {{ $product->order->city->title }}/{{ $product->order->city->state->letter }}
                                        </span>
                                    @endif

                                    <span class="item">
                                        <span>Data e horário:</span>

                                        {{ $product->order->reserve_date }}
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

                                    @if (!$product->product->deleted_at && $product->product->status == 1)
                                        <a href="{{ route('show-product', $product->product->slug) }}" target="_blank">ver produto</a>
                                    @endif
                                </div>
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
