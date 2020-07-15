@extends('app', ['body_class' => 'bg-white'])

@section('content')
    @include ('mobile.inc.header')

    <div class="container page-admin">
        @if ($orders->count())
            <h1 class="page-title">Pedidos</h1>
            <p class="page-description">Confira os dados e responda as solicitações de pedidos</p>

            {!! Form::open(['method' => 'GET', 'route' => 'search-store-orders', 'id' => 'form-orders-search']) !!}
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

                                    @if ($product->status == 2)
                                        <div class="btns">
                                            <button data-url="{{ route('confirm-order', $product->id) }}" class="confirm-order" type="button">CONFIRMAR</button>

                                            <button data-url="{{ route('refuse-order', $product->id) }}" class="refuse-order" type="button">CANCELAR</button>

                                            @if (!$product->product->deleted_at && $product->product->status == 1)
                                                <a href="{{ route('show-product', $product->product->slug) }}" class="show-product" target="_blank"></a>
                                            @endif
                                        </div>
                                    @else
                                        <span class="item">
                                            @if ($product->status == 0 || $product->status == 3)
                                                <span class="red"><b>PEDIDO CANCELADO</b></span>
                                            @else
                                                <span class="green"><b>PEDIDO CONFIRMADO</b></span>
                                            @endif
                                        </span>
                                    @endif
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
                                            Mantenha os produtos atualizados para não perder relevância nas buscas
                                        @elseif ($product->status == 1)
                                            Contate o(a) cliente antes de enviar o produto
                                        @else
                                            Certifique-se que o produto foi separado antes de confirmar o pedido.
                                        @endif
                                    </span>
                                </div>
                            @endforeach

                            <div class="group">
                                <span class="item">
                                    <span>Cliente:</span>

                                    {{ $order->client_name }}
                                </span>

                                <span class="item">
                                    <span>CPF:</span>

                                    {{ $order->client_cpf }}
                                </span>

                                <span class="item">
                                    <span>Telefone:</span>

                                    {{ $order->client_phone }}
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

    @include ('mobile.inc.footer')
@endsection
