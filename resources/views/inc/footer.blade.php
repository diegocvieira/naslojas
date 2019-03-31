        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 support mobile-off">
                        <div class="row">
                            <div class="col-xl-4 col-md-4">
                                <a href="http://www.pelotas.com.br/" target="_blank">
                                    <img src="{{ asset('images/pelotas.png') }}" class="pelotas" />
                                </a>
                            </div>

                            <div class="col-xl-4 col-md-4">
                                <a href="http://fecomercio-rs.org.br/" target="_blank">
                                    <img src="{{ asset('images/fecomercio.png') }}" class="fecomercio" />
                                </a>
                            </div>

                            <div class="col-xl-4 col-md-4">
                                <img src="{{ asset('images/ssl2.png') }}" class="ssl" />
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 links">
                        <div class="row">
                            <span class="col-md-4">
                                <a href="{{ url('/') }}">Início</a>
                                <a href="#" class="open-how-works">Como funciona</a>
                                <a href="https://play.google.com/store/apps/details?id=app.naslojas" target="_blank">Baixe nosso app</a>
                                <a href="{{ route('client-register-get') }}">Cadastrar</a>
                                <a href="{{ route('client-login-get') }}">Entrar</a>
                            </span>

                            <span class="col-md-4">
                                <a href="{{ route('rules') }}" target="_blank">Regras</a>
                                <a href="{{ route('terms-use') }}" target="_blank">Termos de uso</a>
                                <a href="{{ route('privacy-policy') }}" target="_blank">Privacidade</a>
                                <a href="{{ route('store-advertise') }}">Vender online</a>
                                <a href="{{ route('store-login-get') }}">Admin da loja</a>
                            </span>


                            <span class="col-md-4">
                                <a href="#" class="show-city-modal">{{ Cookie::get('city_title') ? Cookie::get('city_title') . '/' . Cookie::get('state_letter') : 'Pelotas/RS' }}</a>

                                <a href="mailto:naslojas.com">contato@naslojas.com</a>

                                <a href="https://api.whatsapp.com/send?phone=5553991786097">whatsapp 53 9 9178 6097</a>

                                <div class="social">
                                    <a href="https://www.facebook.com/naslojas" target="_blank" class="facebook"></a>
                                    <a href="https://www.instagram.com/naslojas" target="_blank" class="instagram"></a>
                                    <a href="https://twitter.com/naslojasoficial" target="_blank" class="twitter"></a>
                                    <a href="https://www.youtube.com/channel/UCiu9mJrHue1ZrkzgVFFttgQ" target="_blank" class="youtube"></a>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12 text-center copyright">
                        <p>
                            Copyright {{ date('Y') }}. Todos os direitos reservados.
                            <br>Dogs Are Awesome Atividades de Internet Ltda <span class="mobile-off">-</span> <span class="cnpj">CNPJ 32.194.554/0001-63</span>
                        </p>
                    </div>
                </div>
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

        <div class="modal fade" id="modal-default" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content"></div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.17.0/dist/jquery.validate.min.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.9/js/bootstrap-select.min.js'></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.8/jquery.mask.min.js"></script>

        @if (Auth::guard('store')->check() || Auth::guard('superadmin')->check())
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/exif-js/2.3.0/exif.min.js"></script>
        @endif

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

            @if (Auth::guard('store')->check() || Auth::guard('superadmin')->check())
                Dropzone.autoDiscover = false;
                var store_logged = true;
            @else
                var store_logged = false;
            @endif
        </script>

        <script src="{{ mix('js/global.js') }}"></script>

        @if (Auth::guard('store')->check() || Auth::guard('superadmin')->check())
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
