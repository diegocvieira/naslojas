@if (isset($search_order) || isset($search_gender) || isset($search_category) || isset($search_min_price) || isset($search_max_price) || isset($search_freight) || isset($search_size) || isset($search_brand) || isset($search_off) || isset($search_installment) || isset($search_color))
    <div class="active-filter-products">
        <button type="button" class="show-filter-products">Filtrar produtos</button>

        <button type="button" class="clear-all-filters">limpar</button>

        <div class="list">
            @if (isset($search_order))
                <button type="button" class="clear-filter" data-id="search-order">{{ $search_order }}</button>
            @endif

            @if (isset($search_gender))
                <button type="button" class="clear-filter" data-id="search-gender">{{ $search_gender }}</button>
            @endif

            @if (isset($search_category))
                <button type="button" class="clear-filter" data-id="search-category">{{ $search_category }}</button>
            @endif

            @if (isset($search_min_price) && is_float($search_min_price))
                <button type="button" class="clear-filter" data-id="search-min-price">R${{ number_format($search_min_price, 2, ',', '.') }}</button>
            @endif

            @if (isset($search_max_price) && is_float($search_max_price))
                <button type="button" class="clear-filter" data-id="search-max-price">R${{ number_format($search_max_price, 2, ',', '.') }}</button>
            @endif

            @if (isset($search_freight))
                <button type="button" class="clear-filter" data-id="search-freight">{{ $search_freight }}</button>
            @endif

            @if (isset($search_size))
                <button type="button" class="clear-filter" data-id="search-size">{{ $search_size }}</button>
            @endif

            @if (isset($search_brand))
                <button type="button" class="clear-filter" data-id="search-brand">{{ $search_brand }}</button>
            @endif

            @if (isset($search_off))
                <button type="button" class="clear-filter" data-id="search-off">{{ $search_off }}</button>
            @endif

            @if (isset($search_installment))
                <button type="button" class="clear-filter" data-id="search-installment">{{ $search_installment }}x</button>
            @endif

            @if (isset($search_color))
                <button type="button" class="clear-filter" data-id="search-color">{{ $search_color }}</button>
            @endif
        </div>
    </div>
@else
    <button type="button" class="show-filter-products">Filtrar produtos</button>
@endif

<div class="filter-products">
    <button class="close-filter-products"></button>

    @if ($orderby)
        <div class="section-filter">
            <h4 class="filter-title">ordenar</h4>

            @foreach ($orderby as $key_order => $order)
                <div class="item">
                    {!! Form::radio('order', $key_order, (isset($search_order) && $search_order == $key_order), ['id' => 'order' . $key_order, 'data-id' => 'search-order']) !!}
                    {!! Form::label('order' . $key_order, $order) !!}
                </div>
            @endforeach
        </div>
    @endif

    @if ($genders)
        <div class="section-filter">
            <h4 class="filter-title">departamento</h4>

            @foreach ($genders as $key_gender => $gender)
                <div class="item">
                    {!! Form::radio('gender', $key_gender, (isset($search_gender) && $search_gender == $key_gender), ['id' => 'gender' . $key_gender, 'data-id' => 'search-gender']) !!}
                    {!! Form::label('gender' . $key_gender, $gender) !!}
                </div>
            @endforeach
        </div>
    @endif

    @if ($categories)
        <div class="section-filter">
            <h4 class="filter-title">categoria</h4>

            <div class="list">
                @foreach ($categories as $category)
                    <div class="item">
                        {!! Form::radio('category', $category, (isset($search_category) && $search_category == $category), ['id' => 'category' . $category, 'data-id' => 'search-category']) !!}
                        {!! Form::label('category' . $category, $category) !!}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="section-filter filter-price">
        <h4 class="filter-title">preço</h4>

        <div class="filter-input">
            <div class="inputs">
                <div class="input">
                    <span>de</span>
                    {!! Form::text('min_price', $search_min_price ?? null, ['placeholder' => 'R$', 'class' => 'mask-money']) !!}
                </div>

                <div class="input">
                    <span>até</span>
                    {!! Form::text('max_price', $search_max_price ?? null, ['placeholder' => 'R$', 'class' => 'mask-money']) !!}
                </div>
            </div>

            <button type="button">OK</button>
        </div>

        @foreach ($prices as $key_price => $price)
            <div class="item">
                {!! Form::radio('price', $key_price, (isset($search_min_price) && explode('-', $key_price)[0] == $search_min_price && isset($search_max_price) && explode('-', $key_price)[1] == $search_max_price), ['id' => 'price' . $key_price]) !!}
                {!! Form::label('price' . $key_price, $price) !!}
            </div>
        @endforeach
    </div>

    <div class="section-filter">
        <h4 class="filter-title">frete</h4>

        <div class="item">
            {!! Form::radio('freight', 'grátis', (isset($search_freight) && $search_freight == 'grátis'), ['id' => 'free-freight', 'data-id' => 'search-freight']) !!}
            {!! Form::label('free-freight', 'grátis') !!}
        </div>
    </div>

    @if ($sizes)
        <div class="section-filter filter-size">
            <h4 class="filter-title">tamanho</h4>

            <div class="list">
                @foreach ($sizes as $size)
                    <div class="item">
                        {!! Form::radio('size', $size->size, (isset($search_size) && $search_size == $size->size), ['id' => 'size' . $size->size, 'data-id' => 'search-size']) !!}
                        {!! Form::label('size' . $size->size, $size->size) !!}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($brands)
        <div class="section-filter">
            <h4 class="filter-title">marca</h4>

            <div class="list">
                @foreach ($brands as $brand)
                    <div class="item">
                        {!! Form::radio('brand', $brand, (isset($search_brand) && $search_brand == $brand), ['id' => 'brand' . $brand, 'data-id' => 'search-brand']) !!}
                        {!! Form::label('brand' . $brand, $brand) !!}
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($offs)
        <div class="section-filter filter-off">
            <h4 class="filter-title">oferta</h4>

            @foreach ($offs as $key_off => $off)
                <div class="item">
                    {!! Form::radio('off', $key_off, (isset($search_off) && $search_off == $key_off), ['id' => 'off' . $key_off, 'data-id' => 'search-off']) !!}
                    {!! Form::label('off' . $key_off, $off) !!}
                </div>
            @endforeach
        </div>
    @endif

    @if ($installments)
        <div class="section-filter filter-installment">
            <h4 class="filter-title">parcelamento</h4>

            @foreach ($installments as $key_installment => $installment)
                <div class="item">
                    {!! Form::radio('installment', $key_installment, (isset($search_installment) && $search_installment == $key_installment), ['id' => 'installment' . $key_installment, 'data-id' => 'search-installment']) !!}
                    {!! Form::label('installment' . $key_installment, $installment) !!}
                </div>
            @endforeach
        </div>
    @endif

    @if ($colors)
        <div class="section-filter filter-color">
            <h4 class="filter-title">cor</h4>

            @foreach ($colors as $key_color => $color)
                <div class="item">
                    {!! Form::radio('color', $color, (isset($search_color) && $search_color == $color), ['id' => 'color' . $key_color, 'data-id' => 'search-color']) !!}
                    {!! Form::label('color' . $key_color, ' ', ['class' => $key_color]) !!}
                </div>
            @endforeach
        </div>
    @endif
</div>
