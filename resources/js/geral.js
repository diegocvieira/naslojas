$(function() {
    $('body').css('opacity', '1');

    // Alert app
    $(document).on('click', '.show-app', function (e) {
        e.preventDefault();

        modalAlert('Em breve você poderá baixar o nosso aplicativo para android e ios.');
    });

    // Open cities
    $(document).on('click', '.open-cities', function() {
        modalAlert('Em breve os usuários de outras cidades também poderão utilizar o naslojas.com.');

        //$(this).next().show();
    });

    // Close cities
    /*$(document).click(function(e) {
        if(!$(e.target).closest('.cities').length) {
            $('.cities').find('.drop-down').hide();
        }
    });*/

    // Filters product
    $(document).on('change', '.product-filter select', function() {
        var val = $(this).val();

        $(this).attr('name') == 'order' ? $('#search-order').val(val) : $('#search-gender').val(val);

        $('#form-search').submit();
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
                    window.location = '/';
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
                        var modal = $('#modal-alert');

                        modalAlert("Confirme sua senha atual.<input type='password' name='current_password' placeholder='digite aqui' />", 'ENVIAR');

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

        var modal = $('#modal-alert');

        modalAlert("Tem certeza que deseja deletar sua conta? <br> Você perderá todos os dados do seu perfil e este processo não poderá ser desfeito.<input type='password' name='current_password' placeholder='confirme aqui a sua senha atual' />", 'DELETAR');

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
            modal.find('.modal-body').append("<div class='store'><h3>" + $(this).data('storename') + "</h3><textarea name='message' placeholder='Digite aqui a sua resposta'></textarea></div>");
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





















});

function modalAlert(body, btn = 'OK') {
    var modal = $('#modal-alert');

    modal.find('.modal-footer .btn-back').remove();

    modal.find('.modal-body').html(body);
    modal.find('.modal-footer .btn').removeClass('btn-confirm').text(btn);
    modal.find('.modal-footer .btn').text(btn);
    modal.modal('show');

    $('.modal-backdrop:last').css('z-index', '1080');
}
