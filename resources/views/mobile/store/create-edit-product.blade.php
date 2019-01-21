@extends('mobile.base')

@section('content')
    <div class="container page-create-edit-product">
        @if (isset($product))
            {!! Form::model($product, ['method' => 'POST', 'route' => ['save-products', $product->id], 'id' => 'form-create-edit-product', 'files' => true]) !!}
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

                                    <li class="{{ $product->reserve == 1 ? 'hidden' : '' }}">
                                        <a href="{{ route('reserve-enable') }}" data-type="reserve-enable" class="option" data-productid="{{ $product->id }}">Habilitar reserva</a>
                                    </li>

                                    <li class="{{ $product->reserve == 0 ? 'hidden' : '' }}">
                                        <a href="{{ route('reserve-disable') }}" data-type="reserve-disable" class="option" data-productid="{{ $product->id }}">Desabilitar reserva</a>
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

                <div class="form-group">
                    {!! Form::text('reserve_discount', null, ['placeholder' => ' ', 'class' => 'mask-percent field']) !!}
                    {!! Form::label('', 'Desconto na reserva') !!}
                </div>
            </div>

            <div class="section">
                <div class="form-group gender">
                    {!! Form::select('gender', $genders, null, ['title' => 'Gênero *', 'class' => 'selectpicker field']) !!}
                    {!! Form::label('', 'Gênero *', ['style' => (isset($product) && $product->gender) ? 'display: block;' : '']) !!}
                </div>

                <div class="form-group">
                    {!! Form::text('installment', null, ['placeholder' => ' ', 'class' => 'mask-x field']) !!}
                    {!! Form::label('', 'Parcelamento') !!}
                </div>

                <div class="form-group">
                    {!! Form::text('installment_price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                    {!! Form::label('', 'Valor da parcela') !!}
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
