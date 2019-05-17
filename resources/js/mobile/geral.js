$(function() {
    $('body').css('opacity', '1');

    $('.slick-home').slick({
        //initialSlide: Math.floor(Math.random() * $('.slick-home .item').length),
        slidesToShow: 1,
        //centerMode: true,
        infinite: true,
        arrows: false,
        slidesToScroll: 1,
        //autoplay: true,
        autoplaySpeed: 4000,
        //cssEase: 'linear',
        //variableWidth: true,
        //variableHeight: true,
    });

    $('.slick-stores').slick({
        //initialSlide: Math.floor(Math.random() * $('.slick-home .item').length),
        slidesToShow: 3,
        //centerMode: true,
        infinite: true,
        arrows: false,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 4000,
        //cssEase: 'linear',
        //variableWidth: true,
        //variableHeight: true
    });

    // City
    $(document).on('click', '.show-city-modal', function(e) {
        e.preventDefault();

        modalAlert('Em breve você poderá ver os produtos das lojas de outras cidades.');
    });

    // Add overlay and close menu
    $(document).on('click', 'nav .open-menu', function() {
        $('body, header').append("<div class='close-menu' style='width: 100%; height: 100%; position: fixed; top: 0; left: 0;'></div>");
    });
    $(document).on('click', '.close-menu', function() {
        $('.close-menu').remove();
    });

    $(document).on('click', 'nav .options, .show-filter-products', function(e) {
        e.preventDefault();

        $('.close-menu').remove();

        var filter = $('.' + $(this).data('type'));

        $(this).toggleClass('active');

        filter.toggle();

        filter.is(':visible') ? $('body').css('overflow', 'hidden') : $('body').css('overflow', 'auto');
    });

    /*$(document).on('click', '.filter-products a', function(e) {
        e.preventDefault();

        var val = $(this).data('value');

        $(this).data('type') == 'order' ? $('#search-order').val(val) : $('#search-gender').val(val);

        $('#form-search').submit();
    });*/

    $(document).on('click', '.close-filter-products', function() {
        $('.filter-products').hide();
    });

    // START FILTER PRODUCTS
    $(document).on('change', '.filter-products input[type=radio]', function() {
        if ($(this).attr('name') == 'price') {
            var split = $(this).val().split('-');

            $('#search-min-price').val(split[0]);
            $('#search-max-price').val(split[1]);
        } else {
            $('#' + $(this).data('id')).val($(this).val());
        }

        $('#form-search').submit();
    });

    $(document).on('click', '.filter-products .filter-price button', function() {
        $('#search-min-price').val(parseFloat($('.filter-products .filter-price input[name=min_price]').val().replace('.', '').replace(',', '.')).toFixed(2));
        $('#search-max-price').val(parseFloat($('.filter-products .filter-price input[name=max_price]').val().replace('.', '').replace(',', '.')).toFixed(2));

        $('#form-search').submit();
    });

    $(document).on('click', '.clear-filter, .clear-all-filters', function() {
        if ($(this).hasClass('clear-filter')) {
            $('#' + $(this).data('id')).val('');
        } else {
            $('#form-search').find('input[type=hidden]').not("input[name='store_slug']").val('');
        }

        $('#form-search').submit();
    });

    // END FILTER PRODUCTS

    $(document).on('click', '.password-recover', function(e) {
        e.preventDefault();

        modalAlert("Informe o e-mail cadastrado.<input type='text' name='email' placeholder='digite aqui' />", 'Enviar');

        var modal = $('#modal-alert'),
            type = $(this).data('type');

        modal.find('.modal-footer .btn').addClass('btn-confirm');

        modal.find('.modal-footer .btn-confirm').off().on('click', function() {
            modal.find('.modal-footer .btn-confirm').text('ENVIANDO').attr('disabled', true);

            $.ajax({
                url: '/recuperar-senha/request',
                method: 'POST',
                dataType: 'json',
                data: { email : modal.find('input[name=email]').val(), type : type },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    modal.find('.modal-footer .invalid-field').remove();
                    modal.find('.modal-footer .btn-confirm').removeAttr('disabled');

                    if(data.status) {
                        modal.find('.modal-body').html('Clique no link que enviamos para o seu e-mail para recuperar a sua conta.');
                        modal.find('.modal-footer .btn-confirm').text('OK').removeClass('btn-confirm');

                        modal.find('.modal-footer .btn').off();
                    } else {
                        modal.find('.modal-footer .btn-confirm').text('ENVIAR');
                        modal.find('.modal-footer').prepend("<span class='invalid-field'>E-mail não cadastrado</span>");
                    }
                }
            });

            return false;
        });
    });

    // Show details messages
    $('.page-confirm, .page-reserve, .page-messages').on('click', '.result', function() {
        $('.result').not($(this)).find('.more-details').hide();

        $(this).find('.more-details').toggle();
    });

    $(document).on('click', '.page-messages .show-message', function(e) {
        e.preventDefault();

        var modal = $('#modal-default');

        modal.removeClass().addClass('modal fade modal-show-messages');

        modal.find('.modal-content').html("<div class='modal-body'><div class='client'><h3>" + $(this).data('clientname') + "</h3><p>" + $(this).data('clientmessage') + "</p></div></div><div class='modal-footer'></div>");

        if ($(this).data('storemessage')) {
            modal.find('.modal-body').append("<div class='store'><h3>" + $(this).data('storename') + "</h3><p>" + $(this).data('storemessage') + "</p></div>");
        }

        if(!$(this).data('storemessage') && store_logged) {
            modal.find('.modal-body').append("<div class='store'><h3>" + $(this).data('storename') + "</h3><textarea name='message' maxlength='300' placeholder='Digite aqui a sua resposta'></textarea></div>");
            modal.find('.modal-footer').append("<button type='button' data-dismiss='modal' class='inverse-color'>VOLTAR</button><button type='button' class='btn-confirm' data-id='" + $(this).data('id') + "'>ENVIAR</button>");
        } else {
            modal.find('.modal-footer').append("<button type='button' data-dismiss='modal'>OK</button>");
        }

        modal.modal('show');
    });

    $(document).on('click', '.modal-show-messages .btn-confirm', function(e) {
        e.preventDefault();

        var message = $('.modal-show-messages').find('textarea').val(),
            id = $(this).data('id');

        if(message) {
            $.ajax({
                url: '/loja/admin/mensagens/create',
                method: 'POST',
                dataType: 'json',
                data: { message : message, id : id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#modal-default').modal('hide');

                     setTimeout(function() {
                         modalAlert(data.msg);
                     }, 1000);

                    if(data.status) {
                        var row = $('.page-messages').find('.show-message[data-id=' + id + ']').parents('.more-details');

                        row.find('.show-message').data('storemessage', message).text('Visualizar resposta');
                        row.find('.status').addClass('green').text('Respondido');
                        row.find('.answered_date').text(data.date);
                    }
                }
            });
        }
    });

    $(document).on('click', '.confirm-order, .refuse-order', function(e) {
        e.preventDefault();

        var $this = $(this),
            message = $this.hasClass('confirm-order') ? 'Você confirma que o produto já foi separado para envio?' : 'O produto será removido e o cliente notificado que ele não está mais disponível. Deseja cancelar?';

        modalAlert(message, 'SIM');

        var modal = $('#modal-alert');

        modal.find('.btn-default').addClass('btn-confirm');
        modal.find('.modal-footer').append("<button type='button' class='btn btn-back invert-color' data-dismiss='modal'>NÃO</button>");

        modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
            $.ajax({
                url: $this.data('url'),
                method: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    setTimeout(function() {
                        modalAlert(data.msg);
                    }, 1000);

                    if (data.status) {
                        var row = $this.parents('.product');

                        if ($this.hasClass('confirm-order')) {
                            row.append("<span class='item'><span class='green'><b>PEDIDO CONFIRMADO</b></span></span>");
                        } else {
                            row.append("<span class='item'><span class='red'><b>PEDIDO CANCELADO</b></span></span>");
                        }

                        row.find('.confirm-order, .refuse-order').remove();
                    }
                }
            });
        });
    });

    // Page orders
    $(document).on('click', '.page-admin .order .resume-infos', function () {
        var div = $(this).parents('.order');

        $('.page-admin').find('.order').not(div).find('.complete-infos').hide();

        div.find('.complete-infos').toggle();
    });

    /*$(document).on('click', '.page-admin .show-more-infos', function () {
        var div = $(this).parents('.order');

        $('.page-admin').find('.order').not(div).find('.complete-infos').hide();

        div.find('.complete-infos').is(':visible') ? $(this).text('ver mais') : $(this).text('ver menos');
        div.find('.complete-infos').toggle();
    });*/

    $('#form-client-config').validate({
        rules: {
            name: {
                required: true,
                minlength: 1,
                maxlength: 200
            },
            email: {
                required: true,
                minlength: 1,
                maxlength: 100,
                email: true
            },
            password: {
                minlength: 8
            },
            password_confirmation: {
                minlength: 8,
                equalTo: "#password"
            },
            phone: {
                required: true,
                minlength: 1
            },
            cpf: {
                required: true,
                minlength: 1
            },
            cep: {
                required: true,
                minlength: 9,
                maxlength: 10
            },
            street: {
                required: true,
                minlength: 1,
                maxlength: 200
            },
            district: {
                required: true,
                minlength: 1,
                maxlength: 100
            },
            number: {
                required: true,
                minlength: 1,
                maxlength: 15
            },
            city: {
                required: true,
                minlength: 1
            },
            state: {
                required: true,
                minlength: 1
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);

            if ($(element).hasClass('selectpicker')) {
                $(element).prev().prev().addClass('validate-error');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);

            if ($(element).hasClass('selectpicker')) {
                $(element).prev().prev().removeClass('validate-error');
            }
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            modalAlert("Confirme sua senha atual.<input type='password' name='current_password' placeholder='digite aqui' />", 'ENVIAR');

            var modal = $('#modal-alert');

            modal.find('.btn').addClass('btn-confirm');

            modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
                $(form).find('input[name=current_password]').val(modal.find('input[name=current_password]').val());

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    dataType: 'json',
                    data: $(form).serialize(),
                    success: function (data) {
                        modal.find('.modal-footer .invalid-field').remove();

                        if(data.status == '0' || data.status == '1') {
                            modal.find('.modal-body').html(data.msg);
                            modal.find('.modal-footer .btn-confirm').removeClass('btn-confirm').text('OK');

                            modal.find('.modal-footer .btn').unbind().on('click', function() {
                                return true;
                            });
                        }

                        if(data.status == '1') {
                            $(form).find('input[type=password]').val('');
                        }

                        if(data.status == '2') {
                            modal.find('.modal-footer').prepend("<span class='invalid-field'>Senha inválida</span>");
                        }
                    }
                });

                return false;
            });
        }
    });

    // Delete client account
    $(document).on('click', '#delete-client-account', function(e) {
        e.preventDefault();

        modalAlert("Tem certeza que deseja deletar sua conta? <br> Você perderá todos os dados do seu perfil e este processo não poderá ser desfeito.<input type='password' name='current_password' placeholder='confirme aqui a sua senha atual' />", 'DELETAR');

        var modal = $('#modal-alert');

        modal.find('.btn-default').addClass('btn-confirm invert-color');
        modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back' data-dismiss='modal'>VOLTAR</button>");
        modal.find('.modal-footer .invalid-field').remove();

        modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
            $.ajax({
                url: $('#delete-client-account').attr('href'),
                method: 'POST',
                dataType: 'json',
                data: 'password=' + modal.find('input[name=current_password]').val(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if(data.status) {
                        window.location = '/';
                    } else {
                        modal.find('.modal-footer .invalid-field').remove();
                        modal.find('.modal-footer').prepend("<span class='invalid-field'>Senha inválida</span>");
                    }
                }
            });

            return false;
        });
    });

    // Login client
    $(document).on('submit', '#form-login-client', function() {
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if(data.status) {
                    window.location = '/';
                } else {
                    modalAlert(data.msg);
                }
            }
        });

        return false;
    });

    // Register client
    $('#form-register-client').validate({
        rules: {
            name: {
                required: true,
                minlength: 1,
                maxlength: 200
            },
            email: {
                required: true,
                minlength: 1,
                maxlength: 100,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            $(form).find('input[type=submit]').val('CADASTRANDO').attr('disabled', true);

            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if (data.status == true) {
                        window.location = '/';
                    } else {
                        $(form).find('input[type=submit]').val('CADASTRAR').attr('disabled', false);

                        modalAlert(data.msg);
                    }
                },
                error: function (request, status, error) {
                    $(form).find('input[type=submit]').val('CADASTRAR').attr('disabled', false);

                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            });

            return false;
        }
    });

    // Login store
    $(document).on('submit', '#form-login-store', function() {
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if(data.status) {
                    window.location = data.url;
                } else {
                    modalAlert(data.msg);
                }
            }
        });

        return false;
    });

    // Register store
    $('#form-register-store').validate({
        rules: {
            email: {
                required: true,
                minlength: 1,
                maxlength: 100,
                email: true
            },
            store_name: {
                required: true
            },
            user_name: {
                required: true
            },
            phone: {
                required: true
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            $(form).find('input[type=submit]').val('CADASTRANDO').attr('disabled', true);

            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if (data.status == true) {
                        window.location = data.url;
                    } else {
                        $(form).find('input[type=submit]').val('CADASTRAR').attr('disabled', false);

                        modalAlert(data.msg);
                    }
                },
                error: function (request, status, error) {
                    $(form).find('input[type=submit]').val('CADASTRAR').attr('disabled', false);

                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            });

            return false;
        }
    });

    if ($('.page-how-works').length) {
        if(navigator.msMaxTouchPoints) {
            $('#slider').addClass('ms-touch');
        } else {
             var slider = {
                 el: {
                     slider: $("#slider"),
                     holder: $(".holder")
                 },

                 slideWidth: $('#slider').width(),
                 touchstartx: undefined,
                 touchmovex: undefined,
                 movex: undefined,
                 index: 0,
                 longTouch: undefined,

                 init: function() {
                     this.bindUIEvents();
                 },

                 bindUIEvents: function() {
                     this.el.holder.on("touchstart", function(event) {
                         slider.start(event);
                     });

                     this.el.holder.on("touchmove", function(event) {
                         slider.move(event);
                     });

                     this.el.holder.on("touchend", function(event) {
                         slider.end(event);
                     });
                 },

                 start: function(event) {
                     // Test for flick.
                     this.longTouch = false;
                     setTimeout(function() {
                         window.slider.longTouch = true;
                     }, 250);

                     // Get the original touch position.
                     this.touchstartx =  event.originalEvent.touches[0].pageX;
                     // The movement gets all janky if there's a transition on the elements.
                     $('.animate').removeClass('animate');
                 },

                 move: function(event) {
                     // Continuously return touch position.
                     this.touchmovex =  event.originalEvent.touches[0].pageX;
                     // Calculate distance to translate holder.
                     this.movex = this.index*this.slideWidth + (this.touchstartx - this.touchmovex);
                     // Defines the speed the images should move at.
                     //var panx = 100-this.movex/10;
                     //if(this.movex < 1040) { // Makes the holder stop moving when there is no more content.
                        // this.el.holder.css('transform','translate3d(-' + this.movex + 'px,0,0)');
                     //}
                 },

                 end: function(event) {
                     // Calculate the distance swiped.
                     var absMove = Math.abs(this.index*this.slideWidth - this.movex);
                     // Calculate the index. All other calculations are based on the index.
                     if(absMove > this.slideWidth/2 || this.longTouch === false) {
                         if(this.movex > this.index*this.slideWidth && this.index < 4) {
                             this.index++;
                         } else if (this.movex < this.index*this.slideWidth && this.index > 0) {
                             this.index--;
                         }
                     }
                     // Move and animate the elements.
                     this.el.holder.addClass('animate').css('transform', 'translate3d(-' + this.index*this.slideWidth + 'px,0,0)');
                 }
             };

             slider.init();
         }
     }

     // Capture cep automatic
     $(document).on('blur', '#cep', function() {
         var cep_original = this.value;
         var cep = this.value.replace(/\D/g,'');
         var url = "https://viacep.com.br/ws/" + cep + "/json/";

         if(cep.length != 8) {
             modalAlert('Não identificamos o CEP que você informou, verifique se digitou corretamente.');

             return false;
         }

         $.getJSON(url, function(data) {
             if (data.erro == true) {
                 $('#street').val('');
                 $('#district').val('');
                 $('#city').val('');
                 $('#state').val('');

                 modalAlert('Não identificamos o CEP que você informou, verifique se digitou corretamente.');
             } else {
                 $('#street').val(data.logradouro);
                 $('#district').val(data.bairro);
                 $('#city').val(data.localidade);
                 $('#state').val(data.uf);

                 data.bairro != '' ? $("#number").focus() : $("#district").focus();
             }
         }).fail(function() {
             modalAlert('Houve um erro ao identificar o seu CEP. Entre em contato conosco.');

             return false;
         });
     });
     $(document).on('keyup', '#cep', function(e) {
         if(this.value.length == 9){
             $('#cep').trigger('blur');
         }
     });
});

function number_format(numero, decimal, decimal_separador, milhar_separador) {
   numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
   var n = !isFinite(+numero) ? 0 : +numero,
       prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
       sep = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
       dec = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
       s = '',
       toFixedFix = function (n, prec) {
           var k = Math.pow(10, prec);
           return '' + Math.round(n * k) / k;
       };

   // Fix para IE: parseFloat(0.55).toFixed(0) = 0;
   s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
   if (s[0].length > 3) {
       s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
   }
   if ((s[1] || '').length < prec) {
       s[1] = s[1] || '';
       s[1] += new Array(prec - s[1].length + 1).join('0');
   }

   return s.join(dec);
}

function modalAlert(body, btn = 'OK') {
    $('#modal-alert, .modal-backdrop').remove();

    $('body').append("<div class='modal fade' id='modal-alert' tabindex='-1' role='dialog'><div class='modal-dialog' role='document'><div class='modal-content'><div class='modal-body'>" + body + "</div><div class='modal-footer'><button type='button' class='btn btn-default' data-dismiss='modal'>" + btn + "</button></div></div></div></div>");

    $('#modal-alert').modal('show');

    $('.modal-backdrop:last').css('z-index', '1080');
}
