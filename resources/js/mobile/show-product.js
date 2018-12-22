$(function() {
    // Image alternate
    $(document).on('click', '.image-thumb', function() {
        $('#image-destaque').find('#photo-zoom').attr('src', $(this).attr('src').replace('_resize', ''));
    });

    // Rating
    $(document).on('change', '#form-rating-product input', function() {
        if(client_logged) {
            $('#form-rating-product').submit();
        } else {
            $(this).prop('checked', false);

            modalAlert('É necessário estar logado para poder avaliar.');
        }
    });
    $(document).on('submit', '#form-rating-product', function() {
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                modalAlert(data.msg);
            }
        });

        return false;
    });

    $(document).on('click', '.btn-product-confirm', function(e) {
        e.preventDefault();

        if (client_logged) {
            if($('.size-container').length && !$('.size-container input[type=checkbox]').is(':checked')) {
                modalAlert('Selecione pelo menos um tamanho para confirmar.');
            } else {
                var sizes = [];
                $('.size-container').find('input[type=checkbox]:checked').each(function() {
                    sizes.push($(this).val());
                });

                $.ajax({
                    url: $(this).data('url'),
                    data: { sizes : sizes, product_id : $(this).data('productid') },
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        modalAlert(data.msg);
                    }
                });
            }
        } else {
            modalAlert('É necessário acessar sua conta para fazer a confirmação de um produto.');
        }
    });

    $(document).on('click', '.btn-product-reserve', function(e) {
        e.preventDefault();

        var url = $(this).data('url'),
            product_id = $(this).data('productid');

        if (client_logged) {
            if($('.size-container').length && !$('.size-container input[type=checkbox]').is(':checked')) {
                modalAlert('Selecione pelo menos um tamanho para reservar.');
            } else {
                modalAlert("Você deseja que este produto seja reservado para você conferir na loja em até 24hs após a confirmação? <br> Você não é obrigado(a) a finalizar a compra na loja!", 'RESERVAR');

                var modal = $('#modal-alert');

                modal.find('.btn-default').addClass('btn-confirm');
                modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back invert-color' data-dismiss='modal'>VOLTAR</button>");

                modal.find('.modal-footer .btn-confirm').off().on('click', function() {
                    var sizes = [];
                    $('.size-container').find('input[type=checkbox]:checked').each(function() {
                        sizes.push($(this).val());
                    });

                    $.ajax({
                        url: url,
                        data: { sizes : sizes, product_id : product_id },
                        method: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (data) {
                            modal.find('.modal-body').html(data.msg);
                            modal.find('.modal-footer .btn-confirm').remove();
                            modal.find('.modal-footer .btn-back').text('OK').off();
                        }
                    });

                    return false;
                });
            }
        } else {
            modalAlert('É necessário acessar sua conta para fazer a reserva de um produto.');
        }
    });

    $(document).on('submit', '#form-question-message', function() {
        var message = $(this).find('textarea').val();

        if(message) {
            if(client_logged) {
                $.ajax({
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    method: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        modalAlert(data.msg);

                        if(data.status) {
                            if (!$('#list-product-messages').length) {
                                $('#product-messages').append("<div id='list-product-messages'></div>");
                            }

                            $('#list-product-messages').prepend("<div class='conversation'><div class='client-message'><h4>" + data.user_name + "</h4><span>HOJE</span><p>" + message + "</p></div></div>");

                            $('#form-question-message').find('textarea').val('');
                        }
                    }
                });
            } else {
                modalAlert('É necessário acessar sua conta para fazer uma pergunta para a loja.');
            }
        }

        return false;
    });
});
