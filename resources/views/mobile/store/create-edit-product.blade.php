@extends('mobile.base')

@section('content')
    <div class="container page-create-edit-product">
        @if (isset($product))
            {!! Form::model($product, ['method' => 'POST', 'route' => ['save-product-individual', $product->id], 'id' => 'form-create-edit-product', 'files' => true]) !!}
        @else
            {!! Form::open(['method' => 'POST', 'route' => 'save-product-individual', 'id' => 'form-create-edit-product', 'files' => true]) !!}
        @endif

            {!! Form::hidden('related', null, ['class' => 'json']) !!}

            <div class='header'>
                <a href="javascript: history.go(-1);" class="btn-back"></a>

                {!! Form::submit('SALVAR') !!}

                @isset ($product)
                    <button type="button" class="btn-option" data-url="{{ route('product-delete') }}" data-productid="{{ $product->id }}">APAGAR</button>

                    <button type="button" class="btn-option enable {{ $product->status == 1 ? 'hidden' : '' }}" data-url="{{ route('product-enable') }}" data-productid="{{ $product->id }}">MOSTRAR</button>
                    <button type="button" class="btn-option disable {{ $product->status == 0 ? 'hidden' : '' }}" data-url="{{ route('product-disable') }}" data-productid="{{ $product->id }}">OCULTAR</button>
                @endisset
            </div>

            <span class="advice">* indica item obrigatório</span>

            <div class="section images">
                <div class="color-variation">
                    @isset ($product)
                        @foreach ($product->images as $key => $image)
                            <div class="image loaded-image">
                                <span class="remove-image"></span>
                                {!! Form::checkbox('image_remove[]', $image->image, false, ['autocomplete' => 'off']) !!}

                                <span class="btn-add-image"></span>
                                {!! Form::file('image[]', ['data-position' => $key, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}

                                <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $image->image) }}" />
                            </div>
                        @endforeach
                    @endisset

                    @for ($i = isset($product) ? ($product->images->count() + 1) : 1; $i <= 5; $i++)
                        <div class="image no-image">
                            <span class="btn-add-image"></span>
                            {!! Form::file('image[]', ['data-position' => $i, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}

                            <img src="#" />
                        </div>
                    @endfor
                </div>

                <button type="button" class="add-color-variation">ADICIONAR VARIAÇÃO DE COR</button>
            </div>

            <div class="section">
                {!! Form::text('title', null, ['placeholder' => 'Título do produto *', 'class' => 'json', 'title' => 'Título do produto']) !!}

                {!! Form::textarea('description', null, ['placeholder' => 'Descrição do produto', 'title' => 'Descrição do produto', 'class' => 'json']) !!}
            </div>

            <div class="section">
                {!! Form::select('gender', $genders, null, ['title' => 'Gênero *', 'class' => 'selectpicker json']) !!}

                {!! Form::text('installment', null, ['placeholder' => 'Parcelamento', 'class' => 'mask-x']) !!}

                {!! Form::text('installment_price', null, ['placeholder' => 'Valor da parcela', 'class' => 'mask-money json', 'title' => 'Valor da parcela']) !!}
            </div>

            <div class="section">
                {!! Form::text('old_price', null, ['placeholder' => 'Preço antigo', 'class' => 'mask-money json', 'title' => 'Preço antigo']) !!}

                {!! Form::text('price', null, ['placeholder' => 'Preço atual *', 'class' => 'mask-money json', 'title' => 'Preço atual']) !!}

                {!! Form::text('discount', $product->old_price ? str_replace('-', '', round(($product->price / $product->old_price - 1) * 100)) : null, ['placeholder' => 'Desconto', 'class' => 'mask-percent json', 'title' => 'Desconto']) !!}
            </div>

            <div class="section sizes">
                @foreach($size_letters as $letter)
                    {!! Form::checkbox('size[]', $letter, (isset($product) && in_array($letter, $product->sizes->pluck('size')->all())) ? true : null, ['id' => 'size_' . $letter]) !!}
                    {!! Form::label('size_' . $letter, $letter) !!}
                @endforeach

                @foreach($size_numbers as $number)
                    {!! Form::checkbox('size[]', $number, (isset($product) && in_array($number, $product->sizes->pluck('size')->all())) ? true : null, ['id' => 'size_' . $number]) !!}
                    {!! Form::label('size_' . $number, $number) !!}
                @endforeach
            </div>

            @isset ($product)
                <a href="{{ route('show-product', $product->slug) }}" class="btn-show-product">VER PRODUTO NO SITE</a>
            @endisset
        {!! Form::close() !!}
    </div>
@endsection
