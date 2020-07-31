@extends('app', ['header_title' => 'Erro 500 - naslojas.com', 'body_class' => 'page-error'])

@section('content')
    @include ('inc.header-simple')

    <div class="container-fluid">
        <div class="no-results">
            <img src="{{ asset('images/icon-box.png') }}" />

            <p>Erro 500 - Página não encontrada</p>
        </div>
    </div>
@endsection
