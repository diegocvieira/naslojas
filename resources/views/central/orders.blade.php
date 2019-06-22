@extends('base')

@section('content')
    <div class="container page-admin">
        <div class="page-header">
            <h1>Pedidos de entregas</h1>

            <p class="page-subtitle">Confira as informações dos produtos e das entregas</p>
        </div>

        {!! Form::open(['method' => 'GET', 'route' => 'central-search-orders', 'id' => 'form-orders-search']) !!}
            {!! Form::text('keyword', $keyword ?? null, ['placeholder' => 'Busque pelo nome da loja, cliente, ou produto', 'required']) !!}
            {!! Form::submit('') !!}
        {!! Form::close() !!}

        <div class="list-orders">
            @foreach ($orders as $order)
                <div class="row order">
                    <div class="col-xs-12 resume-infos">
                        @foreach ($order->products as $product)
                            <div class="row product">
                                <div class="col-xs-2">
                                    <img src="{{ asset('uploads/' . $product->product->store_id . '/products/' . $product->image) }}" alt="{{ $product->title }}" class="product-image img-responsive" />
                                </div>

                                <div class="col-xs-8">
                                    <h3>{{ $product->title }}</h3>

                                    <span class="item">
                                        Tamanho selecionado:

                                        <span>{{ $product->size }}</span>
                                    </span>

                                    <span class="item">
                                        Quantidade:

                                        <span>{{ $product->qtd }}</span>
                                    </span>
                                </div>

                                <div class="col-xs-2 text-center" style="line-height: 7; font-size: 17px; background-color: #f5f5f5; height: 141px;">
                                    Frete <b style="font-size: 21px;">- {{ $order->freight != 0.00 ? 'R$ ' . number_format($order->freight, 2, ',', '.') : 'grátis' }}</b>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="col-xs-12 complete-infos">
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @include('pagination', ['paginator' => $orders])
    </div>
@endsection
