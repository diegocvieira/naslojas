<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-admin page-messages">
        <h1 class="page-title">Responda as mensagens enviadas pelos usuários</h1>

        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Data da mensagem</th>
                    <th>Resposta da loja</th>
                    <th>Status</th>
                    <th>Opções</th>
                    <th>Ver produto</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($messages as $message)
                    <tr>
                        <td>#{{ $message->product_id }}</td>
                        <td>{{ date('d/m/y - H:i', strtotime($message->created_at)) }}</td>
                        <td class="answered_date">{{ $message->answered_at ? date('d/m/y - H:i', strtotime($message->answered_at)) : '-----' }}</td>
                        <td class="status {{ $message->status ? 'green' : '' }}">{{ $message->status ? 'Respondido' : 'Pendente' }}</td>
                        <td><a href="#" class="show-message" data-id="{{ $message->id }}" data-clientmessage="{{ $message->question }}" data-clientname="{{ $message->client->name }}" data-storemessage="{{ $message->response }}" data-storename="{{ $message->product->store->name }}">{{ $message->status ? 'Visualizar resposta' : 'Responder usuário' }}</a></td>
                        <td><a href="{{ route('show-product', $message->product->slug) }}" target="_blank" class="link-product"></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @include('pagination', ['paginator' => $messages])
    </div>
@endsection
