        <footer>
            <div class="container">
                <div class="col-xs-6 support">
                    <a href="http://www.pelotas.com.br/" target="_blank">
                        <img src="{{ asset('images/pelotas.png') }}" />
                    </a>

                    <a href="http://fecomercio-rs.org.br/" target="_blank">
                        <img src="{{ asset('images/fecomercio.png') }}" />
                    </a>
                </div>

                <div class="col-xs-6 links">
                    <div class="col-xs-4">
                        <a href="{{ url('/') }}">Início</a>
                        <a href="#" class="open-how-works">Como funciona</a>
                        <a href="{{ route('client-register-get') }}">Cadastrar</a>
                        <a href="{{ route('client-login-get') }}">Entrar</a>
                        <a href="#" class="show-app">Baixe nosso app</a>
                    </div>

                    <div class="col-xs-4">
                        <a href="{{ route('rules') }}" target="_blank">Regras</a>
                        <a href="{{ route('terms-use') }}" target="_blank">Termos de uso</a>
                        <a href="{{ route('privacy-policy') }}" target="_blank">Privacidade</a>
                        <a href="{{ route('store-advertise') }}">Divulgar ofertas</a>
                        <a href="{{ route('store-login-get') }}">Admin da loja</a>
                    </div>

                    <div class="col-xs-4">
                        <a href="#" class="city show-city-modal">{{ Cookie::get('city_title') ? Cookie::get('city_title') . '/' . Cookie::get('state_letter') : 'Pelotas/RS' }}</a>

                        <a href="mailto:naslojas.com" class="email">contato@naslojas.com</a>

                        <span class="phone">53 9 9169 1716</span>

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

        <div class="modal fade modal-how-works" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <a href="#" class="arrow prev" data-position="1"></a>

                    <img src="{{ asset('images/how-works-desktop/1.png') }}" alt="Como funciona" />

                    <a href="#" class="arrow next" data-position="1"></a>

                    <div class="position">
                        @for($i = 1; $i <= 6; $i++)
                            <a href="#" data-position="{{ $i }}" class="advance"></a>
                        @endfor
                    </div>
                </div><!-- /.modal-content -->
            </div>
        </div><!-- /.modal -->

        <?php /*<div class="modal fade" id="modal-alert" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body"></div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->*/ ?>

        <div class="modal fade" id="modal-default" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content"></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        @if ($app->environment('local'))
            <script type="text/javascript" src="{{ asset('offline-developer/jquery.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('offline-developer/bootstrap.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('offline-developer/jquery.validate.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('offline-developer/bootstrap-select.min.js') }}"></script>
            <script type="text/javascript" src="{{ asset('offline-developer/jquery.mask.min.js') }}"></script>

            @if (Auth::guard('store')->check())
                <script type="text/javascript" src="{{ asset('offline-developer/dropzone.min.js') }}"></script>
                <script type="text/javascript" src="{{ asset('offline-developer/exif.min.js') }}"></script>
            @endif
        @else
            <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
            <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.11.2/js/bootstrap-select.min.js'></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js"></script>

            @if (Auth::guard('store')->check())
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
            @endif
        @endif

        <script>
            @if (Auth::guard('client')->check())
                var client_logged = true;
            @else
                var client_logged = false;
            @endif

            @if (Auth::guard('store')->check())
                Dropzone.autoDiscover = false;
                var store_logged = true;
            @else
                var store_logged = false;
            @endif
        </script>

        <script src="{{ mix('js/global.js') }}"></script>

        @if (Auth::guard('store')->check())
            <script src="{{ mix('js/global-store.js') }}"></script>
        @endif

        @if(session('session_flash_product_url'))
            <script>
                $(function() {
                    $('body').append("<a href='{!! session('session_flash_product_url') !!}' class='show-product show-product-url' style='display:none;'></a>");
                    $('.show-product-url').trigger('click');
                });
            </script>
        @endif

        @yield('script')
    </body>
</html>
