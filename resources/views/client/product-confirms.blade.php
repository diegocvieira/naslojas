<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container page-admin">
        @if ($confirms->count())
            <h1 class="page-title">Confira o status dos produtos que você solicitou confirmação de estoque</h1>

            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Loja</th>
                        <th>Data da solicitação</th>
                        <th>Confirmação da loja</th>
                        <th>Status</th>
                        <th>Tamanho</th>
                        <th>Informação</th>
                        <th>Ver produto</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($confirms as $confirm)
                        <tr>
                            <td>#{{ $confirm->product->identifier }}</td>
                            <td>{{ $confirm->product->store->name }}</td>
                            <td>{{ date('d/m/y - H:i', strtotime($confirm->created_at)) }}</td>
                            <td>{{ $confirm->confirmed_at ? date('d/m/y - H:i', strtotime($confirm->confirmed_at)) : '-----' }}</td>

                            @if ($confirm->status == 1)
                                <td class="green">Confirmado</td>
                            @elseif ($confirm->status == 0)
                                <td class="red">Não confirmado</td>
                            @else
                                <td>Pendente</td>
                            @endif

                            <td>{{ $confirm->size ?? '-----' }}</td>

                            <td>
                                @if ($confirm->status == 1)
                                    O produto que você deseja ainda está disponível. <br> Passe na loja para conferir.
                                @elseif ($confirm->status == 0)
                                    A loja não tem mais o {{ $confirm->size ? 'tamanho ' . $confirm->size . ' deste' : '' }} produto! Já o retiramos do site e <br> notificamos a loja para que isso não ocorra novamente.
                                @else
                                    Aguarde a confirmação da loja ou tente enviar <br> uma mensagem na página do produto.
                                @endif
                            </td>

                            <td>
                                @if (!$confirm->product->deleted_at && $confirm->product->status == 1)
                                    <a href="{{ route('show-product', $confirm->product->slug) }}" target="_blank" class="link-product" title="Ver produto"></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('pagination', ['paginator' => $confirms])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Você ainda não possui nenhuma <br> confirmação de produto</p>
            </div>
        @endif
    </div>
@endsection
