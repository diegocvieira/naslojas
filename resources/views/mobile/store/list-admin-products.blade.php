@foreach ($products as $product)
    <div class="product {{ $product->related ? 'product-variation' : '' }} {{ ($product->status == 0 || $product->status == 2) ? 'disabled' : '' }} {{ $product->status == 2 ? 'pending' : '' }}" data-storestatus="{{ $product->store->status == 1 ? true : false }}" data-related="{{ $product->related ?? '' }}" data-slug="{{ $product->slug }}">
        {!! Form::checkbox('id[]', $product->id, null, ['id' => 'product_' . $product->id, 'autocomplete' => 'off']) !!}
        {!! Form::label('product_' . $product->id, ' ') !!}

        <a href="{{ route('get-create-edit-product', $product->id) }}">
            @if ($product->images->count())
                <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $product->images->first()->image) }}" class="image" alt="{{ $product->title }}" />
            @else
                <div class="image no-image"></div>
            @endif
        </a>
    </div>
@endforeach

@if ($products->lastPage() > 1 && $products->currentPage() < $products->lastPage())
    <div class="pagination">
        <a href="{{ $products->nextPageUrl() }}">Exibir mais</a>
    </div>
@endif
