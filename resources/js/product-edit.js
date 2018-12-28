$(function() {
    $('.mask-money').mask('000.000.000.000.000,00', { reverse: true });
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
    });

    $(document).on('blur', 'input[name=installment], input[name=price]', function() {
        var form = $(this).parents('.form-edit-product'),
            price = form.find('input[name=price]').val(),
            installment = form.find('input[name=installment]').val();

        if (price && installment) {
            form.find('input[name=installment_price]').val(number_format(parseFloat(price.replace('.', '').replace(',', '.') / installment.replace('x', '')), 2, ',', '.'));
        }
    });

    $(document).on('change', '.form-edit-product select', function() {
        $(this).parent().next().show();
    });

    $(document).on('click', '.disable-product, .enable-product', function(e) {
        e.preventDefault();

        var $this = $(this);

        $.ajax({
            url: $this.data('url'),
            method: 'POST',
            dataType: 'json',
            data: { id : $this.data('productid') },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status) {
                    $this.parent().find('.enable-product, .disable-product').toggleClass('hidden');
                    $this.parents('.form-edit-product').toggleClass('product-disabled');
                } else {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            }
        });
    });

    $(document).on('click', '.disable-reserve, .enable-reserve', function(e) {
        e.preventDefault();

        var $this = $(this);

        $.ajax({
            url: $this.data('url'),
            method: 'POST',
            dataType: 'json',
            data: { id : $this.data('productid') },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status) {
                    $this.parent().find('.enable-reserve, .disable-reserve').toggleClass('hidden');
                } else {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            }
        });
    });

    // Delete product
    $(document).on('click', '.delete-product', function(e) {
        e.preventDefault();

        modalAlert("Tem certeza que deseja apagar este produto?", 'APAGAR');

        var modal = $('#modal-alert'),
            $this = $(this);

        modal.find('.btn-default').addClass('btn-confirm invert-color');

        modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back' data-dismiss='modal'>VOLTAR</button>");

        modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
            $.ajax({
                url: $this.data('url'),
                method: 'POST',
                dataType: 'json',
                data: { id : $this.data('productid') },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $this.parents('.form-edit-product').remove();

                    if ($('.form-edit-product').length == 0) {
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 100);
                    }
                }
            });
        });
    });

    // Select or remove products to color variation
    $(document).on('click', '.select-color', function(e) {
        e.preventDefault();

        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
        } else if ($(this).hasClass('color-variation')) {
            var data_variation = $('.color-variation[data-variation=' + $(this).data('variation') + ']');

            if (data_variation.length == 2) {
                data_variation.parents('.form-edit-product').find('input[name=related]').val('');
                data_variation.removeClass('color-variation').removeAttr('title').text('');

                if ($('.generate-color-variation').is(':visible')) {
                    data_variation.show();
                }
            } else {
                $(this).parents('.form-edit-product').find('input[name=related]').val('');
                $(this).removeClass('color-variation').removeAttr('title').text('');

                if ($('.generate-color-variation').is(':visible')) {
                    $(this).show();
                }
            }
        } else {
            $(this).addClass('selected');
        }
    });

    // Open color variations buttons
    $(document).on('click', '.open-color-variation', function(e) {
        e.preventDefault();

        $('button.select-color').show();
        $('.btns-color-variation').find('button').toggle();
    });

    // Generate color variation
    $(document).on('click', '.generate-color-variation', function(e) {
        e.preventDefault();

        var random = Math.round((new Date()).getTime()),
            actives = $('.select-color.selected'),
            next_product = 1;

        actives.parents('.form-edit-product').find('input[name=related]').val(random);

        if(actives.length > 1) {
            actives.removeClass('selected');

            $.each($('.color-variation'), function(index, val) {
                var c = $(this).attr('data-variation');

                if (c >= next_product) {
                    next_product = (parseInt(c) + 1);
                }
            });

            actives.addClass('color-variation')
                .text(next_product)
                .attr('data-variation', next_product)
                .attr('title', 'Clique para remover esta cor da variação');

            $('button.select-color').not('.color-variation').hide();
            $('.btns-color-variation').find('button').toggle();
        } else {
            modalAlert('Selecione dois ou mais produtos para agrupar.');
        }
    });

    // Remove focus from button
    $('.form-edit-product').on('keyup change', 'input, textarea, select, :checkbox', function() {
        var btn = $(this).parents('.form-edit-product').find('.copy-data');

        if(btn.hasClass('copied')) {
            btn.removeClass('copied').text('copiar dados');
        }
    });

    // Set data to local storage
    $(document).on('click', '.copy-data', function(e) {
        e.preventDefault();

        var form = $(this).parents('.form-edit-product');

        $('.copy-data').removeClass('copied');
        $(this).addClass('copied');

        form.find('.field').each(function() {
            localStorage.setItem($(this).attr('name'), $(this).val());
        });

        var sizes = [];
        $(form.find('.sizes').find('input[type=checkbox]:checked')).each(function() {
             sizes.push($(this).val());
        });
        localStorage.setItem('sizes', JSON.stringify(sizes));
    });

    // Get data from local storage
    $(document).on('click', '.paste-data', function(e) {
        e.preventDefault();

        var form = $(this).parents('.form-edit-product');

        form.find('.field').each(function() {
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
    });

    // Scroll sizes
    $('.arrow').on('click', function() {
        var div = $(this).parents('.sizes-container').find('.sizes');

        div.animate({
            'scrollLeft': $(this).data('direction') == 'right' ? div.scrollLeft() + 450 : div.scrollLeft() - 450
        }, 200);
    });

    // Remove images
    $(document).on('click', '.remove-image', function() {
        var image_container = $(this).parent();

        $('#' + $(this).attr('for')).attr('checked', true);

        image_container.find('.remove-image').remove();
        image_container.addClass('no-image').removeClass('loaded-image').find('input[type=file]').val('');
        image_container.find('input[type=hidden]').remove();
    });

    // Preview images
    $(document).on('change', '.image input[type=file]', function() {
        var reader = new FileReader(),
            $this = $(this);

        if($(this)[0].files[0].size > 5100000) {
            modalAlert('A imagem tem que ter no máximo 5mb.');
        } else {
            reader.onload = function(e) {
                $this.parent().removeClass('no-image').addClass('loaded-image').append("<label class='remove-image'></label>").find('img').attr('src', e.target.result);
                $this.parent().append("<input type='hidden' name='image_position[]' value='" + $this.data('position') + "' />");
            }

            reader.readAsDataURL($(this)[0].files[0]);
        }
    });

    // Submit forms
    $('.page-product-edit .btn-finish').on('click', function(e) {
        e.preventDefault();

        var data = new FormData(),
            errors = false,
            images = true;

        $('.form-edit-product').each(function() {
            if (!$(this).valid()) {
                errors = true;
            }

            if ($(this).find('.image.loaded-image').length == 0) {
                images = false;
            }

            if (!$(this).find('.sizes input').is(':checked')) {
                errors = true;

                $(this).find('.sizes input').addClass('error');
            }
        });

        if (errors == false) {
            if (images == true) {
                $('.btn-finish').text('SALVANDO').attr('disabled', true);

                $('.form-edit-product').each(function(index) {
                    $(this).find('.field').each(function() {
                        data.append('products[' + index + '][' + $(this).attr('name') + ']', $(this).val());
                    });

                    $(this).find("input[name='size[]']:checked").each(function() {
                        data.append('products[' + index + '][sizes][]', $(this).val());
                    });

                    $(this).find("input[name='image_remove[]']:checked").each(function() {
                        data.append('products[' + index + '][images_remove][]', $(this).val());
                    });

                    $(this).find('input:file').each(function(index2, element) {
                        if (element.files[0]) {
                            data.append('products[' + index + '][images][]', element.files[0]);
                            data.append('products[' + index + '][images_position][]', $(this).data('position'));
                        }
                    });
                });

                $.ajax({
                    url: $('.form-edit-product:first').attr('action'),
                    method: 'POST',
                    dataType: 'json',
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $('.btn-finish').text('SALVAR ALTERAÇÕES').attr('disabled', false);

                        if(data.status) {
                            window.location.reload(true);
                        } else {
                            modalAlert(data.msg);
                        }
                    }
                });
            } else {
                modalAlert('Cada produto deve ter no mínimo uma imagem.');
            }
        }
    });

    $.each($('.form-edit-product'), function(index, val) {
        $.validator.setDefaults({ ignore: ':hidden:not(.selectpicker)' });

        $(val).validate({
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
                return false;
            }
        });
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
