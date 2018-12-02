<?php
    $top_nav = true;
?>

@extends('base')

@section('content')
    <div class="container page-admin">
        @if ($reserves->count())
            <h1 class="page-title">Confira o status dos produtos que você solicitou reserva</h1>

            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Data da reserva</th>
                        <th>Confirmação da loja</th>
                        <th>Reservar até</th>
                        <th>Status</th>
                        <th>Tamanho</th>
                        <th>Informação relevante</th>
                        <th>Ver produto</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reserves as $reserve)
                        <tr>
                            <td>#{{ $reserve->product_id }}</td>
                            <td>{{ date('d/m/y - H:i', strtotime($reserve->created_at)) }}</td>
                            <td>{{ $reserve->confirmed_at ? date('d/m/y - H:i', strtotime($reserve->confirmed_at)) : '-----' }}</td>
                            <td>{{ $reserve->reserved_until ? date('d/m/y - H:i', strtotime($reserve->reserved_until)) : '-----' }}</td>

                            @if ($reserve->status == 1)
                                <td class="green">Confirmado</td>
                            @elseif ($reserve->status == 0)
                                <td class="red">Não confirmado</td>
                            @else
                                <td>Pendente</td>
                            @endif

                            <td>{{ $reserve->size ?? '-----' }}</td>

                            <td>
                                @if ($reserve->status == 1)
                                    O produto ficará reservado para você até a data indicada. <br> Passe na loja e informe o seu nome para realizar a compra.
                                @elseif ($reserve->status == 0)
                                    A loja não tem mais o {{ $reserve->size ? 'tamanho ' . $reserve->size . ' deste' : '' }} produto! Já o retiramos do site e <br> notificamos a loja para que isso não ocorra novamente.
                                @else
                                    Aguarde a confirmação da loja ou tente enviar <br> uma mensagem na página do produto.
                                @endif
                            </td>

                            <td><a href="{{ route('show-product', $reserve->product->slug) }}" target="_blank" class="link-product"></a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @include('pagination', ['paginator' => $reserves])
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                <p>Você ainda não possui nenhuma <br> reserva de produto</p>
            </div>
        @endif
    </div>
@endsection
