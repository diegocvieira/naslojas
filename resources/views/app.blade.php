<!DOCTYPE html>
<html lang="pt-br">
    <head>
    	<meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>{{ $header_title ?? 'naslojas - Compre nas lojas da sua cidade e receba seu pedido em 24hs' }}</title>

        <base href="{{ url('/') }}">
        <link rel="canonical" href="{{ $header_canonical ?? url()->current() }}" />
    	<link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    	<meta name="theme-color" content="#ff1744">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="google-site-verification" content="iDxi3PlfFp-zPVdB0mjxWc4egmMyZxdCi8eU5zJzLS8" />

    	<!-- SEO META TAGS -->
        <meta name="keywords" content="naslojas, lojas, físicas, cidade, produtos, comprar, vender, clientes, comparar, pesquisar, preço, valor" />
    	<meta name="description" content="{{ $header_desc ?? 'Confira as ofertas das lojas físicas da sua cidade. Pague somente ao receber seu pedido. A melhor experiência de compras da internet.' }}" />
    	<meta itemprop="name" content="{{ $header_title ?? 'naslojas' }}" />
    	<meta itemprop="description" content="{{ $header_desc ?? 'Confira as ofertas das lojas físicas da sua cidade. Pague somente ao receber seu pedido. A melhor experiência de compras da internet.' }}" />
    	<meta itemprop="image" content="{{ $header_image ?? asset('images/social-naslojas.png') }}" />

    	<meta name="twitter:card" content="summary_large_image" />
    	<meta name="twitter:title" content="{{ $header_title ?? 'naslojas' }}" />
    	<meta name="twitter:description" content="{{ $header_desc ?? 'Confira as ofertas das lojas físicas da sua cidade. Pague somente ao receber seu pedido. A melhor experiência de compras da internet.' }}" />
    	<!-- imagens largas para o Twitter Summary Card precisam ter pelo menos 280x150px  -->
    	<meta name="twitter:image" content="{{ $header_image ?? asset('images/social-naslojas.png') }}" />

    	<meta property="og:title" content="{{ $header_title ?? 'naslojas' }}" />
    	<meta property="og:type" content="website" />
    	<meta property="og:url" content="{{ url()->current() }}" />
    	<meta property="og:image" content="{{ $header_image ?? asset('images/social-naslojas.png') }}" />
        <meta property="og:image:secure_url" content="{{ $header_image ?? asset('images/social-naslojas.png') }}" />
        <meta property="og:description" content="{{ $header_desc ?? 'Confira as ofertas das lojas físicas da sua cidade. Pague somente ao receber seu pedido. A melhor experiência de compras da internet.' }}" />
    	<meta property="og:site_name" content="naslojas" />
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="fb:app_id" content="2156565304635391" />

        <style>body{opacity:0;}</style>

        @if (Agent::isMobile())
            <link rel="stylesheet" type="text/css" href="{{ mix('css/global-mobile.css') }}">
        @else
            <link rel="stylesheet" type="text/css" href="{{ mix('css/global.css') }}">
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
        @yield ('content')

        @if ($app->environment('production'))
            <!--Start of Tawk.to Script-->
            <script type="text/javascript">
                var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                (function(){
                var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                s1.async=true;
                s1.src='https://embed.tawk.to/59810f554471ce54db6521a4/default';
                s1.charset='UTF-8';
                s1.setAttribute('crossorigin','*');
                s0.parentNode.insertBefore(s1,s0);
                })();
            </script>
            <!--End of Tawk.to Script-->

            <!-- Hotjar Tracking Code for www.naslojas.com -->
            <script>
                (function(h,o,t,j,a,r){
                    h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
                    h._hjSettings={hjid:1165278,hjsv:6};
                    a=o.getElementsByTagName('head')[0];
                    r=o.createElement('script');r.async=1;
                    r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
                    a.appendChild(r);
                })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
            </script>
        @endif

        <script>
            @if (Auth::guard('client')->check())
                var client_logged = true;
            @else
                var client_logged = false;
            @endif

            @if (Auth::guard('store')->check())
                var store_logged = true;
            @else
                var store_logged = false;
            @endif
        </script>

        @if (Agent::isMobile())
            <script src="{{ mix('js/global-mobile.js') }}"></script>
        @else
            <script src="{{ mix('js/global.js') }}"></script>
        @endif

        @if (session('session_flash_alert'))
            <script>
                modalAlert("{!! session('session_flash_alert') !!}");
            </script>
        @endif

        @yield('script')
    </body>
</html>
