$(function() {
    if ($('.page-admin-products').length) {
        //Desactivate android options on press
        window.oncontextmenu = function(event) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        };

        variation();
    }

    // Load more results
    $(document).on('click', '.page-admin-products .pagination a', function(e) {
        e.preventDefault();

        $(this).css('pointer-events', 'none');

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                $('.page-admin-products').find('.pagination').remove();
                $('.page-admin-products').find('#form-product-manager').append(data.products);

                variation();
            }
        });
    });

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
        } else if (type == 'variation-generate' || type == 'variation-remove') {
            var variation_value = type == 'variation-generate' ? Math.round((new Date()).getTime()) : null,
                selected = $('#form-product-manager').find('.product.selected');
                ids = [];

            if (selected.length < 2 && type == 'variation-generate') {
                modalAlert('Selecione dois ou mais produtos para agrupar.');
            } else if (selected.length < 1 && type == 'variation-remove') {
                modalAlert('Selecione pelo menos um produto para desagrupar.');
            } else {
                selected.each(function(index) {
                    var val = $(this).attr('data-related');

                    $(this).find('.variation-horizontal, .variation-vertical, .variation-diagonal').remove();

                    if (type == 'variation-generate') {
                        $(this).addClass('product-variation').attr('data-related', variation_value);
                    } else {
                        $(this).removeClass('product-variation').removeAttr('data-related');
                    }

                    var related = $(".product[data-related='" + val + "']");

                    if (related.length == 1) {
                        related.removeClass('product-variation').removeAttr('data-related').find('.variation-horizontal, .variation-vertical, .variation-diagonal').remove();
                    }

                    if (index != 0 && type == 'variation-generate') {
                        $(".product[data-related='" + variation_value + "']").first().after($(this)[0]);
                    }

                    ids.push($(this).find('input[type=checkbox]').val());
                });

                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    data: { ids : ids, variation : variation_value },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if (data.status) {
                            selected.removeClass('selected').find('input[type=checkbox]').attr('checked', false);
                            $('#form-product-manager').find('.product').removeClass('prepare-select').find('label').hide();

                            $('header').toggle();

                            variation();
                        } else {
                            modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                        }
                    }
                });
            }
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

function variation() {
    $('.product-variation').each(function() {
        var related = $(".product-variation[data-related='" + $(this).attr('data-related') + "']");

        if ($(this).index() % 2 != 0 && $(this)[0] != related.last()[0]) {
            $(this).prepend("<span class='variation-horizontal'></span>");
        }

        if ($(this)[0] != related.last()[0] && $(this).next().next().attr('data-related') == $(this).attr('data-related')) {
            $(this).prepend("<span class='variation-vertical'></span>");
        }

        if (related.first().index() == $(this).index() && related.first().index() % 2 == 0 && $(this).next().next().attr('data-related') != $(this).attr('data-related')) {
            $(this).prepend("<span class='variation-diagonal'></span>");
        }

        if (related.length == 1) {
            $(this).find('.variation-horizontal, .variation-vertical, .variation-diagonal').remove();
        }
    });
}
