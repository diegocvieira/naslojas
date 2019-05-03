<?php
    $top_nav_store = true;
    $body_class = 'bg-white';
?>

@extends('base')

@section('content')
    <div class="container page-tutorials">
        <ul class="options">
            <li>
                <a href="{{ route('tutorials', 'adicionar-produtos') }}" class="{{ $type == 'adicionar-produtos' ? 'active' : '' }}">Adicionar produtos</a>
            </li>

            <li>
                <a href="{{ route('tutorials', 'editar-produtos') }}" class="{{ $type == 'editar-produtos' ? 'active' : '' }}">Editar produtos</a>
            </li>

            <li>
                <a href="{{ route('tutorials', 'pedidos') }}" class="{{ $type == 'pedidos' ? 'active' : '' }}">Pedidos</a>
            </li>

            <li>
                <a href="{{ route('tutorials', 'mensagens') }}" class="{{ $type == 'mensagens' ? 'active' : '' }}">Mensagens</a>
            </li>

            <li>
                <a href="{{ route('tutorials', 'configuracoes') }}" class="{{ $type == 'configuracoes' ? 'active' : '' }}">Configurações</a>
            </li>
        </ul>

        <div class="images">
            @if ($type == 'adicionar-produtos')
                @for ($i = 1; $i <= 13; $i++)
                    <img src="{{ asset('images/tutorials-desktop/add-products/' . $i . '.jpg') }}" alt="Passo {{ $i }}" class="img-responsive" />
                @endfor
            @elseif ($type == 'editar-produtos')
                @for ($i = 1; $i <= 7; $i++)
                    <img src="{{ asset('images/tutorials-desktop/edit-products/' . $i . '.jpg') }}" alt="Passo {{ $i }}" class="img-responsive" />
                @endfor
            @elseif ($type == 'pedidos')
                @for ($i = 1; $i <= 4; $i++)
                    <img src="{{ asset('images/tutorials-desktop/orders/' . $i . '.jpg') }}" alt="Passo {{ $i }}" class="img-responsive" />
                @endfor
            @elseif ($type == 'mensagens')
                @for ($i = 1; $i <= 5; $i++)
                    <img src="{{ asset('images/tutorials-desktop/messages/' . $i . '.jpg') }}" alt="Passo {{ $i }}" class="img-responsive" />
                @endfor
            @elseif ($type == 'configuracoes')
                @for ($i = 1; $i <= 8; $i++)
                    <img src="{{ asset('images/tutorials-desktop/configs/' . $i . '.jpg') }}" alt="Passo {{ $i }}" class="img-responsive" />
                @endfor
            @endif
        </div>
    </div>
@endsection
