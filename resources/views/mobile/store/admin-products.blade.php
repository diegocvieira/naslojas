<?php
    $top_nav = true;
    $body_class = 'page-admin-products';
    $admin_search = true;
?>

@extends('mobile.base')

@section('content')
    <header class="product-manager">
        <button type='button' class='btn-back'></button>

        <nav class="nav navbar-nav nav-menu">
            <ul>
                <li>
                    <a href="#" class="open-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('images/icon-menu.png') }}" alt="Menu" />
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" data-type="show-product">Ver produto</a>
                        </li>

                        <li>
                            <a href="{{ route('product-enable') }}" data-type="product-enable">Ativar</a>
                        </li>

                        <li>
                            <a href="{{ route('product-disable') }}" data-type="product-disable">Desativar</a>
                        </li>

                        <li>
                            <a href="{{ route('color-variation') }}" data-type="variation-generate">Agrupar variação</a>
                        </li>

                        <li>
                            <a href="{{ route('color-variation') }}" data-type="variation-remove">Desagrupar variação</a>
                        </li>

                        <li>
                            <a href="{{ route('product-delete') }}" data-type="delete">Excluir</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </header>

    <div class="container">
        @if ($products->count())
            {!! Form::open(['method' => 'POST', 'id' => 'form-product-manager']) !!}
                @include('mobile.store.list-admin-products')
            {!! Form::close() !!}
        @else
            <div class="no-results">
                <img src="{{ asset('images/icon-box.png') }}" />

                @if ($products->count() == 0 && isset($keyword))
                    <p>Não encontramos resultados. <br> Tente palavras-chave diferentes</p>
                @else
                    <p>Adicione produtos para poder editá-los aqui</p>
                @endif
            </div>
        @endif

        <?php /* <a href="{{ route('get-create-edit-product') }}" class="btn-create-edit-product">+</a>*/ ?>
    </div>
@endsection
