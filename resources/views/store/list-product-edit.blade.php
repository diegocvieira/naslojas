@foreach ($products as $product)
    {!! Form::model($product, ['method' => 'POST', 'route' => ['save-products', $product->id], 'class' => "form-edit-product " . ($product->related ? 'product-variation' : '') . " " . ($product->status == '0' ? 'product-disabled' : ''), 'files' => true, 'data-related' => $product->related ?? '']) !!}
        {!! Form::hidden('product_id', $product->id, ['class' => 'field']) !!}
        {!! Form::hidden('free_freight_price', $product->store->free_freight_price) !!}

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

                @if ($product->status == 1 && $product->store->status == 1)
                    <a href="{{ route('show-product', $product->slug) }}" target="_blank" title="Ver produto"></a>
                @endif

                <button type="button" class="select-color color-variation {{ !$product->related ? 'hidden' : '' }}" data-url="{{ route('color-variation') }}" title="Remover variação de cor"></button>
                <button type="button" class="select-color {{ $product->related ? 'hidden' : '' }}" title="Selecionar variação"></button>

                @if (!isset($section) || $section != 'add')
                    @if ($product->free_freight)
                        <button type="button" class="free-freight free-freight-selected" title="Desabilitar frete grátis" data-url="{{ route('product-free-freight') }}">frete grátis</button>
                    @else
                        <button type="button" class="free-freight" title="Habilitar frete grátis" data-url="{{ route('product-free-freight') }}">frete grátis</button>
                    @endif
                @endif
            </div>
        </div>

        <div class="row">
            <div class="form-group title">
                {!! Form::text('title', null, ['class' => 'field', 'placeholder' => ' ']) !!}
                {!! Form::label('', 'Título do produto * (A busca é feita com base nas palavras escritas aqui)') !!}
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
                {!! Form::text('price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                {!! Form::label('', 'Preço atual *') !!}
            </div>

            <div class="form-group">
                {!! Form::text('old_price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                {!! Form::label('', 'Preço anterior') !!}
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

@if($products->lastPage() > 1 && $products->currentPage() < $products->lastPage())
    <div class="pagination">
        <a href="{{ $products->nextPageUrl() }}">Exibir mais</a>
    </div>
@endif
