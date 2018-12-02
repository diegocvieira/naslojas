<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container page-admin page-messages">
        <h1 class="page-title">Confira as mensagens trocadas com as lojas</h1>

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
                        <td>{{ $message->answered_at ? date('d/m/y - H:i', strtotime($message->answered_at)) : '-----' }}</td>
                        <td class="{{ $message->status ? 'green' : '' }}">{{ $message->status ? 'Respondido' : 'Pendente' }}</td>
                        <td><a href="#" class="show-message" data-clientmessage="{{ $message->question }}" data-clientname="{{ $message->client->name }}" data-storemessage="{{ $message->response }}" data-storename="{{ $message->product->store->name }}">{{ $message->status ? 'Visualizar resposta' : 'Visualizar mensagem' }}</a></td>
                        <td><a href="{{ route('show-product', $message->product->slug) }}" target="_blank" class="link-product"></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @include('pagination', ['paginator' => $messages])
    </div>
@endsection
