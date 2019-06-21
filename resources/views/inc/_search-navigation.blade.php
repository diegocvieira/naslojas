<ul class="search-navigation">
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?freight=grátis' }}">FRETE GRÁTIS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?color=preto' }}">TUDO PRETO</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?max_price=100.00' }}">ATÉ CEMZINHO</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?gender=feminino' }}">PARA MOÇAS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?gender=masculino' }}">PARA RAPAZES</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?off=10' }}">DESCONTINHOS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=esporte' }}">ESPORTE</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=casual' }}">CASUAL</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=acessorios' }}">ACESSÓRIOS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=estilo' }}">ESTILOSOS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=roupas' }}">ROUPAS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?advanced=true&keyword=calcados' }}">CALÇADOS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?brand=nike' }}">NIKE</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?brand=adidas' }}">ADIDAS</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) . '?brand=melissa' }}">MELISSA</a></li>
    <li><a href="https://play.google.com/store/apps/details?id=app.naslojas">BAIXAR APP</a></li>
    <li><a href="{{ route('search-products', [Cookie::get('city_slug'), Cookie::get('state_letter_lc')]) }}">TUDO</a></li>
</ul>
