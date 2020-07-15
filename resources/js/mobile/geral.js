$(function() {
    $('body').css('opacity', '1');

    $(document).on('click', '.show-select-district', function(event) {
        event.preventDefault();

        $('.select-district-container').addClass('active');
        $('body').css('overflow', 'hidden');

        $('.close-menu').trigger('click');
    });

    $(document).on('click', '.close-select-district', function() {
        $('.select-district-container').removeClass('active');
        $('body').css('overflow', 'auto');
    });

    $('.slick-home-banner').slick({
        slidesToShow: 1,
        infinite: true,
        arrows: false,
        slidesToScroll: 1,
        autoplaySpeed: 4000,
        autoplay: true
    });

    $('.slick-know, .slick-home-prices').slick({
        slidesToShow: 1,
        variableWidth: true,
        infinite: false,
        arrows: false,
        slidesToScroll: 1
    });

    $('.slick-home-products').slick({
        slidesToShow: 1,
        variableWidth: true,
        infinite: true,
        arrows: false,
        slidesToScroll: 1
    });

    $('.slick-home-stores').slick({
        slidesToShow: 1,
        variableWidth: true,
        infinite: true,
        arrows: false,
        slidesToScroll: 1,
        initialSlide: Math.floor(Math.random() * $('.slick-home-stores a').length)
    });

    $('.slick-home-brands').slick({
        infinite: true,
        arrows: false,
        slidesPerRow: 2,
        rows: 2
    });

    $('.slick-trending-words').slick({
        slidesToShow: 1,
        variableWidth: true,
        infinite: true,
        arrows: false,
        slidesToScroll: 1,
        slidesPerRow: 3,
        rows: 3
    });

    // Newsletter register
    $(document).on('submit', '#form-newsletter-register', function () {
        var form = $(this);

        form.find('input[type=submit]').val('ENVIANDO').attr('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            dataType: 'json',
            data: form.serialize(),
            success: function (data) {
                form.find('input[type=submit]').val('ENVIAR').attr('disabled', false);
                form.find('input[type=email]').val('');

                modalAlert('E-mail enviado com sucesso!');
            },
            error: function (request, status, error) {
                form.find('input[type=submit]').val('ENVIAR').attr('disabled', false);

                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
            }
        });

        return false;
    });

    // Disabled stores
    $(document).on('click', '.store-disabled', function(e) {
        e.preventDefault();

        modalAlert('Em breve!');
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

    $(document).on('click', 'nav .options', function(e) {
        e.preventDefault();

        $('.close-menu').remove();

        var filter = $('.' + $(this).data('type'));

        $(this).toggleClass('active');

        filter.toggle();

        filter.is(':visible') ? $('body').css('overflow', 'hidden') : $('body').css('overflow', 'auto');
    });

    // START FILTER PRODUCTS
    $(document).on('click', '.show-filter-products', function() {
        $('.filter-products').addClass('active');
        $('body').css('overflow', 'hidden');
    });

    $(document).on('click', '.close-filter-products', function() {
        $('.filter-products').removeClass('active');
        $('body').css('overflow', 'auto');
    });

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
        const minPrice = $('.filter-products .filter-price input[name=min_price]').val(),
            maxPrice = $('.filter-products .filter-price input[name=max_price]').val();

        if (minPrice) {
            $('#search-min-price').val(parseFloat(minPrice.replace('.', '').replace(',', '.')).toFixed(2));
        }

        if (maxPrice) {
            $('#search-max-price').val(parseFloat(maxPrice.replace('.', '').replace(',', '.')).toFixed(2));
        }

        if (minPrice || maxPrice) {
            $('#form-search').submit();
        }
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
            const button = $(form).find('input[type=submit]');

            button.val('SALVANDO').attr('disabled', true);

            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    button.val('SALVAR ALTERAÇÕES').attr('disabled', false);

                    modalAlert(data.message);
                },
                error: function (request, status, error) {
                    button.val('SALVAR ALTERAÇÕES').attr('disabled', false);

                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            });

            return false;
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

     if ($('.list-products .product').length) {
         $('.list-products .product .offtime').each(function(index, element) {
             showOffTime($(this).attr('data-date'), $(this));
         });
     }
});

// CAPTURAR IP DO USUARIO
function getIp(callback) {
    function response(s) {
        callback(window.userip);

        s.onload = s.onerror = null;
        document.body.removeChild(s);
    }

    function trigger() {
        window.userip = false;

        var s = document.createElement("script");
        s.async = true;
        s.onload = function() {
            response(s);
        };
        s.onerror = function() {
            response(s);
        };

        s.src = "https://l2.io/ip.js?var=userip";
        document.body.appendChild(s);
    }

    if (/^(interactive|complete)$/i.test(document.readyState)) {
        trigger();
    } else {
        document.addEventListener('DOMContentLoaded', trigger);
    }
}

function showOffTime(date, div) {
    var end = new Date(date),
        _second = 1000,
        _minute = _second * 60,
        _hour = _minute * 60,
        timer;

    function showRemaining() {
        var distance = end - new Date();

        if (distance < 0) {
            clearInterval(timer);

            return;
        }

        var hours = Math.floor(distance / _hour);
        var minutes = Math.floor((distance % _hour) / _minute);
        var seconds = Math.floor((distance % _minute) / _second);

        $(div).text(hours + 'h ' + minutes + 'm ' + seconds + 's');
    }

    timer = setInterval(showRemaining, 1000);
}

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
