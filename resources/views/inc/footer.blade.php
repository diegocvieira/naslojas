<footer>
    <div class="container">
        <div class="col-xs-6 support">
            <?php /*<a href="http://www.pelotas.com.br/" target="_blank">
                <img src="{{ asset('images/pelotas.png') }}" class="pelotas" />
            </a>

            <a href="http://fecomercio-rs.org.br/" target="_blank">
                <img src="{{ asset('images/fecomercio.png') }}" class="fecomercio" />
            </a>*/ ?>

            <img src="{{ asset('images/ssl2.png') }}" class="ssl" />
        </div>

        <div class="col-xs-6 links">
            <div class="col-xs-4">
                <a href="{{ route('home') }}">Início</a>
                <!-- <a href="https://play.google.com/store/apps/details?id=app.naslojas" target="_blank">Baixe nosso app</a> -->
                <a href="{{ route('client-register-get') }}">Cadastrar</a>
                <a href="{{ route('client-login-get') }}">Entrar</a>
            </div>

            <div class="col-xs-3">
                <a href="{{ route('rules') }}">Regras</a>
                <a href="{{ route('terms-use') }}">Termos de uso</a>
                <a href="{{ route('privacy-policy') }}">Privacidade</a>
            </div>

            <div class="col-xs-5">
                <!-- <a href="#" class="city show-city-modal">{{ Cookie::get('city_title') ? Cookie::get('city_title') . '/' . Cookie::get('state_letter') : 'Pelotas/RS' }}</a> -->

                <!-- <a href="mailto:naslojas.com" class="email">contato@naslojas.com</a> -->

                <!-- <a href="https://api.whatsapp.com/send?phone=5553991786097" class="phone">whatsapp 53 9 9178 6097</a> -->

                <div class="social">
                    <a href="https://www.facebook.com/naslojas" target="_blank" class="facebook"></a>
                    <a href="https://www.instagram.com/naslojas" target="_blank" class="instagram"></a>
                    <a href="https://twitter.com/naslojasoficial" target="_blank" class="twitter"></a>
                    <a href="https://www.youtube.com/channel/UCiu9mJrHue1ZrkzgVFFttgQ" target="_blank" class="youtube"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright">
        <p>Copyright {{ date('Y') }}. Todos os direitos reservados.<br>Dogs Are Awesome Atividades de Internet Ltda - CNPJ 32.194.554/0001-63</p>
    </div>
</footer>

<div class="modal fade" id="modal-default" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content"></div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
