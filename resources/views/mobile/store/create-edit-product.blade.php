@php
    $top_nav = true;
    $back = true;
@endphp

@extends('mobile.base')

@section('content')
    <div class="container page-create-edit-product">
        <?php /* @if (isset($product))
            {!! Form::model($product, ['method' => 'POST', 'route' => ['save-products', $product->id], 'id' => 'form-create-edit-product', 'files' => true]) !!}
                {!! Form::hidden('free_freight_price', $product->store->free_freight_price) !!}
                {!! Form::hidden('product_id', $product->id) !!}
        @else
            {!! Form::open(['method' => 'POST', 'route' => 'save-products', 'id' => 'form-create-edit-product', 'files' => true]) !!}
        @endif

            <header>
                <a href="{{ route('edit-products') }}" class="btn-back"></a>

                <nav class="nav navbar-nav nav-menu">
                    <ul>
                        <li>
                            <a href="#" class="open-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <img src="{{ asset('images/icon-menu.png') }}" alt="Menu" />
                            </a>

                            <ul class="dropdown-menu">
                                @isset ($product)
                                    @if ($product->status == 1)
                                        <li>
                                            <a href="{{ route('show-product', $product->slug) }}" target="_blank">Ver produto</a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('product-free-freight') }}" data-type="free-freight" class="option free-freight {{ $product->free_freight == 1 ? 'hidden' : '' }}" data-productid="{{ $product->id }}">Ativar frete grátis</button>

                                        <a href="{{ route('product-free-freight') }}" data-type="free-freight" class="option free-freight free-freight-selected {{ $product->free_freight == 0 ? 'hidden' : '' }}" data-productid="{{ $product->id }}">Desativar frete grátis</a>
                                    </li>

                                    <li>
                                        <a href="#" data-type="copy-data" class="option">Copiar dados</a>
                                    </li>
                                @endisset

                                <li>
                                    <a href="#" data-type="paste-data" class="option">Colar dados</a>
                                </li>

                                @isset ($product)
                                    <li class="{{ $product->status == 1 ? 'hidden' : '' }}">
                                        <a href="{{ route('product-enable') }}" data-type="product-enable" class="option" data-productid="{{ $product->id }}">Ativar</a>
                                    </li>

                                    <li class="{{ $product->status == 0 ? 'hidden' : '' }}">
                                        <a href="{{ route('product-disable') }}" data-type="product-disable" class="option" data-productid="{{ $product->id }}">Desativar</a>
                                    </li>

                                    <li>
                                        <a href="{{ route('product-delete') }}" data-type="delete" class="option" data-productid="{{ $product->id }}">Excluir</a>
                                    </li>
                                @endisset
                            </ul>
                        </li>
                    </ul>
                </nav>

                {!! Form::submit('SALVAR') !!}
            </header>

            <span class="advice">* indica item obrigatório</span>

            <div class="section images">
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

                @for ($i = isset($product) ? ($product->images->count()) : 0; $i <= 4; $i++)
                    <div class="image no-image">
                        <span class="btn-add-image"></span>
                        {!! Form::file('image[]', ['data-position' => $i, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}

                        <img src="#" />
                    </div>
                @endfor
            </div>

            <div class="section">
                <div class="form-group">
                    {!! Form::text('title', null, ['placeholder' => ' ', 'class' => 'field']) !!}
                    {!! Form::label('', 'Título do produto *') !!}
                </div>

                <div class="form-group description">
                    {!! Form::textarea('description', null, ['placeholder' => ' ', 'class' => 'field']) !!}
                    {!! Form::label('', 'Descrição do produto') !!}
                </div>
            </div>

            <div class="section">
                <div class="form-group">
                    {!! Form::text('price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                    {!! Form::label('', 'Preço atual *') !!}
                </div>

                <div class="form-group">
                    {!! Form::text('old_price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                    {!! Form::label('', 'Preço anterior') !!}
                </div>
            </div>

            <div class="section">
                <div class="form-group gender">
                    {!! Form::select('gender', $genders, null, ['title' => 'Gênero *', 'class' => 'selectpicker field']) !!}
                    {!! Form::label('', 'Gênero *', ['style' => (isset($product) && $product->gender) ? 'display: block;' : '']) !!}
                </div>
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
        {!! Form::close() !!} */ ?>

        <div class="mobile-disabled">
            <h1>Para realizar o cadastro de produtos acesse de um computador ou notebook</h1>
        </div>
    </div>
@endsection
