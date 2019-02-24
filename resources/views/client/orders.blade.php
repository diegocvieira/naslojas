@php
    $top_nav = true;
@endphp

@extends('base')

@section('content')
    <div class="container page-admin">
        @if ($products->count())
            <div class="page-header">
                <h1>Meus pedidos</h1>

                <p>Confira os dados e o status dos pedidos que você realizou</p>
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
                                        <span>Confirmação da loja:</span>
                                        {{ $product->order->confirmed_at ? date('d/m/Y', strtotime($product->order->confirmed_at)) . ' às ' . date('H:i', strtotime($product->order->confirmed_at)) : '---------' }}
                                    </span>

                                    <span class="item">
                                        <span>Status:</span>
                                        @if ($product->status == 0)
                                            <span class="red">Pedido recusado</span>
                                        @elseif ($product->status == 1)
                                            <span class="green">Pedido confirmado</span>
                                        @else
                                            Pedido pendente
                                        @endif
                                    </span>
                                </div>

                                <div class="group">
                                    <span class="item">
                                        <span>Loja:</span>
                                        Vendido e entregue por {{ $product->product->store->name }}
                                    </span>

                                    <span class="item">
                                        <span>Endereço:</span>
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

                                    <span class="item">
                                        <span>Telefone:</span>
                                        {{ $product->product->store->phone }}
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
                                            A loja não possui mais o tamanho {{ $product->size }} deste produto!
                                            <br>
                                            Já o retiramos do site e notificamos a loja para que isso não ocorra novamente.
                                        @elseif ($product->status == 1 && $product->order->freight_type == 1)
                                            O seu pedido ficará reservado para você na loja até a data e horário indicado acima.
                                            <br>
                                            Passe na loja e informe o seu nome para finalizar a compra e retirar o produto.
                                        @elseif ($product->status == 1 && $product->order->freight_type == 0)
                                            Certifique-se que haverá alguém no endereço de entrega no horário
                                            <br>
                                            agendado para receber o produto e realizar o pagamento.
                                        @else
                                            Aguarde a confirmação da loja ou tente entrar em contato pelo telefone acima.
                                        @endif
                                    </span>
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
