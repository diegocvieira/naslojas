<?php
    $top_nav = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container page-admin page-messages">
        @if ($messages->count())
            <h1 class="page-title">Mensagens</h1>
            <p class="page-description">Confira as mensagens trocadas com as lojas</p>

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
                                <span class="info-title">Loja</span>
                                <span class="info-detail">{{ $message->product->store->name }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Data da mensagem</span>
                                <span class="info-detail">{{ date('d/m/y - H:i', strtotime($message->created_at)) }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Resposta da loja</span>
                                <span class="info-detail">{{ $message->answered_at ? date('d/m/y - H:i', strtotime($message->answered_at)) : '-----' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Status</span>
                                <span class="info-detail {{ $message->status ? 'green' : '' }}">{{ $message->status ? 'Respondido' : 'Pendente' }}</span>
                            </div>

                            <div class="info">
                                <span class="info-title">Op????es</span>
                                <span class="info-detail">
                                    <a href="#" class="show-message" data-clientmessage="{{ $message->question }}" data-clientname="{{ $message->client->name }}" data-storemessage="{{ $message->response }}" data-storename="{{ $message->product->store->name }}">{{ $message->status ? 'Visualizar resposta' : 'Visualizar mensagem' }}</a>
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

                <p>Voc?? ainda n??o possui <br> nenhuma mensagem</p>
            </div>
        @endif
    </div>
@endsection
