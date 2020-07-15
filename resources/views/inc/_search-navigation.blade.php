<ul class="search-navigation">
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?freight=grátis' }}">FRETE GRÁTIS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?color=preto' }}">TUDO PRETO</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?max_price=100.00' }}">ATÉ CEMZINHO</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?gender=feminino' }}">PARA MOÇAS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?gender=masculino' }}">PARA RAPAZES</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?off=10' }}">DESCONTINHOS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=esporte' }}">ESPORTE</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=casual' }}">CASUAL</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=acessorios' }}">ACESSÓRIOS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=estilo' }}">ESTILOSOS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=roupas' }}">ROUPAS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?advanced=true&keyword=calcados' }}">CALÇADOS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?brand=nike' }}">NIKE</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?brand=adidas' }}">ADIDAS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) . '?brand=melissa' }}">MELISSA</a></li>
    <li><a href="https://play.google.com/store/apps/details?id=app.naslojas">BAIXAR APP</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter')]) }}">TUDO</a></li>
</ul>
