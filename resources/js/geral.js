$(function() {
    $('body').css('opacity', '1');

    $('.mask-week').mask('00:00 às 00:00 e 00:00 às 00:00', {reverse: false});
    $('#cep').mask('00000-000', {reverse: false, clearIfNotMatch: true});
    $('.mask-cpf').mask('000.000.000-00', {reverse: true, clearIfNotMatch: true});
    $('.mask-cnpj').mask('00.000.000/0000-00', {reverse: true, clearIfNotMatch: true});
    var SPMaskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
    },
    spOptions = {
        onKeyPress: function(val, e, field, options) {
            field.mask(SPMaskBehavior.apply({}, arguments));
        }
    };
    $('.mask-phone').mask(SPMaskBehavior, spOptions);
    $('.mask-number').mask('#', {reverse: false});
    $('.mask-money').mask('000.000.000.000.000,00', {reverse: true});
    $('.mask-percent').mask('00%', {reverse: true}).blur(function() {
        if ($(this).val() == '%') {
            $(this).val('');
        }
    });
    $('.mask-x').mask('00x', { reverse: true }).blur(function() {
        if ($(this).val() == 'x') {
            $(this).val('');
        }
    });

    // Capture cep
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

    // Alert app
    $(document).on('click', '.show-app', function (e) {
        e.preventDefault();

        modalAlert('Em breve você poderá baixar o nosso aplicativo para android e ios.');
    });

    // City
    $(document).on('click', '.show-city-modal', function(e) {
        e.preventDefault();

        modalAlert('Em breve você poderá ver os produtos das lojas de outras cidades.');
    });

    // Filters product
    $(document).on('change', '.product-filter select', function() {
        var val = $(this).val();

        $(this).attr('name') == 'order' ? $('#search-order').val(val) : $('#search-gender').val(val);

        $('#form-search').submit();
    });

    $(document).on('click', '.password-recover', function(e) {
        e.preventDefault();

        modalAlert("Informe o e-mail cadastrado.<input type='text' name='email' placeholder='digite aqui' />", 'ENVIAR');

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
            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if(data.status == true) {
                        window.location = '/';
                    } else {
                        modalAlert(data.msg);
                    }
                }
            });

            return false;
        }
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
            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if(data.status == true) {
                        window.location = '/';
                    } else {
                        modalAlert(data.msg);
                    }
                }
            });

            return false;
        }
    });

    // Modal how works
    $('.open-how-works').on('click', function(e) {
        e.preventDefault();

        var modal = $('.modal-how-works');

        modal.find('.arrow').data('position', 1);

        modal.find('.next').show();
        modal.find('.prev').hide();

        modal.find('.advance').removeClass('active');
        modal.find('.advance[data-position=1]').addClass('active');

        modal.find('img').attr('src', '/images/how-works-desktop/1.png');

        modal.modal('show');
    });
    // Passar imagens do modal nas flechas do teclado
    $('.modal-how-works').on('keydown', function(e) {
        var modal = $('.modal-how-works'),
            position = parseInt(modal.find('.position .active').data('position'));

        if(e.which == 39 && position < 6) {
            modal.find('.next').trigger('click'); // right
        } else if(e.which == 37 && position > 1) {
            modal.find('.prev').trigger('click'); // left
        }
    });
    $('.modal-how-works').on('click', '.arrow, .advance', function(e) {
        e.preventDefault();

        var modal = $('.modal-how-works'),
            position = parseInt($(this).data('position'));

        // Verifica se o click foi nas flechas
        if($(this).hasClass('arrow')) {
            // Faz o calculo para next ou prev
            position = $(this).hasClass('next') ? position + 1 : position - 1;
        }

        // Adiciona a imagem
        modal.find('img').attr('src', '/images/how-works-desktop/' + position + '.png');

        // Atualiza a posicao da imagem na flecha
        modal.find('.arrow').data('position', position);
        // Atualiza a class active nos circulos
        modal.find('.advance').removeClass('active');
        modal.find('.advance[data-position=' + position + ']').addClass('active');

        // Oculta a flecha next se estiver na ultima imagem
        position == 6 ? modal.find('.next').hide() : modal.find('.next').show();
        // Oculta a flecha prev se estiver na primeira imagem
        position == 1 ? modal.find('.prev').hide() : modal.find('.prev').show();
    });

    $(document).on('click', '.show-client-config', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            cache: false,
            success: function (data) {
                $('#modal-default').removeClass().addClass('modal fade page-client-config').find('.modal-content').html(data.body);
                $('#modal-default').modal('show');

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
            }
        });
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
                        var row = $('table').find('.show-message[data-id=' + id + ']').parents('tr');

                        row.find('.show-message').data('storemessage', message).text('Visualizar resposta');
                        row.find('.status').addClass('green').text('Respondido');
                        row.find('.answered_date').text(data.date);
                    }
                }
            });
        }
    });

    $(document).on('click', '.page-admin .change-confirm-status', function(e) {
        e.preventDefault();

        var $this = $(this);

        $.ajax({
            url: $(this).attr('href'),
            method: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                 modalAlert(data.msg);

                if(data.status) {
                    var row = $this.parents('tr');

                    row.find('.btn-status').text('-----');
                    row.find('.confirmed_date').text(data.date);

                    if (data.type == 1) {
                        row.find('.status').addClass('green').text('Confirmado');
                    } else {
                        row.find('.status').addClass('red').text('Recusado');
                    }
                }
            }
        });
    });

    $(document).on('click', '.page-admin .change-reserve-status', function(e) {
        e.preventDefault();

        var $this = $(this);

        $.ajax({
            url: $(this).attr('href'),
            method: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                 modalAlert(data.msg);

                if(data.status) {
                    var row = $this.parents('tr');

                    row.find('.btn-status').text('-----');
                    row.find('.confirmed_date').text(data.date_confirmed);

                    if (data.type == 1) {
                        row.find('.status').addClass('green').text('Confirmado');
                        row.find('.reserved_until').text(data.date_reserved);
                    } else {
                        row.find('.status').addClass('red').text('Recusado');
                    }
                }
            }
        });
    });

    // List stores when logged like superadmin
    $(document).on('click', '.open-search-stores', function(e) {
        e.preventDefault();

        $(this).next().toggle();
    });

    // Close modal list stores
    $(document).click(function(event) {
        if (!$(event.target).closest('.search-stores').length && $('.search-stores .dropdown').is(":visible")) {
            $('.search-stores .dropdown').hide();
        }
    });

































    $(document).click(function(event) {
        if (!$(event.target).closest('header').find('.bag').length && $('header').find('.bag').is(':visible')) {
            $('header').find('.bag').remove();
        }
    });

    $(document).on('click', '.open-bag', function(e) {
        e.preventDefault();

        var bag = $('header').find('.bag');

        if (bag.is(':visible')) {
            bag.remove();
        } else {
            $.ajax({
                url: $(this).attr('href'),
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('header').find('.container').append(data.body);
                }
            });
        }
    });

    $(document).on('click', '.bag-add-product', function(e) {
        e.preventDefault();

        if (!$('.size-container').find('input').is(':checked')) {
            modalAlert('Selecione um tamanho antes de adicionar o produto ao carrinho.');
        } else {
            var redirect = $(this).hasClass('redirect') ? true : false;

            $.ajax({
                url: $(this).data('url'),
                data: {
                    qtd : $('.qtd-container').find('select.qtd').val(),
                    size : $('.size-container').find('input:checked').val(),
                    product_id : $(this).data('productid')
                },
                method: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (redirect) {
                        //window.location = '/sacola/produtos';
                    } else {
                        $('#modal-default').modal('hide');

                        $('.open-bag').trigger('click');

                        var bag = $('header').find('.open-bag');
                        bag.text(parseInt(bag.text()) + 1);
                    }
                },
                error: function (request, status, error) {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            });
        }
    });

    $(document).on('click', '.bag-remove-product', function(e) {
        e.preventDefault();

        var product = $(this).parents('.product');

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                product.remove();

                var bag = $('header').find('.open-bag');
                bag.text(parseInt(bag.text()) - 1);

                updateBagInfos();
            },
            error: function (request, status, error) {
                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
            }
        });
    });

    $(document).on('change', 'select.bag-change-qtd', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'sacola/change-qtd/' + $(this).data('productid') + '/' + $(this).val(),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                updateBagInfos();
            },
            error: function (request, status, error) {
                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
            }
        });
    });

    $(document).on('change', 'select.bag-change-size', function(e) {
        e.preventDefault();

        $.ajax({
            url: 'sacola/change-size/' + $(this).data('productid') + '/' + $(this).val(),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                updateBagInfos();
            },
            error: function (request, status, error) {
                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
            }
        });
    });

    $(document).on('change', '.page-bag-order-data input[name=freight]', function() {
        $('.freight-field, .text-freight').hide();

        if ($(this).val() == '0') {
            $('.freight-house, .text-freight-house').show();

            $('.custom-validate').each(function() {
                $(this).rules('add', {
                    required: true,
                    minlength: 1,
                });
            });
        } else {
            $('.freight-store, .text-freight-store').show();

            $('.custom-validate').each(function() {
                $(this).rules('remove');
            });
        }
    });

    $(document).on('change', '.page-bag-order-data select[name=district]', function() {
        $.ajax({
            url: 'sacola/change-district/' + $(this).val(),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $('.update-freight').each(function(index) {
                    var freight = parseFloat(data.freights[index].price),
                        subtotal = parseFloat($(this).parent().find('.update-subtotal').data('subtotal'));

                    // Freight
                    $(this).text(freight > 0 ? 'R$ ' + number_format(freight, 2, ',', '.') : 'grátis');

                    // Subtotal
                    $(this).parent().find('.update-subtotal').text('R$ ' + number_format(subtotal + freight, 2, ',', '.'));
                });
            },
            error: function (request, status, error) {
                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
            }
        });
    });

    $('#form-bag-finish').validate({
        ignore: ['input[type=radio]'],
        rules: {
            phone: {
                required: true,
                minlength: 1,
                maxlength: 200
            },
            cpf: {
                required: true,
                minlength: 1,
                maxlength: 100
            },
            freight: {
                required: true,
                minlength: 1,
                maxlength: 100
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);

            if ($(element).attr('type') == 'radio') {
                $(element).parent().find('label').addClass('validate-error');
            }

            if ($(element).hasClass('selectpicker')) {
                $(element).prev().prev().addClass('validate-error');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);

            if ($(element).attr('type') == 'radio') {
                $(element).parent().find('label').removeClass('validate-error');
            }

            if ($(element).hasClass('selectpicker')) {
                $(element).prev().prev().removeClass('validate-error');
            }
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if (data.status) {
                        window.location = data.route;
                    } else {
                        modalAlert(data.msg);
                    }
                },
                error: function (request, status, error) {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            });

            return false;
        }
    });
});

function updateBagInfos() {
    var subtotal = 0;

    $('.bag').find('.product').each(function() {
        subtotal += (parseInt($(this).find('select.qtd').val()) * parseFloat($(this).find('.price').data('price')));
    });

    $('.bag').find('.subtotal span').text('R$ ' + (number_format(subtotal, 2, ',', '.')));
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
    $('#modal-alert').remove();

    $('body').append("<div class='modal fade' id='modal-alert' tabindex='-1' role='dialog'><div class='modal-dialog' role='document'><div class='modal-content'><div class='modal-body'>" + body + "</div><div class='modal-footer'><button type='button' class='btn btn-default' data-dismiss='modal'>" + btn + "</button></div></div></div></div>");

    $('#modal-alert').modal('show');

    $('.modal-backdrop:last').css('z-index', '1080');
}
