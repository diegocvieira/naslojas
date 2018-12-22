<?php
    $top_nav = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container page-admin page-reserve">
        @if ($reserves->count())
            <h1 class="page-title">Reservas</h1>
            <p class="page-description">Confira o status dos produtos que você solicitou reserva</p>

            <div class="results">
                @foreach ($reserves as $reserve)
                    <div class="result">
                        <div class="images">
                            @foreach ($reserve->product->images as $image)
                                <img src="{{ asset('uploads/' . $reserve->product->store_id . '/products/' . $image->image) }}" alt="{{ $reserve->product->title }}" />
                            @endforeach
                        </div>

                        @if (!$reserve->product->deleted_at && $reserve->product->status == 1)
                            <a href="{{ route('show-product', $reserve->product->slug) }}" target="_blank" class="link-product"></a>
                        @endif

                        <div class="more-details">
                            <div class="info">
                                <span class="info-title">Produto</span>
                                <span class="info-detail">#{{ $reserve->product->identifier }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Data da reserva</span>
                                <span class="info-detail">{{ date('d/m/y - H:i', strtotime($reserve->created_at)) }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Confirmação da loja</span>
                                <span class="info-detail">{{ $reserve->confirmed_at ? date('d/m/y - H:i', strtotime($reserve->confirmed_at)) : '-----' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Reservar até</span>
                                <span class="info-detail">{{ $reserve->reserved_until ? date('d/m/y - H:i', strtotime($reserve->reserved_until)) : '-----' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Status</span>
                                @if ($reserve->status == 1)
                                    <span class="info-detail green">Confirmado</span>
                                @elseif ($reserve->status == 0)
                                    <span class="info-detail red">Não confirmado</span>
                                @else
                                    <span class="info-detail">Pendente</span>
                                @endif
                            </div>

                            <div class="info">
                                <span class="info-title">Tamanho</span>
                                <span class="info-detail">{{ $reserve->size ?? '-----' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Informação</span>
                                <span class="info-detail">
                                    @if ($reserve->status == 1)
                                        O produto ficará reservado para você até a data indicada. <br> Passe na loja e informe o seu nome para realizar a compra.
                                    @elseif ($reserve->status == 0)
                                        A loja não tem mais o {{ $reserve->size ? 'tamanho ' . $reserve->size . ' deste' : '' }} produto! Já o retiramos do site e <br> notificamos a loja para que isso não ocorra novamente.
                                    @else
                                        Aguarde a confirmação da loja ou tente enviar <br> uma mensagem na página do produto.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('mobile.pagination', ['paginator' => $reserves])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Você ainda não possui nenhuma <br> confirmação de produto</p>
            </div>
        @endif
    </div>
@endsection
