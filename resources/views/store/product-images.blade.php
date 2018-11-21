<?php
    $top_nav_store = true;
?>

@extends('base')

@section('content')
    <div class="container page-product-images">
        {!! Form::open(['route' => 'save-images', 'class' => 'dropzone', 'id' => 'my-awesome-dropzone']) !!}
        {!! Form::close() !!}
    </div>
@endsection
