<!DOCTYPE html>
<html lang="pt-br">
    <head>
    	<meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="google-site-verification" content="iDxi3PlfFp-zPVdB0mjxWc4egmMyZxdCi8eU5zJzLS8" />

    	<base href="{{ url('/') }}">

    	<title>{{ $header_title ?? 'naslojas - Compre nas lojas de Pelotas e receba seu pedido em 24hs' }}</title>

    	<link rel="shortcut icon" href="{{ asset('images/favicon-mobile.png') }}">

    	<meta name="theme-color" content="#ff1744">

    	<!-- SEO META TAGS -->
    	<meta name="csrf-token" content="{!! csrf_token() !!}">

    	@if (isset($header_keywords))
    		<meta name="keywords" content="{{ $header_keywords }}" />
    	@else
    		<meta name="keywords" content="naslojas, lojas, físicas, cidade, produtos, comprar, vender, clientes, comparar, pesquisar, preço, valor" />
    	@endif

    	<link rel="canonical" href="{{ $header_canonical ?? url()->current() }}" />

    	<meta name="description" content="{{ $header_desc ?? 'Confira as ofertas das lojas físicas de Pelotas. Pague somente ao receber seu pedido. A melhor experiência de compras da internet.' }}" />
    	<meta itemprop="name" content="{{ $header_title ?? 'naslojas' }}" />
    	<meta itemprop="description" content="{{ $header_desc ?? 'Confira as ofertas das lojas físicas de Pelotas. Pague somente ao receber seu pedido. A melhor experiência de compras da internet.' }}" />
    	<meta itemprop="image" content="{{ $header_image ?? asset('images/social-naslojas.png') }}" />

    	<meta name="twitter:card" content="summary_large_image" />
    	<meta name="twitter:title" content="{{ $header_title ?? 'naslojas' }}" />
    	<meta name="twitter:description" content="{{ $header_desc ?? 'Confira as ofertas das lojas físicas de Pelotas. Pague somente ao receber seu pedido. A melhor experiência de compras da internet.' }}" />
    	<!-- imagens largas para o Twitter Summary Card precisam ter pelo menos 280x150px  -->
    	<meta name="twitter:image" content="{{ $header_image ?? asset('images/social-naslojas.png') }}" />

    	<meta property="og:title" content="{{ $header_title ?? 'naslojas' }}" />
    	<meta property="og:type" content="website" />
    	<meta property="og:url" content="{{ url()->current() }}" />
    	<meta property="og:image" content="{{ $header_image ?? asset('images/social-naslojas.png') }}" />
    	<meta property="og:description" content="{{ $header_desc ?? 'Confira as ofertas das lojas físicas de Pelotas. Pague somente ao receber seu pedido. A melhor experiência de compras da internet.' }}" />
    	<meta property="og:site_name" content="naslojas" />

        <style>body{opacity:0;}</style>

        @if ($app->environment('local'))
            <link rel="stylesheet" href="{{ asset('offline-developer/bootstrap.min.css') }}">
            <link rel="stylesheet" href="{{ asset('offline-developer/bootstrap-select.min.css') }}">
        @else
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/css/bootstrap-select.min.css">
        @endif

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css">

        <link rel="stylesheet" type="text/css" href="{{ mix('css/global-mobile.css') }}">

        @if (Auth::guard('store')->check() || Auth::guard('superadmin')->check())
            <link rel="stylesheet" type="text/css" href="{{ mix('css/global-store-mobile.css') }}">
        @endif

        @if ($app->environment('production'))
            <script>
                (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

                ga('create', 'UA-96847699-1', 'auto');
                ga('send', 'pageview');
            </script>
        @endif
    </head>
    <body class="{{ $body_class ?? '' }}">
        @if(session('session_flash_alert'))
            @section('script')
                <script>
                    modalAlert("{!! session('session_flash_alert') !!}");
                </script>
            @endsection
        @endif
