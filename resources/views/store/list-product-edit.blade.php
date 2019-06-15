@foreach ($products as $product)
    {!! Form::model($product, ['method' => 'POST', 'route' => ['save-products', $product->id], 'class' => "form-edit-product " . ($product->related ? 'product-variation' : '') . " " . ($product->status == '0' ? 'product-disabled' : ''), 'files' => true, 'data-related' => $product->related ?? '']) !!}
        {!! Form::hidden('product_id', $product->id, ['class' => 'field']) !!}
        {!! Form::hidden('free_freight_price', $product->store->free_freight_price) !!}

        <div class="row">
            <div class="col-xs-5 images">
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

            <div class="col-xs-7 options">
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

                    @if ($product->status == 1 && $product->store->status == 1 && $product->images->count())
                        <div class="download-post">
                            <button type="button" class="open-modal-post">BAIXAR POST</button>

                            <div class="modal-post">
                                <div class="top">Baixe postagens prontas para compartilhar no instagram e no facebook.</div>

                                <div class="body">
                                    <button type="button" class="btn-download-post" data-option="1" data-productid="{{ $product->id }}" data-route="{{ route('download-post') }}">STORIES PADRÃO</button>

                                    <hr>

                                    <button type="button" class="btn-download-post" data-option="2" data-productid="{{ $product->id }}" data-route="{{ route('download-post') }}">STORIES COM DESLIZAR</button>

                                    <button type="button" class="btn-copy-link-post" data-url="{{ route('show-product', $product->slug) }}">COPIAR LINK DIRETO</button>

                                    <span>Cole o link direto junto com o post para os clientes poderem deslizar para comprar.</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="create-off">
                        @if ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time))
                            <button type="button" class="btn-offtime offtime-selected">EM OFERTA</button>
                        @else
                            <button type="button" class="btn-offtime">CRIAR OFERTA</button>
                        @endif

                        <div class="modal-offtime">
                            <div class="top">O preço do produto voltará ao normal assim que o período em oferta acabar.</div>

                            <div class="body">
                                <span class="price-container">PREÇO EM OFERTA - <b>R$<span class="price">{{ number_format($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time) ? _priceOff($product->price, $product->offtime->off) : $product->price, 2, ',', '.') }}</span></b></span>

                                <div class="off-container">
                                    {!! Form::text('offtime_off', ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time)) ? $product->offtime->off : null, ['placeholder' => 'Desconto', 'class' => 'mask-percent']) !!}

                                    <button type="button" class="apply-off">APLICAR</button>
                                </div>

                                <div class="time-container">
                                    <span>Válido por</span>

                                    {!! Form::radio('offtime_time', '24', ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time) && $product->offtime->time == '24') ? true : false, ['id' => '24h_' . $product->id]) !!}
                                    {!! Form::label('24h_' . $product->id, '24h') !!}

                                    {!! Form::radio('offtime_time', '48', ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time) && $product->offtime->time == '48') ? true : false, ['id' => '48h_' . $product->id]) !!}
                                    {!! Form::label('48h_' . $product->id, '48h') !!}

                                    {!! Form::radio('offtime_time', '72', ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time) && $product->offtime->time == '72') ? true : false, ['id' => '72h_' . $product->id]) !!}
                                    {!! Form::label('72h_' . $product->id, '72h') !!}
                                </div>
                            </div>

                            <div class="bottom">
                                @if ($product->offtime && _checkDateOff($product->offtime->created_at, $product->offtime->time))
                                    <span class="offtime-time" data-date="{{ date('Y-m-d H:i:s', strtotime('+' . $product->offtime->time . ' hours', strtotime($product->offtime->created_at))) }}">TEMPO RESTANTE - <span class="offtime-timer">00h 00m 00s</span></span>
                                @endif

                                <button type="button" class="save-off" data-route="{{ route('offtime-create') }}">SALVAR OFERTA</button>

                                <button type="button" class="remove-off {{ (!$product->offtime || $product->offtime && !_checkDateOff($product->offtime->created_at, $product->offtime->time)) ? 'hide' : '' }}" data-route="{{ route('offtime-remove') }}" data-id="{{ $product->offtime ? $product->offtime->id : '' }}">Cancelar oferta</button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="form-group title">
                {!! Form::text('title', null, ['class' => 'field', 'placeholder' => ' ']) !!}
                {!! Form::label('', 'Título do produto * (ESTE É O CAMPO MAIS IMPORTANTE - ESCREVA A CATEGORIA, A MARCA E A COR NESTE CAMPO - Ex: Camiseta adidas preta)') !!}
            </div>

            <div class="form-group">
                {!! Form::text('price', null, ['placeholder' => ' ', 'class' => 'mask-money field']) !!}
                {!! Form::label('', 'Preço *') !!}
            </div>
        </div>

        <div class="row">
            <div class="form-group description">
                {!! Form::textarea('description', null, ['placeholder' => ' ', 'class' => 'field']) !!}
                {!! Form::label('', 'Descrição do produto') !!}
            </div>

            <div class="form-group">
                {!! Form::text('off', null, ['placeholder' => ' ', 'class' => 'mask-percent field']) !!}
                {!! Form::label('', '% OFF') !!}
            </div>

            <div class="form-group gender">
                {!! Form::select('gender', $genders, null, ['title' => 'Departamento', 'class' => 'selectpicker field']) !!}
                {!! Form::label('', 'Departamento', ['style' => (isset($product) && $product->gender) ? 'display: block;' : '']) !!}
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
