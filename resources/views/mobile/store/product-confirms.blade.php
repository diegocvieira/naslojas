<?php
    $top_nav = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container page-admin page-confirm">
        @if ($confirms->count())
            <h1 class="page-title">Confirmações</h1>
            <p class="page-description">Confira os pedidos de confirmação solicitados pelos usuários</p>

            <div class="results">
                @foreach ($confirms as $confirm)
                    <div class="result">
                        <div class="images">
                            @foreach ($confirm->product->images as $image)
                                <img src="{{ asset('uploads/' . $confirm->product->store_id . '/products/' . $image->image) }}" alt="{{ $confirm->product->title }}" />
                            @endforeach
                        </div>

                        @if (!$confirm->product->deleted_at && $confirm->product->status == 1)
                            <a href="{{ route('show-product', $confirm->product->slug) }}" target="_blank" class="link-product"></a>
                        @endif

                        <div class="more-details">
                            <div class="info">
                                <span class="info-title">Produto</span>
                                <span class="info-detail">#{{ $confirm->product_id }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Data da solicitação</span>
                                <span class="info-detail">{{ date('d/m/y - H:i', strtotime($confirm->created_at)) }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Confirmação da loja</span>
                                <span class="info-detail confirmed_date">{{ $confirm->confirmed_at ? date('d/m/y - H:i', strtotime($confirm->confirmed_at)) : '-----' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Status</span>
                                @if ($confirm->status == 1)
                                    <span class="info-detail green">Confirmado</span>
                                @elseif ($confirm->status == 0)
                                    <span class="info-detail red">Não confirmado</span>
                                @else
                                    <span class="info-detail status">Pendente</span>
                                @endif
                            </div>

                            <div class="info">
                                <span class="info-title">Tamanho</span>
                                <span class="info-detail">{{ $confirm->size ?? '-----' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Opções</span>
                                <span class="info-detail btn-status">
                                    @if ($confirm->status == 2)
                                        <a href="{{ route('product-confirm-confirm', $confirm->id) }}" class="change-confirm-status">Produto disponível</a>
                                        <a href="{{ route('product-refuse-confirm', $confirm->id) }}" class="change-confirm-status">Produto indisponível</a>
                                    @else
                                        -----
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('mobile.pagination', ['paginator' => $confirms])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Você ainda não possui nenhuma <br> confirmação de produto</p>
            </div>
        @endif
    </div>
@endsection
