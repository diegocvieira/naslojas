<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container page-admin">
        <h1 class="page-title">Confira o status dos produtos que você solicitou confirmação de estoque</h1>

        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Data da solicitação</th>
                    <th>Confirmação da loja</th>
                    <th>Status</th>
                    <th>Tamanho</th>
                    <th>Opções</th>
                    <th>Ver produto</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($confirms as $confirm)
                    <tr>
                        <td>#{{ $confirm->product_id }}</td>
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

                        <td><a href="{{ route('show-product', $confirm->product->slug) }}" target="_blank" class="link-product"></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @include('pagination', ['paginator' => $confirms])
    </div>
@endsection
