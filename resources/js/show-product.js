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

                if (!modal.hasClass('page-show-product')) {
                    modal.removeClass().addClass('modal fade page-show-product');
                } else {
                    modal.animate({
                        'scrollTop' : 0
                    }, 500);
                }

                modal.find('.modal-content').html(data.body);
                modal.modal('show');

                $('select.selectpicker').selectpicker('refresh');

                showOffTime($('.page-show-product .offtime.timer-generate').attr('data-date'), $('.page-show-product .offtime-timer'));

                // PRODUTOS RELACIONADOS
                $('.list-products .product .offtime').each(function(index, element) {
                    showOffTime($(this).attr('data-date'), $(this));
                });
            }
        });
    });

    $(document).on('change', '.page-show-product select.freights', function(e) {
        e.preventDefault();

        var span = $('.freights-container').find('.freight-selected'),
            val = $(this).val();

        if (val == 0.00) {
            span.addClass('free').text('Frete grátis');
        } else {
            span.removeClass('free').text('Frete R$ ' + number_format(val, 2, ',', '.'));
        }

        $(this).val('').selectpicker('refresh');
    });

    $(document).on('click', '.related-products .pagination a', function(e) {
        e.preventDefault();

        $(this).css('pointer-events', 'none');

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $('.related-products').find('.pagination').remove();

                $('.related-products').find('.list-products').append(data.body);
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
    $(document).on('mouseover', '.image-thumb', function() {
        $('#image-destaque').find('#photo-zoom').attr('src', $(this).attr('src').replace('_resize', ''));
    });

    // Rating
    $(document).on('change', '#form-rating-product input', function(e) {
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
