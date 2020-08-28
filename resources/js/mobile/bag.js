$(function() {
    // CAPTURA O IP DE QUEM VAI FAZER A COMPRA
    if ($('.page-bag-order-data').length) {
        getIp(function (ip) {
            $("input[name='client_ip']").val(ip);
        });
    }

    $(document).on('click', '.bag-add-product', function(e) {
        e.preventDefault();

        const redirect = $(this).hasClass('redirect') ? true : false,
            image = $('#photo-zoom'),
            cartContainer = $('.open-bag'),
            qtd = $('.qtd-container').find('select.qtd').val(),
            size = $('.size-container').find('input:checked').val();

        if (!size) {
            modalAlert('Selecione um tamanho antes de adicionar o produto ao carrinho.');
            return false;
        }

        $('body').append('<img src="' + image.attr('src') + '" class="moving-product-cart" />');

        var move = $('.moving-product-cart').css({
            'top': $(this).offset().top,
            'left': $(this).offset().left,
            'width': image.width(),
            'height': image.height(),
            'position': 'absolute',
            'z-index': '9999',
            'border-radius': '50%'
        });

        move.animate({
            'top': cartContainer.offset().top + cartContainer.height() / 2,
            'left': cartContainer.offset().left + cartContainer.width() / 2,
            'width': '0',
            'height': '0'
        }, 1000, function() {
            cartContainer.addClass('cart-has-products');

            move.remove();
        });

        $.ajax({
            url: $(this).data('url'),
            data: {
                qtd : qtd,
                size : size,
                product_id : $(this).data('productid')
            },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (!data.success) {
                    modalAlert(data.message);
                    return false;
                }

                if (redirect) {
                    window.location = '/sacola/dados';
                } else {
                    $('#modal-default').modal('hide');
                }
            },
            error: function (request, status, error) {
                modalAlert(defaultErrorMessage());
            }
        });
    });

    $(document).on('click', '.bag-remove-product', function(e) {
        e.preventDefault();

        const product = $(this).parents('.product'),
            button = $(this);

        button.text('Removendo');

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    if ($('.product').length > 1) {
                        product.fadeOut(300, function () {
                            $(this).remove();
                        });

                        updateBagInfos();
                    } else {
                        window.location.reload(true);
                    }
                } else {
                    button.text('Remover da sacola');

                    modalAlert(defaultErrorMessage());
                }
            },
            error: function (request, status, error) {
                modalAlert(defaultErrorMessage());
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

    $(document).on('change', '.page-bag-order-data input[name=payment]', function() {
        updateParcels();

        var div = $('.payment-card');

        if ($(this).val() == 0) {
            $('select[name=payment_card]').rules('remove');

            div.hide();
        } else {
            $('select[name=payment_card]').rules('add', {
                required: true,
                minlength: 1,
            });

            div.show();

            div.find('select option').hide();

            div.find("select option[data-method='" + $(this).val() + "']").show();

            div.find('select').selectpicker('refresh');
        }
    });

    $(document).on('change', '.page-bag-order-data select[name=district]', function() {
        $.ajax({
            url: 'sacola/change-district/' + $(this).val(),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $('.update-freight').each(function(index) {
                    if ($(this).attr('data-freefreight') == false) {
                        var freight = parseFloat(data.freights[index].price),
                            subtotal = parseFloat($(this).parent().find('.update-subtotal').data('subtotal'));

                        // Freight
                        $(this).text(freight > 0 ? 'R$ ' + number_format(freight, 2, ',', '.') : 'grátis');

                        // Subtotal
                        $(this).parent().find('.update-subtotal').text('R$ ' + number_format(subtotal + freight, 2, ',', '.'));
                    }
                });

                updateParcels();
            },
            error: function (request, status, error) {
                modalAlert('Ocorreu um erro inesperado ao calcular o frete. Atualize a página e tente novamente.');
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
            payment: {
                required: true,
                minlength: 1
            },
            cep: {
                required: true,
                minlength: 1
            },
            street: {
                required: true,
                minlength: 1
            },
            number: {
                required: true,
                minlength: 1
            },
            district: {
                required: true,
                minlength: 1
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
            var btn = $(form).find('input[type=submit]');

            btn.val('ENVIANDO PEDIDO').prop('disabled', true);

            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if (data.status) {
                        window.location = data.route;
                    } else {
                        btn.val('ENVIAR PEDIDO').prop('disabled', false);

                        modalAlert(data.msg);
                    }
                },
                error: function (request, status, error) {
                    btn.val('ENVIAR PEDIDO').prop('disabled', false);

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

function updateParcels() {
    var payment = $('.page-bag-order-data input[name=payment]:checked');

    if (payment.length) {
        $('.order').each(function() {
            var parcels_div = $(this).find('.parcels'),
                parcels = 0,
                price = parseFloat($(this).find('.update-subtotal').data('subtotal'));
                freight = parseFloat($('.update-freight').text().replace('R$', '').replace('.', '').replace(',', '.')),
                subtotal = freight ? price + freight : price;

            if (payment.val() == 1) {
                for (i = 2; i <= parcels_div.data('maxparcel'); i++) {

                    if ((subtotal / i) >= parcels_div.data('minparcelprice')) {
                        parcels = i;
                    }
                }
            }

            parcels_div.text(parcels ? 'em até ' + parcels + 'x de R$ ' + number_format(subtotal / parcels, 2, ',', '.') + ' sem juros' : 'à vista');
        });
    }
}
