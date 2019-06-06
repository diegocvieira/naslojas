$(function() {
    $(document).on('change', '.sizes input', function() {
        $(this).parents('.sizes').find('input').removeClass('error');

        if ($(this).parents('.sizes').find('input:checked').length > 1 && $(this).parents('.sizes').find("input[value='Ú']:checked").length) {
            $(this).prop('checked', false);

            modalAlert('Não é possível marcar um mesmo produto como tamanho único e também como outro tamanho.');
        }
    });

    $(document).on('change', '.form-edit-product select', function() {
        $(this).parent().next().show();
    });

    // ABRE O MODAL DE OFERTA
    $(document).on('click', '.form-edit-product .options .btn-offtime', function() {
        $(this).next().show();
    });

    // FECHA O MODAL DE OFERTA
    $(document).click(function(event) {
        if (!$(event.target).closest('.create-off').length && !$(event.target).closest('#modal-alert').length && $('.create-off .modal-offtime').is(":visible")) {
            $('.create-off .modal-offtime').hide();
        }
    });

    $(document).on('click', '.apply-off', function() {
        var form = $(this).parents('.form-edit-product'),
            price = parseFloat(form.find("input[name='price']").val().replace('.', '').replace(',', '.')),
            off = form.find(".modal-offtime input[name='offtime_off']").val().replace('%', ''),
            final_price = number_format((price - ((off / 100) * price)).toFixed(2), 2, ',', '.');

        form.find('.modal-offtime .price').text(final_price);
    });

    // CRIA UMA OFERTA
    $(document).on('click', '.save-off', function(e) {
        e.preventDefault();

        var form = $(this).parents('.form-edit-product'),
            off = form.find(".modal-offtime input[name='offtime_off']").val(),
            time = form.find(".modal-offtime input[name='offtime_time']:checked").val();

        if (off && time) {
            $.ajax({
                url: $(this).data('route'),
                method: 'POST',
                dataType: 'json',
                data: {
                    off: off,
                    time: time,
                    product_id: form.find("input[name='product_id']").val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status) {
                        form.find('.btn-offtime').addClass('offtime-selected').text('EM OFERTA');

                        $('.modal-offtime').hide();

                        form.find('.modal-offtime .remove-off').attr('data-id', data.id).removeClass('hide');
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

        var form = $(this).parents('.form-edit-product');

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
                    form.find('.btn-offtime').removeClass('offtime-selected').text('CRIAR OFERTA');

                    $('.modal-offtime').hide();

                    form.find('.modal-offtime .remove-off').addClass('hide');

                    form.find(".modal-offtime input[name='off']").val('');
                    form.find(".modal-offtime input[name='time']").prop('checked', false);
                } else {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            },
            error: function (request, status, error) {
                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
            }
        });
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
                $this.parent().find('.enable-product, .disable-product').toggleClass('hidden');
                $this.parents('.form-edit-product').toggleClass('product-disabled');
            },
            error: function (request, status, error) {
                modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
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

                $('.mask-money').mask('000.000.000.000.000,00', {reverse: true});
                $('.selectpicker').selectpicker('refresh');

                variation();
            }
        });
    });

    // Enable/Disable free freight
    $(document).on('click', '.free-freight', function() {
        var btn = $(this),
            val = btn.hasClass('free-freight-selected') ? 0 : 1;

        btn.toggleClass('free-freight-selected');

        $.ajax({
            url: btn.data('url'),
            method: 'POST',
            dataType: 'json',
            data: {
                id : btn.parents('form').find('input[name=product_id]').val(),
                free_freight : val
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (!data.status) {
                    modalAlert(data.msg);

                    btn.toggleClass('free-freight-selected');
                }
            }
        });
    });

    $(document).on('blur', 'input[name=price]', function() {
        var btn = $(this).parents('form').find('.free-freight'),
            free_freight = $(this).parents('form').find('input[name=free_freight_price]').val(),
            price = parseFloat($(this).val().replace('.', '').replace(',', '.'));

        if (free_freight && price >= free_freight && !btn.hasClass('free-freight-selected')) {
            btn.addClass('free-freight-selected');

            $.ajax({
                url: btn.data('url'),
                method: 'POST',
                dataType: 'json',
                data: {
                    id : $(this).parents('form').find('input[name=product_id]').val(),
                    free_freight : 1
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    modalAlert(data.msg);

                    if (!data.status) {
                        btn.removeClass('free-freight-selected');
                    }
                }
            });
        }
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
    $(document).on('click', '.arrow', function() {
        var div = $(this).parents('.sizes-container').find('.sizes');

        div.animate({
            'scrollLeft': $(this).data('direction') == 'right' ? div.scrollLeft() + 1200 : div.scrollLeft() - 1200
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

    // Submit forms
    $('.page-product-edit .btn-finish').on('click', function(e) {
        e.preventDefault();

        var data = new FormData(),
            errors = false,
            images = true,
            btn = $(this);

        $('.form-edit-product').each(function() {
            if (!$(this).valid() || $(this).find('.validate-error').length) {
                errors = true;
            }

            if ($(this).find('.image.loaded-image').length == 0) {
                images = false;
            }

            if (!$(this).find('.sizes input').is(':checked') && !btn.hasClass('add')) {
                errors = true;

                $(this).find('.sizes input').addClass('error');
            }
        });

        if (errors == true && !btn.hasClass('add')) {
            modalAlert('É necessário preencher todos os campos obrigatórios.');

            return false;
        }

        if (images == false && !btn.hasClass('add')) {
            modalAlert('Cada produto deve ter no mínimo uma imagem.');

            return false;
        }

        btn.text('SALVANDO').attr('disabled', true);

        $('.form-edit-product').each(function(index) {
            if (btn.hasClass('add')) {
                data.append('products[' + index + '][status]', btn.data('status'));
            }

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
                btn.text((!btn.hasClass('add') || btn.hasClass('add') && btn.data('status') == '2') ? 'SALVAR' : 'ENVIAR AO SITE').attr('disabled', false);

                if (data.status) {
                    window.location.reload(true);
                } else {
                    modalAlert(data.msg);
                }
            }
        });
    });

    if ($('.page-product-edit.page-edit').length) {
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
    }
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

        $(this).prepend("<span class='variation' title='Variações de cor de um mesmo produto'></span>");

        if ($(this)[0] === related.last()[0]) {
            $(this).find('.variation').css('height', '0');
        }

        if (related.length == 1) {
            $(this).find('.variation').remove();
        }
    });
}
