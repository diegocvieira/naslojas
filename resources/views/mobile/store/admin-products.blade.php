<?php
    $top_nav = true;
    $body_class = 'page-admin-products';
    $admin_search = true;
?>

@extends('mobile.base')

@section('content')
    <div class="container">
        {!! Form::open(['method' => 'POST', 'id' => 'form-product-manager']) !!}
            @foreach ($products as $product)
                <div class="product {{ $product->status == 0 ? 'disabled' : '' }}">
                    {!! Form::checkbox('id[]', $product->id, null, ['id' => 'product_' . $product->id, 'autocomplete' => 'off']) !!}
                    {!! Form::label('product_' . $product->id, ' ') !!}

                    <a href="{{ route('show-product', $product->slug) }}">
                        <img src="{{ asset('uploads/' . $product->store_id . '/products/' . $product->images->first()->image) }}" class="image" alt="{{ $product->title }}" />
                    </a>
                </div>
            @endforeach
        {!! Form::close() !!}

        @include('mobile.pagination', ['paginator' => $products])
    </div>
@endsection
