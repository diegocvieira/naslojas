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

    $(document).on('change', '.sizes input', function() {
        $(this).parents('.sizes').find('input').removeClass('error');

        if ($(this).parents('.sizes').find('input:checked').length > 1 && $(this).parents('.sizes').find("input[value='Ú']:checked").length) {
            $(this).prop('checked', false);

            modalAlert('Não é possível marcar um mesmo produto como tamanho único e também como outro tamanho.');
        }
    });

    $(document).on('blur', 'input[name=installment], input[name=installment_price], input[name=price], input[name=old_price]', function() {
        var form = $(this).parents('#form-create-edit-product'),
            price = parseFloat(form.find('input[name=price]').val().replace('.', '').replace(',', '.')),
            old_price = parseFloat(form.find('input[name=old_price]').val().replace('.', '').replace(',', '.'));
            installment = form.find('input[name=installment]').val().replace('x', '');
            installment_price = parseFloat(form.find('input[name=installment_price]').val().replace('.', '').replace(',', '.'));

        if (price && installment && !installment_price) {
            form.find('input[name=installment_price]').val(number_format(price / installment, 2, ',', '.'));
        }

        if (price && old_price && price > old_price) {
            form.find('input[name=old_price]').addClass('validate-error');
        } else {
            form.find('input[name=old_price]').removeClass('validate-error');
        }

        if (price && installment && installment_price && (installment * installment_price) < price) {
            form.find('input[name=installment_price]').addClass('validate-error');
        } else {
            form.find('input[name=installment_price]').removeClass('validate-error');
        }
    });

    $(document).on('change', '.page-create-edit-product select', function() {
        $(this).parent().next().show();
    });

    $(document).on('click', '.page-create-edit-product .btn-add-image, .page-create-edit-product .remove-image', function(e) {
        e.preventDefault();

        $(this).next().trigger('click');
    });

    // Remove images
    $(document).on('click', '.page-create-edit-product .remove-image', function() {
        var image_container = $(this).parent();

        image_container.find('input[name=image_remove]').attr('checked', true);
        image_container.find(".remove-image, input[name='image_position[]']").remove();
        image_container.addClass('no-image').removeClass('loaded-image').find('input[type=file]').val('');
    });

    // Preview images
    $(document).on('change', '.page-create-edit-product .image input:file', function() {
        if($(this)[0].files[0].size > 5100000) {
            msgGeral('A imagem tem que ter no máximo 5mb.');
        } else {
            var $this = $(this),
                fr = new FileReader;

            fr.onload = function() {
                var data = fr.result,
                    node = $this.parent(),
                    image = new Image();

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
                        }

                        node.removeClass('no-image')
                            .addClass('loaded-image')
                            .append("<label class='remove-image'></label><input type='hidden' name='image_position[]' value='" + $this.data('position') + "' />")
                            .find('img').css('transform', rotation).attr('src', data);
                    });
                };
            };

            fr.readAsDataURL(this.files[0]);
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
                            } else if (type == 'reserve-enable') {
                                var msg = 'O produto teve a reserva habilitada.';
                            } else if (type == 'reserve-disable') {
                                var msg = 'O produto teve a reserva desabilitada.';
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
            gender: {
                required: true,
                minlength: 1,
                min: 1
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
    });
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
