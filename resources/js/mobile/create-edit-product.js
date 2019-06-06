$(function() {
    $('.mask-money').mask('000.000.000.000.000,00', {reverse: true});
    $('.mask-percent').mask('00%', { reverse: true }).blur(function() {
        if ($(this).val() == '%') {
            $(this).val('');
        }
    });
    $('.mask-x').mask('00x', { reverse: true }).blur(function() {
        if ($(this).val() == 'x') {
            $(this).val('');
        }
    });

    // ABRE O MODAL DE OFERTA
    $(document).on('click', '.btn-offtime', function() {
        $(this).next().show();
    });

    // FECHA O MODAL DE OFERTA
    $(document).click(function(event) {
        if (!$(event.target).closest('.top-options').length && !$(event.target).closest('#modal-alert').length && $('.modal-offtime').is(":visible")) {
            $('.modal-offtime').hide();
        }
    });

    $(document).on('click', '.apply-off', function() {
        var price = parseFloat($("input[name='price']").val().replace('.', '').replace(',', '.')),
            off = $(".modal-offtime input[name='offtime_off']").val().replace('%', ''),
            final_price = number_format((price - ((off / 100) * price)).toFixed(2), 2, ',', '.');

        $('.modal-offtime .price').text(final_price);
    });

    // CRIA UMA OFERTA
    $(document).on('click', '.save-off', function(e) {
        e.preventDefault();

        var off = $(".modal-offtime input[name='offtime_off']").val(),
            time = $(".modal-offtime input[name='offtime_time']:checked").val();

        if (off && time) {
            $.ajax({
                url: $(this).data('route'),
                method: 'POST',
                dataType: 'json',
                data: {
                    off: off,
                    time: time,
                    product_id: $("input[name='product_id']").val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status) {
                        $('.btn-offtime').addClass('offtime-selected').text('EM OFERTA');

                        $('.modal-offtime').hide();

                        $('.modal-offtime .remove-off').attr('data-id', data.id).removeClass('hide');
                    } else {
                        modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                    }
                },
                error: function (request, status, error) {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            });
        } else {
            modalAlert('Informe o desconto e o tempo de duração');
        }
    });

    // Remover uma oferta
    $(document).on('click', '.remove-off', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).data('route'),
            method: 'POST',
            dataType: 'json',
            data: {
                id: $(this).attr('data-id')
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status) {
                    $('.btn-offtime').removeClass('offtime-selected').text('CRIAR OFERTA');

                    $('.modal-offtime').hide();

                    $('.modal-offtime .remove-off').addClass('hide');

                    $(".modal-offtime input[name='off']").val('');
                    $(".modal-offtime input[name='time']").prop('checked', false);
                } else {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            },
            error: function (request, status, error) {
                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
            }
        });
    });

    $(document).on('change', '.sizes input', function() {
        $(this).parents('.sizes').find('input').removeClass('error');

        if ($(this).parents('.sizes').find('input:checked').length > 1 && $(this).parents('.sizes').find("input[value='Ú']:checked").length) {
            $(this).prop('checked', false);

            modalAlert('Não é possível marcar um mesmo produto como tamanho único e também como outro tamanho.');
        }
    });

    $(document).on('change', '.page-create-edit-product select', function() {
        $(this).parent().next().show();
    });

    /*$(document).on('click', '.page-create-edit-product .btn-add-image, .page-create-edit-product .remove-image', function(e) {
        e.preventDefault();

        $(this).next().trigger('click');
    });*/

    // Remove images
    $(document).on('click', '.page-create-edit-product .remove-image', function() {
        var image_container = $(this).parent();

        image_container.find('input[name=image_remove]').attr('checked', true);
        image_container.find(".remove-image, input[name='image_position[]']").remove();
        image_container.addClass('no-image').removeClass('loaded-image').find('input[type=file]').val('');
    });

    // Preview images
    /*$(document).on('change', '.page-create-edit-product .image input:file', function() {
        if ($(this)[0].files[0].size > 5100000) {
            modalAlert('Esta imagem é muito grande, por favor utilize imagens de até 5MB.');
        } else {
            var $this = $(this),
                fr = new FileReader;

            fr.onload = function() {
                var data = fr.result,
                    node = $this.parent();
                    /*image = new Image();

                image.src = data;

                image.onload = function() {
                    EXIF.getData(image, function() {
                        var orientation = EXIF.getTag(this, "Orientation");

                        switch(orientation) {
                            case 3:
                                var rotation = 'rotate(180deg)';
                                break;
                            case 6:
                                var rotation = 'rotate(90deg) scale(1.15)';
                                break;
                            case 8:
                                var rotation = 'rotate(-90deg)';
                                break;
                        }*/

                        /*node.removeClass('no-image')
                            .addClass('loaded-image')
                            .append("<label class='remove-image'></label><input type='hidden' name='image_position[]' value='" + $this.data('position') + "' />")
                            .find('img').attr('src', data);
                            //.find('img').css('transform', rotation).attr('src', data);
                    //});
                //};
            };

            fr.readAsDataURL(this.files[0]);
        }
    });*/

    // Preview images
    $(document).on('change', '.page-create-edit-product .image input[type=file]', function() {
        var reader = new FileReader(),
            $this = $(this);

        if ($(this)[0].files[0].size > 5100000) {
            modalAlert('Esta imagem é muito grande, por favor utilize imagens de até 5MB.');
        } else {
            reader.onload = function(e) {
                $this.parent().removeClass('no-image').addClass('loaded-image').append("<label class='remove-image'></label>").find('img').attr('src', e.target.result);
                $this.parent().append("<input type='hidden' name='image_position[]' value='" + $this.data('position') + "' />");
            }

            reader.readAsDataURL($(this)[0].files[0]);
        }
    });

    $(document).on('blur', 'input[name=price]', function() {
        var free_freight = $('input[name=free_freight_price]').val(),
            price = parseFloat($(this).val().replace('.', '').replace(',', '.'));

        if (free_freight && price >= free_freight && $('.free-freight-selected').hasClass('hidden')) {
            $('.free-freight').addClass('hidden');
            $('.free-freight-selected').removeClass('hidden');

            $.ajax({
                url: $('.free-freight').attr('href'),
                method: 'POST',
                dataType: 'json',
                data: {
                    id : $('input[name=product_id]').val(),
                    free_freight : 1
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    modalAlert(data.msg);

                    if (!data.status) {
                        $('.free-freight').addClass('hidden');
                        $('.free-freight').not('.free-freight-selected').removeClass('hidden');
                    }
                }
            });
        }
    });

    $(document).on('click', '.page-create-edit-product header .option', function(e) {
        e.preventDefault();

        $('.close-menu').remove();

        var type = $(this).attr('data-type');

        if (type == 'copy-data') {
            var form = $('#form-create-edit-product');

            form.find('.field').not('.field[name=product_id], .field[name=related]').each(function() {
                localStorage.setItem($(this).attr('name'), $(this).val());
            });

            var sizes = [];
            $(form.find('.sizes').find('input[type=checkbox]:checked')).each(function() {
                 sizes.push($(this).val());
            });
            localStorage.setItem('sizes', JSON.stringify(sizes));
        } else if (type == 'paste-data') {
            var form = $('#form-create-edit-product');

            form.find('.field').not('.field[name=product_id], .field[name=related]').each(function() {
                $(this).val(localStorage.getItem($(this).attr('name')));

                if ($(this).attr('name') == 'gender') {
                    $(this).selectpicker('refresh');
                }
            });

            $(form.find('.sizes').find('input[type=checkbox]')).each(function() {
                if (JSON.parse(localStorage.getItem('sizes')).includes($(this).val())) {
                     $(this).attr('checked', true);
                 } else {
                     $(this).attr('checked', false);
                 }
            });
        } else if (type == 'free-freight') {
            $('.free-freight').toggleClass('hidden');

            $.ajax({
                url: $(this).attr('href'),
                method: 'POST',
                dataType: 'json',
                data: {
                    id : $(this).data('productid'),
                    free_freight : ($(this).hasClass('free-freight-selected') ? 0 : 1)
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (!data.status) {
                        modalAlert(data.msg);

                        $('.free-freight').toggleClass('hidden');
                    }
                }
            });
        } else if (type == 'link-share') {
            modalAlert("<div class='top'><b>Cole nas suas redes sociais e whatsapp</b><br>As informações aparecerão automaticamente e o usuário poderá clicar para fazer o pedido"
                + "<input type='text' value='" + $(this).attr('href') + "' readonly /><div class='modal-footer'><button type='button' class='btn btn-default' data-dismiss='modal'>COPIAR</button></div></div><div class='post'>"
                + "<img src='" + $(this).data('image') + "' />"
                + "<span class='site-title'>NASLOJAS.COM</span>"
                + "<span class='title'>Clique para pedir | Frete " + $(this).data('freight') + " | Entrega em 24hs | Pague somente ao receber</span>"
                + "<span class='description'>" + $(this).data('store') + " | Pelotas | " + $(this).data('title') + "</span></div>");

            var modal = $('#modal-alert');

            modal.addClass('modal-link-share');
            modal.find('.btn').addClass('btn-confirm');

            modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
                modal.find('input').select();

                document.execCommand('copy');
            });
        } else {
            var url = $(this).attr('href'),
                product_id = $(this).data('productid');

            if (type == 'delete') {
                modalAlert('Tem certeza que deseja excluir este produto?', 'CONFIRMAR');

                var modal = $('#modal-alert');

                modal.find('.btn-default').addClass('btn-confirm invert-color');
                modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back' data-dismiss='modal'>VOLTAR</button>");

                modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
                    $.ajax({
                        url: url,
                        method: 'POST',
                        dataType: 'json',
                        data: { id : product_id },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            if (data.status) {
                                window.location = '/loja/admin/produtos';
                            } else {
                                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                            }
                        }
                    });
                });
            } else {
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    data: { id : product_id },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.status) {
                            if (type == 'product-enable') {
                                var msg = 'O produto foi ativado.';
                            } else if (type == 'product-disable') {
                                var msg = 'O produto foi desativado.';
                            } else {
                                var msg = 'Informações salvas com sucesso!'; // Just for precaution
                            }

                            var header = $('.page-create-edit-product header');

                            if (type == 'product-enable' || type == 'product-disable') {
                                header.find('a[data-type=product-enable], a[data-type=product-disable]').parent().toggleClass('hidden');
                            } else {
                                header.find('a[data-type=reserve-enable], a[data-type=reserve-disable]').parent().toggleClass('hidden');
                            }

                            modalAlert(msg);
                        } else {
                            modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                        }
                    }
                });
            }
        }
    });

    /*$('#form-create-edit-product').validate({
        rules: {
            title: {
                required: true,
                minlength: 1,
                maxlength: 255
            },
            price: {
                required: true,
                minlength: 1
            },
            description: {
                maxlength: 2000
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);

            if ($(element).hasClass('selectpicker')) {
                $(element).prev().prev().addClass('error');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);

            if ($(element).hasClass('selectpicker')) {
                $(element).prev().prev().removeClass('error');
            }
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            if (!$(form).find('.sizes input').is(':checked')) {
                $(form).find('.sizes input').addClass('error');

                return false;
            }

            if ($(form).find('.validate-error').length) {
                return false;
            }

            $(form).find('input[type=submit]').val('SALVANDO').attr('disabled', true);

            var images = $('.image.loaded-image').length > 0 ? true : false;

            if (images == true) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    dataType: 'json',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        $(form).find('input[type=submit]').val('SALVAR').attr('disabled', false);

                        if (data.status) {
                            window.location = '/loja/admin/produtos';
                        } else {
                            modalAlert(data.msg);
                        }
                    }
                });
            } else {
                $(form).find('input[type=submit]').val('SALVAR').attr('disabled', false);

                modalAlert('Selecione no mínimo uma imagem.');
            }

            return false;
        }
    });*/

    $('#form-create-edit-product .btn-finish').on('click', function(e) {
        e.preventDefault();

        var form = $('#form-create-edit-product'),
            btn = $(this);

        if (!form.find('.sizes input').is(':checked') && btn.data('status') == '1') {
            form.find('.sizes input').addClass('error');

            return false;
        }

        if (form.find('.validate-error').length && btn.data('status') == '1') {
            return false;
        }

        var images = $('.image.loaded-image').length > 0 ? true : false;

        if (!images && btn.data('status') == '1') {
            modalAlert('Selecione no mínimo uma imagem.');

            return false;
        }

        form.append("<input type='hidden' name='status' value='" + btn.data('status') + "' />");

        btn.text('SALVANDO').attr('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            dataType: 'json',
            data: new FormData(form[0]),
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                btn.text(btn.data('status') == '1' ? 'ENVIAR AO SITE' : 'SALVAR').attr('disabled', false);

                if (data.status) {
                    window.location = '/loja/admin/produtos';
                } else {
                    modalAlert(data.msg);
                }
            }
        });
    });

    /*if ($('#form-create-edit-product input[name=product_id]').length) {
        $.validator.setDefaults({ ignore: ':hidden:not(.selectpicker)' });

        $('#form-create-edit-product').validate({
            rules: {
                title: {
                    required: true,
                    minlength: 1,
                    maxlength: 255
                },
                price: {
                    required: true,
                    minlength: 1
                },
                description: {
                    maxlength: 2000
                }
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass(errorClass).removeClass(validClass);

                if ($(element).hasClass('selectpicker')) {
                    $(element).prev().prev().addClass('error');
                }
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass(errorClass).addClass(validClass);

                if ($(element).hasClass('selectpicker')) {
                    $(element).prev().prev().removeClass('error');
                }
            },
            errorPlacement: function(error, element) {
            },
            submitHandler: function(form) {
                return false;
            }
        });
    }*/
});

// Format dollar to real
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
    if(s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }

    return s.join(dec);
}
