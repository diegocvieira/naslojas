<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-admin">
        @if ($reserves->count())
            <h1 class="page-title">Confirme as reservas solicitadas pelos usuários</h1>

            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Data da reserva</th>
                        <th>Confirmação da loja</th>
                        <th>Reservar até</th>
                        <th>Status</th>
                        <th>Tamanho</th>
                        <th>Opções</th>
                        <th>Ver produto</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($reserves as $reserve)
                        <tr>
                            <td>#{{ $reserve->product->identifier }}</td>
                            <td>{{ date('d/m/y - H:i', strtotime($reserve->created_at)) }}</td>
                            <td class="confirmed_date">{{ $reserve->confirmed_at ? date('d/m/y - H:i', strtotime($reserve->confirmed_at)) : '-----' }}</td>
                            <td class="reserved_until">{{ $reserve->reserved_until ? date('d/m/y - H:i', strtotime($reserve->reserved_until)) : '-----' }}</td>

                            @if ($reserve->status == 1)
                                <td class="status green">Confirmado</td>
                            @elseif ($reserve->status == 0)
                                <td class="status red">Recusado</td>
                            @else
                                <td class="status">Pendente</td>
                            @endif

                            <td>{{ $reserve->size ?? '-----' }}</td>

                            <td class="btn-status">
                                @if ($reserve->status == 2)
                                    <a href="{{ route('product-confirm-reserve', $reserve->id) }}" class="change-reserve-status">Confirmar reserva</a>
                                    <a href="{{ route('product-refuse-reserve', $reserve->id) }}" class="change-reserve-status">Recusar reserva</a>
                                @else
                                    -----
                                @endif
                            </td>

                            <td>
                                @if (!$reserve->product->deleted_at && $reserve->product->status == 1)
                                    <a href="{{ route('show-product', $reserve->product->slug) }}" target="_blank" class="link-product" title="Ver produto"></a>
                                @else
                                    -----
                                @endif
                            </td>
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
