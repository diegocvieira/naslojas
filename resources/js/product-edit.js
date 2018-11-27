$(function() {
    $('.mask-money').mask('000.000.000.000.000,00', {reverse: true});

    $(document).on('keypress', '.mask-number', function(e) {
        if(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            return false;
        }
    });

    $(document).on('click', '.disable-product', function(e) {
        e.preventDefault();

        var $this = $(this);

        $this.val($this.hasClass('disabled') ? 'ocultar' : 'ocultado');
        $this.toggleClass('disabled');

        $.ajax({
            url: '/loja/produtos/enable-disable/' + $this.data('productid'),
            method: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {

            }
        });
    });

    // Delete product
    $(document).on('click', '.delete-product', function(e) {
        e.preventDefault();

        var modal = $('#modal-alert'),
            $this = $(this);

        modalAlert("Tem certeza que deseja apagar este produto?", 'APAGAR');

        modal.find('.btn-default').addClass('btn-confirm invert-color');

        modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back' data-dismiss='modal'>VOLTAR</button>");

        modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
            $.ajax({
                url: $this.data('url'),
                method: 'POST',
                dataType: 'json',
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
            $(this).removeClass('selected').text('selecionar');
        } else if ($(this).hasClass('color-variation')) {
            var data_variation = $('.color-variation[data-variation=' + $(this).data('variation') + ']');

            if(data_variation.length == 2) {
                data_variation.parents('.form-edit-product').find('input[name=related]').val('');
                data_variation.removeClass('color-variation').text('selecionar').removeAttr('title');
            } else {
                $(this).parents('.form-edit-product').find('input[name=related]').val('');
                $(this).removeClass('color-variation').text('selecionar').removeAttr('title');
            }
        } else {
            $(this).addClass('selected').text('selecionado');
        }
    });

    // Generate color variation
    $(document).on('click', '.btn-color-variation', function(e) {
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
                .text('Variação ' + next_product)
                .attr('data-variation', next_product)
                .attr('title', 'Clique para remover esta cor da variação');
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
        $(this).addClass('copied').text('copiado');

        localStorage.setItem('title', form.find('input[name=title]').val());
        localStorage.setItem('description', form.find('textarea[name=description]').val());
        localStorage.setItem('price', form.find('input[name=price]').val());
        localStorage.setItem('old_price', form.find('input[name=old_price]').val());
        localStorage.setItem('installment_price', form.find('input[name=installment_price]').val());
        localStorage.setItem('discount', form.find('input[name=discount]').val());
        localStorage.setItem('installment', form.find('select[name=installment]').val());
        localStorage.setItem('gender', form.find('select[name=gender]').val());

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

        form.find('input[name=title]').val(localStorage.getItem('title'));
        form.find('textarea[name=description]').val(localStorage.getItem('description'));
        form.find('input[name=price]').val(localStorage.getItem('price'));
        form.find('input[name=old_price]').val(localStorage.getItem('old_price'));
        form.find('input[name=installment_price]').val(localStorage.getItem('installment_price'));
        form.find('input[name=discount]').val(localStorage.getItem('discount'));
        form.find('select[name=installment]').val(localStorage.getItem('installment')).selectpicker('refresh');
        form.find('select[name=gender]').val(localStorage.getItem('gender')).selectpicker('refresh');

        $(form.find('.sizes').find('input[type=checkbox]')).each(function() {
            if(JSON.parse(localStorage.getItem('sizes')).includes($(this).val())) {
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

    // Generate old price or discount automatic
    $('.form-edit-product').on('blur', 'input[name=discount], input[name=old_price]', function(e) {
        var form = $(this).parents('.form-edit-product'),
            price = form.find('input[name=price]').val(),
            old_price = form.find('input[name=old_price]'),
            discount = form.find('input[name=discount]');

        if(price) {
            if($(this).attr('name') == 'discount') {
                old_price.val(number_format(parseFloat((price.replace('.', '').replace(',', '.') * 100) / (100 - discount.val())), 2, ',', '.'));
            } else {
                discount.val((Math.round((price.replace('.', '').replace(',', '.') / old_price.val().replace('.', '').replace(',', '.') - 1) * 100)).toString().replace('-', ''));
            }
        }
    });

    // Remove images
    $(document).on('click', '.remove-image', function() {
        $(this).parents('.image').hide();
    });

    // Preview images
    $(document).on('change', '.container-add-image input[type=file]', function() {
        var container = $(this).parents('.container-add-image'),
            reader = new FileReader(),
            position = (parseInt($(this).data('position')) + 1);

        if($(this)[0].files[0].size > 5100000) {
            modalAlert('A imagem tem que ter no máximo 5mb.');
        } else {
            reader.onload = function(e) {
                console.log(position);
                container.before("<div class='image'><input type='hidden' name='image_position[]' value='" + (position - 1) + "' /><label class='remove-image'></label><img src='" + e.target.result + "' /></div>");
                container.find('.btn-add-image').remove();

                if(position < 7) {
                    container.append("<input name='image[]' type='file' data-position='" + position + "' id='image_" + position + "' /><label class='btn-add-image' for='image_" + position + "'>+</label>");
                }
            }

            reader.readAsDataURL($(this)[0].files[0]);
        }
    });

    // Submit forms
    $('.page-product-edit .btn-finish').on('click', function(e) {
        e.preventDefault();

        $('.form-edit-product').submit();
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
                $('.btn-finish').text('SALVANDO').attr('disabled', true);

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    dataType: 'json',
                    data: new FormData($(form)[0]),
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        $('.btn-finish').text('SALVAR ALTERAÇÕES').attr('disabled', false);

                        if(data.status) {
                            if ($('.page-add').length) {
                                $(form).remove();

                                if ($('.form-edit-product').length == 0) {

                                    setTimeout(function() {
                                        window.location.reload(true);
                                    }, 100);
                                }
                            }
                        }

                        if (!$('.page-add').length || !data.status) {
                            modalAlert(data.msg);
                        }
                    }
                });

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
