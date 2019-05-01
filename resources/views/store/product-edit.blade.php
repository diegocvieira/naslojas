<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-product-edit {{ $section == 'add' ? 'page-add' : 'page-edit' }}">
        @if ($products->count() > 0 || $products->count() == 0 && isset($keyword))
            @if ($products->count())
                <div class="top-images">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-6">
                                <p>Altere os dados dos produtos e clique em "{{ $section == 'add' ? 'adicionar ao site' : 'salvar alterações' }}"</p>
                            </div>

                            <div class="col-xs-6 text-right">
                                <span class="btns-color-variation">
                                    <button type="button" class="open-color-variation">VARIAÇÃO DE COR</button>
                                    <span class="color-variation-tooltip">Clique para selecionar as diferentes cores de<br>um mesmo produto e assim indicar no site que<br>o produto está à venda em mais de uma cor.</span>

                                    <button type="button" title="Clique para agrupar as variações de cor selecionadas" class="generate-color-variation" data-url="{{ route('color-variation') }}">AGRUPAR VARIAÇÃO</button>
                                </span>

                                <button type="button" class="btn-finish">{{ $section == 'add' ? 'ADICIONAR AO SITE' : 'SALVAR ALTERAÇÕES' }}</button>

                                <?php /*
                                {!! Form::open(['method' => 'POST', 'route' => 'save-excel', 'files' => true]) !!}
                                    {!! Form::file('file') !!}
                                    {!! Form::submit('file') !!}
                                {!! Form::close() !!}
                                */ ?>
                            </div>
                        </div>
                    </div>
                </div>

                <span class="advice">* indica item obrigatorio</span>
            @endif

            @if ($section == 'edit')
                {!! Form::open(['method' => 'GET', 'route' => 'form-search-admin', 'id' => 'form-search']) !!}
                    {!! Form::text('keyword', $keyword ?? null, ['placeholder' => 'Pesquise aqui o produto que você deseja editar', 'required']) !!}
                    {!! Form::submit('') !!}
                {!! Form::close() !!}
            @endif
        @endif

        @if ($products->count())
            <div class="forms">
                @include('store.list-product-edit')
            </div>
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
    </div>
@endsection
