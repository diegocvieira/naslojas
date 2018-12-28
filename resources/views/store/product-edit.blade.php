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
                                    <button type="button" class="generate-color-variation">AGRUPAR VARIAÇÃO</button>
                                </span>
                                <span class="color-variation-tooltip">Clique para selecionar as diferentes cores de um mesmo produto e assim indicar no site que o produto está à venda em mais de uma cor.</span>

                                <button type="button" class="btn-finish">{{ $section == 'add' ? 'ADICIONAR AO SITE' : 'SALVAR ALTERAÇÕES' }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <span class="advice">* indica item obrigatorio</span>
            @endif

            @if ($section == 'edit')
                {!! Form::open(['method' => 'GET', 'route' => 'form-search-admin', 'id' => 'form-search']) !!}
                    {!! Form::text('keyword', $keyword ?? null, ['placeholder' => 'Digite aqui o produto que você deseja editar']) !!}
                    {!! Form::submit('') !!}
                {!! Form::close() !!}
            @endif
        @endif

        @if ($products->count())
            <div class="forms">
                <?php $variations = []; ?>

                @foreach ($products as $product)
                    @if ($product->related && !in_array($product->related, $variations))
                        <?php array_push($variations, $product->related); ?>
                    @endif

                    <?php $variation = (array_search($product->related, $variations) + 1); ?>

                    {!! Form::model($product, ['method' => 'POST', 'route' => ['save-products', $product->id], 'class' => "form-edit-product " . ($product->status == '0' ? 'product-disabled' : ''), 'files' => true]) !!}
                        {!! Form::hidden('related', null, ['class' => 'field']) !!}
                        {!! Form::hidden('product_id', $product->id, ['class' => 'field']) !!}

                        <div class="row">
                            <div class="col-xs-6 images">
                                @foreach ($product->images as $key => $image)
                                    <div class="image loaded-image">
                                        {!! Form::checkbox('image_remove[]', $image->image, false, ['id' => 'image_remove_' . $product->id . '_' . $key, 'autocomplete' => 'off']) !!}
                                        {!! Form::label('image_remove_' . $product->id . '_' . $key, ' ', ['class' => 'remove-image']) !!}

                                        {!! Form::file('image[]', ['id' => 'image_' . $product->id . '_' . $key, 'data-position' => $key, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}
                                        {!! Form::label('image_' . $product->id . '_' . $key, ' ', ['class' => 'btn-add-image']) !!}

                                        <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $image->image) }}" />
                                    </div>
                                @endforeach

                                @for ($i = ($product->images->count() + 1); $i <= 5; $i++)
                                    <div class="image no-image">
                                        {!! Form::file('image[]', ['id' => 'image_' . $product->id . '_' . $i, 'data-position' => $i, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}
                                        {!! Form::label('image_' . $product->id . '_' . $i, ' ', ['class' => 'btn-add-image']) !!}

                                        <img src="#" />
                                    </div>
                                @endfor
                            </div>

                            <div class="col-xs-6 options">
                                <button type="button" class="delete-product" data-productid="{{ $product->id }}" data-url="{{ route('product-delete') }}" title="Excluir produto"></button>

                                @if ($product->status != 2)
                                    <button type="button" class="disable-product disabled {{ $product->status == 1 ? 'hidden' : '' }}" data-productid="{{ $product->id }}" data-url="{{ route('product-enable') }}" title="Ativar produto"></button>
                                    <button type="button" class="enable-product {{ $product->status == 0 ? 'hidden' : '' }}" data-productid="{{ $product->id }}" data-url="{{ route('product-disable') }}" title="Desativar produto"></button>
                                @endif

                                <button type="button" class="paste-data" title="Colar dados"></button>
                                <button type="button" class="copy-data" title="Copiar dados"></button>

                                <button type="button" class="disable-reserve {{ $product->reserve == 1 ? 'hidden' : '' }}" data-productid="{{ $product->id }}" data-url="{{ route('reserve-enable') }}" title="Habilitar reserva"></button>
                                <button type="button" class="enable-reserve disabled {{ $product->reserve == 0 ? 'hidden' : '' }}" data-productid="{{ $product->id }}" data-url="{{ route('reserve-disable') }}" title="Desabilitar reserva"></button>

                                @if ($product->status == 1)
                                    <a href="{{ route('show-product', $product->slug) }}" target="_blank" title="Ver produto"></a>
                                @endif

                                @if ($product->related)
                                    <button type="button" class="select-color color-variation" data-variation="{{ $variation }}" title="Clique para remover esta cor da variação">{{ $variation }}</button>
                                @else
                                    <button type="button" class="select-color" title="Variação de cor"></button>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group title">
                                {!! Form::text('title', null, ['class' => 'field', 'placeholder' => ' ']) !!}
                                {!! Form::label('', 'Título do produto * (A busca é feita com base nas palavras escritas aqui)') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                                {!! Form::label('', 'Preço atual *') !!}
                            </div>

                            <div class="form-group gender">
                                {!! Form::select('gender', $genders, null, ['title' => 'Gênero *', 'class' => 'selectpicker field']) !!}
                                {!! Form::label('', 'Gênero *', ['style' => (isset($product) && $product->gender) ? 'display: block;' : '']) !!}
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group description">
                                {!! Form::textarea('description', null, ['placeholder' => ' ', 'class' => 'field']) !!}
                                {!! Form::label('', 'Descrição do produto') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('old_price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                                {!! Form::label('', 'Preço anterior') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('installment', null, ['placeholder' => ' ', 'class' => 'mask-x field']) !!}
                                {!! Form::label('', 'Parcelamento') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('reserve_discount', null, ['placeholder' => ' ', 'class' => 'mask-percent field']) !!}
                                {!! Form::label('', 'Desconto na reserva') !!}
                            </div>

                            <div class="form-group">
                                {!! Form::text('installment_price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                                {!! Form::label('', 'Valor da parcela') !!}
                            </div>
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
