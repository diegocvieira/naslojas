$(function() {

    $(document).on('click', '.show-product', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            cache: false,
            success: function (data) {
                document.title = data.header_title;
                window.history.pushState('', data.header_title, data.url);

                var modal = $('#modal-default');

                if(!modal.hasClass('page-show-product')) {
                    modal.removeClass().addClass('modal fade page-show-product');
                }

                modal.find('.modal-content').html(data.body);
                modal.modal('show');
            }
        });
    });

    $(document).on('mouseover', '#image-destaque', function() {
        $(this).children('#photo-zoom').css('transform', 'scale(1.5)');
    });
    $(document).on('mouseout', '#image-destaque', function() {
        $(this).children('#photo-zoom').css('transform', 'scale(1)');
    });
    $(document).on('mousemove', '#image-destaque', function(e) {
        $(this).children('#photo-zoom').css('transform-origin', ((e.pageX - $(this).offset().left) / $(this).width()) * 100 + '% ' + ((e.pageY - $(this).offset().top) / $(this).height()) * 100 + '%');
    });
    $(document).on('click', '.image-thumb', function() {
        var destaque_src = $('#image-destaque').find('#photo-zoom').attr('src'),
            thumb_src = $(this).attr('src').replace('_resize', '');

        $('#image-destaque').find('#photo-zoom').attr('src', thumb_src),
        $(this).attr('src', destaque_src);
    });

    // Rating
    $(document).on('change', '#form-rating-product input', function() {
        if(client_logged) {
            $('#form-rating-product').submit();
        } else {
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

    $(document).on('click', '.btn-product-confirm, .btn-product-reserve', function(e) {
        e.preventDefault();

        var btn_confirm = $(this).hasClass('btn-product-confirm') ? true : false,
            url = $(this).data('url'),
            product_id = $(this).data('productid');

        if(client_logged) {
            if($('.size-container').length && !$('.size-container input[type=checkbox]').is(':checked')) {
                if(btn_confirm) {
                    modalAlert('Selecione pelo menos um tamanho para confirmar.');
                } else {
                    modalAlert('Selecione pelo menos um tamanho para reservar.');
                }
            } else {
                var modal = $('#modal-alert');

                if(btn_confirm) {
                    modalAlert("Você deseja que este produto seja reservado para você conferir na loja em até 24hs após a confirmação? <br> Você não é obrigado(a) a finalizar a compra na loja!", 'RESERVAR');
                } else {
                    modalAlert("Você deseja que a loja confirme se o produto ainda está à venda? Assim você pode passar na loja para experimentar. <br> Você receberá a confirmação por e-mail.", 'CONFIRMAR');
                }

                modal.find('.btn-default').addClass('btn-confirm');
                modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back invert-color' data-dismiss='modal'>VOLTAR</button>");

                modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
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
                            modalAlert(data.msg);

                            $('#modal-alert').find('.modal-footer .btn').unbind();
                        }
                    });

                    return false;
                });
            }
        } else {
            if(btn_confirm) {
                modalAlert('É necessário acessar sua conta para fazer a confirmação de um produto.');
            } else {
                modalAlert('É necessário acessar sua conta para fazer a reserva de um produto.');
            }
        }
    });

    $(document).on('keyup', '#form-question-message textarea', function() {
        $('.message-counter').text(300 - $(this).val().length);
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
