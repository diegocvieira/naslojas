<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-product-edit {{ $section == 'add' ? 'page-add' : '' }}">
        @if ($products->count() > 0 || $products->count() == 0 && isset($keyword))
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
        @endif

        @if ($products->count())
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
                                @foreach($product->images as $key => $image)
                                    <div class="image loaded-image">
                                        {!! Form::checkbox('image_remove[]', $image->image, false, ['id' => 'image_remove_' . $product->id . '_' . $key, 'autocomplete' => 'off']) !!}
                                        {!! Form::label('image_remove_' . $product->id . '_' . $key, ' ', ['class' => 'remove-image']) !!}

                                        {!! Form::file('image[]', ['id' => 'image_' . $product->id . '_' . $key, 'data-position' => $key, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}
                                        {!! Form::label('image_' . $product->id . '_' . $key, ' ', ['class' => 'btn-add-image']) !!}

                                        <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $image->image) }}" />
                                    </div>
                                @endforeach

                                @for($i = ($product->images->count() + 1); $i <= 5; $i++)
                                    <div class="image no-image">
                                        {!! Form::file('image[]', ['id' => 'image_' . $product->id . '_' . $i, 'data-position' => $i, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}
                                        {!! Form::label('image_' . $product->id . '_' . $i, ' ', ['class' => 'btn-add-image']) !!}

                                        <img src="#" />
                                    </div>
                                @endfor
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
                            {!! Form::text('title', null, ['placeholder' => 'Título do produto * (A busca é feita com base nas palavras escritas aqui)', 'class' => 'title', 'title' => 'Título do produto']) !!}

                            {!! Form::select('gender', $genders, null, ['title' => 'Gênero*', 'class' => 'selectpicker']) !!}

                            {!! Form::text('old_price', null, ['placeholder' => 'Preço antigo', 'class' => 'mask-money', 'title' => 'Preço antigo']) !!}
                        </div>

                        <div class="row">
                            {!! Form::textarea('description', null, ['placeholder' => 'Descrição do produto', 'title' => 'Descrição do produto']) !!}

                            {!! Form::select('installment', $installment, null, ['title' => 'Parcelamento', 'class' => 'selectpicker']) !!}

                            {!! Form::text('price', null, ['placeholder' => 'Preço atual*', 'class' => 'mask-money', 'title' => 'Preço atual']) !!}

                            {!! Form::text('installment_price', null, ['placeholder' => 'Valor da parcela', 'class' => 'mask-money', 'title' => 'Valor da parcela']) !!}

                            {!! Form::text('discount', null, ['placeholder' => 'Desconto', 'class' => 'mask-number', 'title' => 'Desconto']) !!}
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
