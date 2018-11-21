@include('inc.header')

@if (isset($top_nav))
    @include('inc.top-nav')
@elseif (isset($top_simple))
    @include('inc.top-simple')
@elseif (isset($top_nav_store))
    @include('inc.top-nav-store')
@endif

@yield('content')

@include('inc.footer')
