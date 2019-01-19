<?php
    $top_nav = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container page-admin page-messages">
        @if ($messages->count())
            <h1 class="page-title">Mensagens</h1>
            <p class="page-description">Responda as mensagens enviadas pelos usuários</p>

            <div class="results">
                @foreach ($messages as $message)
                    <div class="result">
                        <div class="images">
                            @foreach ($message->product->images as $image)
                                <img src="{{ asset('uploads/' . $message->product->store_id . '/products/' . $image->image) }}" alt="{{ $message->product->title }}" />
                            @endforeach
                        </div>

                        @if (!$message->product->deleted_at && $message->product->status == 1)
                            <a href="{{ route('show-product', $message->product->slug) }}" target="_blank" class="link-product"></a>
                        @endif

                        <div class="more-details">
                            <div class="info">
                                <span class="info-title">Produto</span>
                                <span class="info-detail">#{{ $message->product->identifier }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Cliente</span>
                                <span class="info-detail">{{ $message->client->name }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Data da mensagem</span>
                                <span class="info-detail">{{ date('d/m/y - H:i', strtotime($message->created_at)) }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Resposta da loja</span>
                                <span class="info-detail answered_date">{{ $message->answered_at ? date('d/m/y - H:i', strtotime($message->answered_at)) : '-----' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Status</span>
                                <span class="info-detail status {{ $message->status ? 'green' : '' }}">{{ $message->status ? 'Respondido' : 'Pendente' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Opções</span>
                                <span class="info-detail">
                                    <a href="#" class="show-message" data-id="{{ $message->id }}" data-clientmessage="{{ $message->question }}" data-clientname="{{ $message->client->name }}" data-storemessage="{{ $message->response }}" data-storename="{{ $message->product->store->name }}">{{ $message->status ? 'VISUALIZAR RESPOSTA' : 'RESPONDER USUÁRIO' }}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @include('mobile.pagination', ['paginator' => $messages])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Você ainda não possui <br> nenhuma mensagem</p>
            </div>
        @endif
    </div>
@endsection
