@php
    $top_nav = true;
    $back = true;
@endphp

@extends('mobile.base')

@section('content')
    <div class="container page-create-edit-product">
        @if (isset($product))
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
                                    @if ($product->status == 1 && $product->store->status == 1)
                                        <li>
                                            <a href="{{ route('show-product', $product->slug) }}" target="_blank">Ver produto</a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('product-free-freight') }}" data-type="free-freight" class="option free-freight {{ $product->free_freight == 1 ? 'hidden' : '' }}" data-productid="{{ $product->id }}">Ativar frete grátis</button>

                                        <a href="{{ route('product-free-freight') }}" data-type="free-freight" class="option free-freight free-freight-selected {{ $product->free_freight == 0 ? 'hidden' : '' }}" data-productid="{{ $product->id }}">Desativar frete grátis</a>
                                    </li>

                                    @if ($product->status == 1 && $product->store->status == 1 && $product->images->count())
                                        <li>
                                            <a href="{{ route('show-product', $product->slug) }}" data-type="link-share" class="option link-share" data-image="{{ asset('uploads/' . $product->store_id . '/products/' . _originalImage($product->images->first()->image)) }}" data-freight="{{ $product->free_freight ? 'grátis' : 'R$5,00' }}" data-store="{{ $product->store->name }}" data-title="{{ $product->title }}">Compartilhar</a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="#" data-type="copy-data" class="option">Copiar dados</a>
                                    </li>
                                @endisset

                                <li>
                                    <a href="#" data-type="paste-data" class="option">Colar dados</a>
                                </li>

                                @isset ($product)
                                    @if ($product->status != 2)
                                        <li class="{{ $product->status == 1 ? 'hidden' : '' }}">
                                            <a href="{{ route('product-enable') }}" data-type="product-enable" class="option" data-productid="{{ $product->id }}">Ativar</a>
                                        </li>

                                        <li class="{{ $product->status == 0 ? 'hidden' : '' }}">
                                            <a href="{{ route('product-disable') }}" data-type="product-disable" class="option" data-productid="{{ $product->id }}">Desativar</a>
                                        </li>
                                    @endif

                                    <li>
                                        <a href="{{ route('product-delete') }}" data-type="delete" class="option" data-productid="{{ $product->id }}">Excluir</a>
                                    </li>
                                @endisset
                            </ul>
                        </li>
                    </ul>
                </nav>

                <button type="button" class="btn-finish" data-status="1">ENVIAR AO SITE</button>
                <button type="button" class="btn-finish" data-status="">SALVAR</button>
            </header>

            @if (isset($product))
                <div class="top-options">
                    @if ($product->offtime)
                        <button type="button" class="btn-offtime offtime-selected">EM OFERTA</button>
                    @else
                        <button type="button" class="btn-offtime">CRIAR OFERTA</button>
                    @endif

                    <div class="modal-offtime">
                        <div class="top">O preço do produto voltará ao normal assim que o período em oferta acabar.</div>

                        <div class="body">
                            <span class="price-container">PREÇO EM OFERTA - <b>R$<span class="price">{{ number_format($product->price, 2, ',', '.') }}</span></b></span>

                            <div class="off-container">
                                {!! Form::text('off', $product->offtime ? $product->offtime->off : null, ['placeholder' => 'Desconto', 'class' => 'mask-percent']) !!}

                                <button type="button" class="apply-off">APLICAR</button>
                            </div>

                            <div class="time-container">
                                <span>Válido por</span>

                                {!! Form::radio('time', '24', ($product->offtime && $product->offtime->time == '24') ? true : false, ['id' => '24h']) !!}
                                {!! Form::label('24h', '24h') !!}

                                {!! Form::radio('time', '48', ($product->offtime && $product->offtime->time == '48') ? true : false, ['id' => '48h']) !!}
                                {!! Form::label('48h', '48h') !!}

                                {!! Form::radio('time', '72', ($product->offtime && $product->offtime->time == '72') ? true : false, ['id' => '72h']) !!}
                                {!! Form::label('72h', '72h') !!}
                            </div>
                        </div>

                        <div class="bottom">
                            <button type="button" class="save-off" data-route="{{ route('offtime-create') }}">SALVAR OFERTA</button>

                            <button type="button" class="remove-off {{ !$product->offtime ? 'hide' : '' }}" data-route="{{ route('offtime-remove') }}" data-id="{{ $product->offtime ? $product->offtime->id : '' }}">Cancelar oferta</button>
                        </div>
                    </div>
                </div>
            @endif

            <span class="advice">* indica item obrigatório</span>

            <div class="section images">
                @isset ($product)
                    @foreach ($product->images as $key => $image)
                        <div class="image loaded-image">
                            <?php /* <span class="remove-image"></span>
                            {!! Form::checkbox('image_remove[]', $image->image, false, ['autocomplete' => 'off']) !!}

                            <span class="btn-add-image"></span>
                            {!! Form::file('image[]', ['data-position' => $key, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}

                            <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $image->image) }}" /> */ ?>

                            {!! Form::checkbox('image_remove[]', $image->image, false, ['id' => 'image_remove_' . $product->id . '_' . $key, 'autocomplete' => 'off']) !!}
                            {!! Form::label('image_remove_' . $product->id . '_' . $key, ' ', ['class' => 'remove-image']) !!}

                            {!! Form::file('image[]', ['id' => 'image_' . $product->id . '_' . $key, 'data-position' => $key, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}
                            {!! Form::label('image_' . $product->id . '_' . $key, ' ', ['class' => 'btn-add-image']) !!}

                            <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $image->image) }}" />
                        </div>
                    @endforeach
                @endisset

                @for ($i = isset($product) ? ($product->images->count()) : 0; $i <= 4; $i++)
                    <div class="image no-image">
                        <?php /* <span class="btn-add-image"></span>
                        {!! Form::file('image[]', ['data-position' => $i, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}

                        <img src="#" /> */ ?>

                        {!! Form::file('image[]', ['id' => 'image_' . $i, 'data-position' => $i, 'autocomplete' => 'off', 'accept' => 'image/*']) !!}
                        {!! Form::label('image_' . $i, ' ', ['class' => 'btn-add-image']) !!}

                        <img src="#" />
                    </div>
                @endfor
            </div>

            <div class="section">
                <div class="form-group">
                    {!! Form::text('title', null, ['placeholder' => ' ', 'class' => 'field']) !!}
                    {!! Form::label('', 'Título * (Escreva a categoria, a marca e a cor)') !!}
                </div>

                <div class="form-group">
                    {!! Form::text('price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                    {!! Form::label('', 'Preço *') !!}
                </div>

                <div class="form-group">
                    {!! Form::text('off', null, ['placeholder' => ' ', 'class' => 'mask-percent field']) !!}
                    {!! Form::label('', '% OFF') !!}
                </div>

                <div class="form-group gender">
                    {!! Form::select('gender', $genders, null, ['title' => 'Departamento', 'class' => 'selectpicker field']) !!}
                    {!! Form::label('', 'Departamento', ['style' => (isset($product) && $product->gender) ? 'display: block;' : '']) !!}
                </div>

                <div class="form-group description">
                    {!! Form::textarea('description', null, ['placeholder' => ' ', 'class' => 'field']) !!}
                    {!! Form::label('', 'Descrição do produto') !!}
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
        {!! Form::close() !!}
    </div>
@endsection
