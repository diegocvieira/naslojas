<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-product-edit {{ $section == 'add' ? 'page-add' : '' }}">
        <div class="top-images">
            <div class="col-xs-6">
                <p>Preencha os dados dos produtos e clique em "{{ $section == 'add' ? 'adicionar ao site' : 'salvar alterações' }}"</p>
            </div>

            <div class="col-xs-6 text-right">
                <button type="button" class="btn-color-variation">VARIAÇÃO DE COR</button>
                <span class="color-variation-tooltip">Selecione as diferentes cores de um mesmo produto e clique em “varaiação de cor” para indicar no site que o produto está à venda em outras cores.</span>

                <button type="button" class="btn-finish">{{ $section == 'add' ? 'ADICIONAR AO SITE' : 'SALVAR ALTERAÇÕES' }}</button>
            </div>

            <span class="advice">* indica item obrigatorio</span>
        </div>

        @if($section == 'edit')
            {!! Form::open(['method' => 'GET', 'route' => 'form-search-admin', 'id' => 'form-search']) !!}
                {!! Form::text('keyword', $keyword ?? null, ['placeholder' => 'Digite aqui o produto que você deseja editar']) !!}
                {!! Form::submit('') !!}
            {!! Form::close() !!}
        @endif

        <div class="forms">
            <?php $variation = 1; ?>
            @foreach($products as $product)
                <?php $variations[] = $product->related; ?>

                @if(!in_array($product->related, $variations))
                    <?php $variation += 1; ?>
                @endif

                {!! Form::model($product, ['method' => 'POST', 'route' => ['save-products', $product->id], 'class' => 'form-edit-product', 'files' => true]) !!}
                    {!! Form::hidden('related') !!}

                    <div class="row">
                        <div class="col-xs-6 images">
                            @foreach($product->images as $image)
                                <div class="image">
                                    {!! Form::checkbox('image_remove[]', $image->image, null, ['id' => 'image_remove_' . $image->id]) !!}
                                    {!! Form::label('image_remove_' . $image->id, ' ', ['class' => 'remove-image']) !!}

                                    <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $image->image) }}" />
                                </div>
                            @endforeach

                            @if($product->images->count() < 6)
                                <div class="container-add-image">
                                    {!! Form::file('image[]', ['id' => 'image_' . ($product->images->count() + 1), 'data-position' => ($product->images->count() + 1)]) !!}
                                    {!! Form::label('image_' . ($product->images->count() + 1), '+', ['class' => 'btn-add-image']) !!}
                                </div>
                            @endif
                        </div>

                        <div class="col-xs-6 options">
                            @if($product->related)
                                <button type="button" class="select-color color-variation" data-variation="{{ $variation }}" title="Clique para remover esta cor da variação">Variação {{ $variation }}</button>
                            @else
                                <button type="button" class="select-color">selecionar</button>
                            @endif

                            <button type="button" class="copy-data">copiar dados</button>
                            <button type="button" class="paste-data">colar dados</button>

                            @if($product->status != 2)
                                @if($product->status == 0)
                                    <button type="button" class="disable-product disabled" data-productid="{{ $product->id }}">ocultado</button>
                                @else
                                    <button type="button" class="disable-product" data-productid="{{ $product->id }}">ocultar</button>
                                @endif
                            @endif

                            <button type="button" class="delete-product" data-url="{{ route('delete-product', $product->id) }}">apagar</button>
                        </div>
                    </div>

                    <div class="row">
                        {!! Form::text('title', null, ['placeholder' => 'Título do produto * (A busca é feita com base nas palavras escritas aqui)', 'class' => 'title']) !!}

                        {!! Form::select('gender', $genders, null, ['title' => 'Gênero*', 'class' => 'selectpicker']) !!}

                        {!! Form::text('old_price', null, ['placeholder' => 'Preço antigo', 'class' => 'mask-money']) !!}
                    </div>

                    <div class="row">
                        {!! Form::textarea('description', null, ['placeholder' => 'Descrição do produto']) !!}

                        {!! Form::select('installment', $installment, null, ['title' => 'Parcelamento', 'class' => 'selectpicker']) !!}

                        {!! Form::text('price', null, ['placeholder' => 'Preço atual*', 'class' => 'mask-money']) !!}

                        {!! Form::text('installment_price', null, ['placeholder' => 'Valor da parcela', 'class' => 'mask-money']) !!}

                        {!! Form::text('discount', null, ['placeholder' => 'Desconto', 'class' => 'mask-number']) !!}
                    </div>

                    <div class="row sizes-container">
                        <button type="button" class="arrow left" data-direction="left"></button>

                        <div class="sizes">
                            @foreach($size_letters as $letter)
                                {!! Form::checkbox('size[]', $letter, in_array($letter, $product->sizes->pluck('size')->all()) ? true : null, ['id' => 'size_' . $letter . '_' . $product->id]) !!}
                                {!! Form::label('size_' . $letter . '_' . $product->id, $letter) !!}
                            @endforeach

                            @foreach($size_numbers as $number)
                                {!! Form::checkbox('size[]', $number, in_array($number, $product->sizes->pluck('size')->all()) ? true : null, ['id' => 'size_' . $number . '_' . $product->id]) !!}
                                {!! Form::label('size_' . $number . '_' . $product->id, $number) !!}
                            @endforeach
                        </div>

                        <button type="button" class="arrow right" data-direction="right"></button>
                    </div>
                {!! Form::close() !!}
            @endforeach
        </div>

        @include('pagination', ['paginator' => $products])
    </div>
@endsection
