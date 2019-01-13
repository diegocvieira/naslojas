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

        if ($(this).parents('.sizes').find('input:checked').length > 1 && $(this).parents('.sizes').find("input[value='Ú']:checked").length) {
            $(this).prop('checked', false);

            modalAlert('Não é possível marcar um mesmo produto como tamanho único e também como outro tamanho.');
        }
    });

    $(document).on('blur', 'input[name=installment], input[name=installment_price], input[name=price], input[name=old_price]', function() {
        var form = $(this).parents('.form-edit-product'),
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

    // Load more results
    $(document).on('click', '.page-product-edit .pagination a', function(e) {
        e.preventDefault();

        $(this).css('pointer-events', 'none');

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('.page-product-edit').find('.pagination').remove();
                $('.page-product-edit').find('.forms').append(data.products);

                variation();
            }
        });
    });

    if ($('.page-product-edit').length) {
        variation();
    }

    // Select or remove products to color variation
    $(document).on('click', '.select-color', function(e) {
        e.preventDefault();

        if ($(this).hasClass('color-variation')) {
            var val = $(this).parents('form').attr('data-related');

            $(this).parents('form').removeClass('product-variation').removeAttr('data-related').find('.select-color').toggleClass('hidden');

            var related = $(".form-edit-product[data-related='" + val + "']");

            if (related.length == 1) {
                related.removeClass('product-variation').removeAttr('data-related').find('.select-color').toggleClass('hidden');
            }

            related.first().after(related.not(related.first()));

            $.ajax({
                url: $(this).data('url'),
                method: 'POST',
                dataType: 'json',
                data: { ids : [$(this).parents('form').find('input[name=product_id]').val()], variation : null },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status) {
                        variation();
                    } else {
                        modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                    }
                }
            });
        } else {
            $(this).toggleClass('selected');

            if ($('.select-color.selected').length > 1) {
                $('.open-color-variation').hide();
                $('.generate-color-variation').show();
            } else {
                $('.open-color-variation').show();
                $('.generate-color-variation').hide();
            }
        }
    });

    // Show checkbox variation
    $(document).on('click', '.open-color-variation', function(e) {
        e.preventDefault();

        $('button.select-color').not('.hide').toggle();
    });

    // Generate color variation
    $(document).on('click', '.generate-color-variation', function(e) {
        e.preventDefault();

        var variation_value = Math.round((new Date()).getTime()),
            ids = [];

        $('.select-color.selected').each(function() {
            var val = $(this).parents('.form-edit-product').attr('data-related');

            $(this).parents('form').addClass('product-variation').attr('data-related', variation_value).find('.select-color').toggleClass('hidden');

            var related = $(".form-edit-product[data-related='" + val + "']");

            if (related.length == 1) {
                related.removeClass('product-variation').removeAttr('data-related').find('.select-color').toggleClass('hidden');
            }

            // Move products
            $(".form-edit-product[data-related='" + variation_value + "']").first().after($(this).parents('.form-edit-product')[0]);

            ids.push($(this).parents('form').find('input[name=product_id]').val());
        });

        $(this).hide();
        $('.select-color').removeClass('selected').hide();
        $('.open-color-variation').show();

        $.ajax({
            url: $(this).data('url'),
            method: 'POST',
            dataType: 'json',
            data: { ids : ids, variation : variation_value },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status) {
                    variation();
                } else {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            }
        });
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

        form.find('.field').not('.field[name=product_id], .field[name=related]').each(function() {
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
            if (!$(this).valid() || $(this).find('.validate-error').length) {
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

                        if (data.status) {
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

function variation() {
    var form = $('.product-variation');

    form.find('.variation').remove();

    form.each(function() {
        var related = $(".product-variation[data-related='" + $(this).attr('data-related') + "']");

        $(this).prepend("<span class='variation'></span>");

        if ($(this)[0] === related.last()[0]) {
            $(this).find('.variation').css('height', '0');
        }

        if (related.length == 1) {
            $(this).find('.variation').remove();
        }
    });
}
