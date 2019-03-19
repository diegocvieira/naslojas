$(function() {
    // Image alternate
    $(document).on('click', '.image-thumb', function() {
        $('#image-destaque').find('#photo-zoom').attr('src', $(this).attr('src').replace('_resize', ''));
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
