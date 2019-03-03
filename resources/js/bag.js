$(function() {
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
            div.hide();
        } else {
            div.show();

            div.find('select option').hide();

            div.find("select option[data-method='" + $(this).val() + "']").show();

            div.find('select').selectpicker('refresh');
        }
    });

                /*$(this).rules('add', {
                    required: true,
                    minlength: 1,
                });*/

    $(document).on('change', '.page-bag-order-data select[name=district]', function() {
        updateFreight($(this).val());
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
            },
            payment: {
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

function updateParcels() {
    $('.order').each(function() {
        var parcels_div = $(this).find('.parcels'),
            parcels = 0;

        if ($('.page-bag-order-data input[name=payment]:checked').val() == 1) {
            var subtotal = parseFloat($(this).find('.update-subtotal').data('subtotal')) + parseFloat($('.update-freight').text().replace('R$', '').replace('.', '').replace(',', '.'));

            for (i = 1; i <= parcels_div.data('maxparcel'); i++) {
                if (subtotal / i < parcels_div.data('minparcelprice')) {
                    parcels = i - 1;

                    break;
                }
            }
        }

        parcels_div.text(parcels ? 'em até ' + parcels + 'x de R$ ' + number_format(subtotal / parcels, 2, ',', '.') + ' sem juros' : 'à vista');
    });
}

function updateFreight(district_id) {
    $.ajax({
        url: 'sacola/change-district/' + district_id,
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

            updateParcels();
        },
        error: function (request, status, error) {
            modalAlert('Ocorreu um erro inesperado ao calcular o frete. Atualize a página e tente novamente.');
        }
    });
}
