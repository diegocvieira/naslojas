$(function() {
    if ($('.page-admin-products').length) {
        //Desactivate android options on press
        window.oncontextmenu = function(event) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        };
    }

    $(document).on('click', '.product-manager .btn-back', function(e) {
        e.preventDefault();

        var products = $('#form-product-manager').find('.product');

        products.removeClass('selected prepare-select').find('input[type=checkbox]').attr('checked', false);
        products.find('label').hide();

        $('header').find('.product-manager').remove();
        $('header').find('#logo-naslojas, .btn-back-search, nav, #form-search').show();
    });

    $(document).on('press', '.page-admin-products .product', function(e) {
        e.preventDefault();

        if ($(this).hasClass('prepare-select')) {
            return false;
        }

        $(this).addClass('selected');
        $(this).find('input[type=checkbox]').attr('checked', true);

        $('.page-admin-products').find('.product').addClass('prepare-select').find('label').show();

        $('header').find('#logo-naslojas, .btn-back-search, nav, #form-search').hide();
        $('header').append("<div class='product-manager'><button type='button' class='btn-back'></button><button type='button' class='btn-option' data-url='/loja/admin/produtos/delete'>APAGAR</button></div>");

        if ($(this).hasClass('disabled')) {
            $('header').find('.btn-option').after("<button type='button' class='btn-option enable' data-url='/loja/admin/produtos/enable'>MOSTRAR</button>");
        } else {
            $('header').find('.btn-option').after("<button type='button' class='btn-option disable' data-url='/loja/admin/produtos/disable'>OCULTAR</button>");
        }
    });

    $(document).on('click', '.page-admin-products .product.prepare-select', function(e) {
        e.preventDefault();

        if ($(this).hasClass('selected')) {
            $(this).find('input[type=checkbox]').attr('checked', false);
        } else {
            $(this).find('input[type=checkbox]').attr('checked', true);
        }

        $(this).toggleClass('selected');
    });

    $(document).on('click', '.product-manager .btn-option', function(e) {
        e.preventDefault();

        if ($(this).hasClass('enable')) {
            var msg = 'Tem certeza que deseja <b>mostrar</b> todos os produtos selecionados?';
        } else if ($(this).hasClass('disable')) {
            var msg = 'Tem certeza que deseja <b>ocultar</b> todos os produtos selecionados?';
        } else {
            var msg = 'Tem certeza que deseja <b>apagar</b> todos os produtos selecionados?';
        }

        modalAlert(msg, 'CONFIRMAR');

        var modal = $('#modal-alert'),
            url = $(this).data('url');

        modal.find('.btn-default').addClass('btn-confirm invert-color');
        modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back' data-dismiss='modal'>VOLTAR</button>");

        modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
            $('#form-product-manager').attr('action', url).submit();
        });
    });

    $(document).on('submit', '#form-product-manager', function() {
        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            dataType: 'json',
            data: form.serialize(),
            success: function (data) {
                if (data.status) {
                    var selected = form.find('.product.selected');

                    if (data.type == 'enable') {
                        selected.removeClass('disabled');
                    } else if (data.type == 'disable') {
                        selected.addClass('disabled');
                    } else {
                        selected.remove();

                        if (form.find('.product').length == 0) {
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 100);
                        }
                    }

                    selected.removeClass('selected').find('input[type=checkbox]').attr('checked', false);
                    form.find('.product').removeClass('prepare-select').find('label').hide();

                    $('header').find('.product-manager').remove();
                    $('header').find('#logo-naslojas, .btn-back-search, nav, #form-search').show();
                } else {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            }
        });

        return false;
    });
});
