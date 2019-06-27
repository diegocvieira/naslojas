$(function() {
    // CAPTURA O IP DE QUEM VAI FAZER A COMPRA
    if ($('.page-bag-order-data').length) {
        getIp(function (ip) {
            console.log(ip);
            $("input[name='client_ip']").val(ip);
        });
    }

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
                        window.location = '/sacola/dados';
                    } else {
                        $('#modal-default').modal('hide');

                        $('.open-bag').trigger('click');

                        var bag = $('header').find('.open-bag');
                        bag.text(parseInt(bag.text() ? bag.text() : 0) + 1);
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
