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

        $('header').toggle();
    });

    $(document).on('press', '.page-admin-products .product', function(e) {
        e.preventDefault();

        if ($(this).hasClass('prepare-select')) {
            return false;
        }

        $(this).addClass('selected');
        $(this).find('input[type=checkbox]').attr('checked', true);

        $('.page-admin-products').find('.product').addClass('prepare-select').find('label').show();

        $('header').toggle();

        if ($(this).hasClass('disabled')) {
            $('.product-manager').find('a[data-type=show-product]').hide();

            $('.product-manager nav').find('a[data-type=show-product]').attr('href', '/produto/' + $(this).data('slug'));
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

        var links = $('.product-manager nav').find('a[data-type=show-product], a[data-type=copy-data]');

        if ($('.product.selected').length == 1 && !$('.product.selected').hasClass('disabled')) {
            links.show();

            $('.product-manager nav').find('a[data-type=show-product]').attr('href', '/produto/' + $(this).data('slug'));
        } else {
            links.hide();
        }
    });

    $(document).on('click', '.product-manager .dropdown-menu a', function(e) {
        e.preventDefault();

        var type = $(this).data('type'),
            url = $(this).attr('href');

        if (type == 'show-product') {
            window.open(url, '_blank');
        } else {
            if (type == 'product-enable') {
                var msg = 'Tem certeza que deseja <b>mostrar</b> todos os produtos selecionados?';
            } else if (type == 'product-disable') {
                var msg = 'Tem certeza que deseja <b>ocultar</b> todos os produtos selecionados?';
            } else if (type == 'delete') {
                var msg = 'Tem certeza que deseja <b>apagar</b> todos os produtos selecionados?';
            } else if (type == 'reserve-enable') {
                var msg = 'Tem certeza que deseja <b>habilitar</b> a reserva de todos os produtos selecionados?';
            } else if (type == 'reserve-disable') {
                var msg = 'Tem certeza que deseja <b>desabilitar</b> a reserva de todos os produtos selecionados?';
            }

            modalAlert(msg, 'CONFIRMAR');

            var modal = $('#modal-alert');

            modal.find('.btn-default').addClass('btn-confirm invert-color');
            modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back' data-dismiss='modal'>VOLTAR</button>");

            modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
                $('#form-product-manager').attr('action', url).submit();
            });
        }
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
                    } else if (data.type == 'delete') {
                        selected.remove();

                        if (form.find('.product').length == 0) {
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 100);
                        }
                    }

                    selected.removeClass('selected').find('input[type=checkbox]').attr('checked', false);
                    form.find('.product').removeClass('prepare-select').find('label').hide();

                    $('header').toggle();
                } else {
                    modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                }
            }
        });

        return false;
    });
});
