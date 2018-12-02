<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-admin">
        <h1 class="page-title">Confira os pedidos de confirmação solicitados pelos usuários</h1>

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
                        <td class="confirmed_date">{{ $confirm->confirmed_at ? date('d/m/y - H:i', strtotime($confirm->confirmed_at)) : '-----' }}</td>

                        @if ($confirm->status == 1)
                            <td class="status green">Confirmado</td>
                        @elseif ($confirm->status == 0)
                            <td class="status red">Recusado</td>
                        @else
                            <td class="status">Pendente</td>
                        @endif

                        <td>{{ $confirm->size ?? '-----' }}</td>

                        <td class="btn-status">
                            @if ($confirm->status == 2)
                                <a href="{{ route('product-confirm-confirm', $confirm->id) }}" class="change-confirm-status">Produto disponível</a>
                                <a href="{{ route('product-refuse-confirm', $confirm->id) }}" class="change-confirm-status">Produto indisponível</a>
                            @else
                                -----
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
