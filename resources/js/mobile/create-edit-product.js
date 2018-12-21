$(function() {
    $('.mask-money').mask('000.000.000.000.000,00', {reverse: true});
    $('.mask-percent').mask('00%', { reverse: true, clearIfNotMatch : true });
    $('.mask-x').mask('00x', { reverse: true, clearIfNotMatch : true });

    $('#form-create-edit-product').on('blur', 'input[name=old_price], input[name=price]', function(e) {
        var form = $(this).parents('#form-create-edit-product'),
            price = form.find('input[name=price]').val(),
            old_price = form.find('input[name=old_price]').val();

        if (price && old_price) {
            var off = (Math.round((price.replace('.', '').replace(',', '.') / old_price.replace('.', '').replace(',', '.') - 1) * 100)).toString().replace('-', '');

            form.find('input[name=discount]').val($.isNumeric(off) ? off + '%' : '');
        }
    });

    $(document).on('click', '.page-create-edit-product .btn-add-image, .page-create-edit-product .remove-image', function(e) {
        e.preventDefault();

        $(this).next().trigger('click');
    });

    $(document).on('click', '.page-create-edit-product .add-color-variation', function(e) {
        e.preventDefault();

        $('.images').find('.color-variation:last').after($('.color-variation:last').clone());

        var last = $('.images').find('.color-variation:last'),
            related = $('#form-create-edit-product').find('input[name=related]');

        last.find('.image').removeClass('loaded-image').addClass('no-image').find('input:file').val('');
        last.find('input[name=image_position], input[name=image_remove]').remove();

        if (!related.val()) {
            related.val(Math.round((new Date()).getTime()));
        }
    });

    // Remove images
    $(document).on('click', '.page-create-edit-product .remove-image', function() {
        var image_container = $(this).parent();

        image_container.find('input[name=image_remove]').attr('checked', true);
        image_container.find('.remove-image').remove();
        image_container.addClass('no-image').removeClass('loaded-image').find('input[type=file]').val('');
    });

    // Preview images
    $(document).on('change', '.page-create-edit-product .image input:file', function() {
        var reader = new FileReader(),
            $this = $(this);

        if($(this)[0].files[0].size > 5100000) {
            modalAlert('A imagem tem que ter no máximo 5mb.');
        } else {
            reader.onload = function(e) {
                $this.parent().removeClass('no-image').addClass('loaded-image').append("<label class='remove-image'></label>").find('img').attr('src', e.target.result);
            }

            reader.readAsDataURL($(this)[0].files[0]);
        }
    });

    $(document).on('click', '.page-create-edit-product .header .btn-option', function(e) {
        e.preventDefault();

        if ($(this).hasClass('enable')) {
            var msg = 'Tem certeza que deseja <b>mostrar</b> este produto?';
        } else if ($(this).hasClass('disable')) {
            var msg = 'Tem certeza que deseja <b>ocultar</b> este produto?';
        } else {
            var msg = 'Tem certeza que deseja <b>apagar</b> este produto?';
        }

        modalAlert(msg, 'CONFIRMAR');

        var modal = $('#modal-alert'),
            url = $(this).data('url'),
            product_id = $(this).data('productid');

        modal.find('.btn-default').addClass('btn-confirm invert-color');
        modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back' data-dismiss='modal'>VOLTAR</button>");

        modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'json',
                data: { id : product_id },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status) {
                        if (data.type == 'delete') {
                            window.location = '/loja/admin/produtos';
                        } else {
                            $('.page-create-edit-product .header').find('.btn-option.enable, .btn-option.disable').toggleClass('hidden');
                        }
                    } else {
                        modalAlert('Ocorreu um erro inesperado. Atualize a página e tente novamente.');
                    }
                }
            });
        });
    });

    $('#form-create-edit-product').validate({
        rules: {
            title: {
                required: true,
                minlength: 1,
                maxlength: 255
            },
            price: {
                required: true,
                minlength: 1
            },
            gender: {
                required: true,
                minlength: 1,
                min: 1
            },
            description: {
                maxlength: 2000
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);

            if ($(element).hasClass('selectpicker')) {
                $(element).prev().prev().addClass('error');
            }
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);

            if ($(element).hasClass('selectpicker')) {
                $(element).prev().prev().removeClass('error');
            }
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            $(form).find('input[type=submit]').val('SALVANDO').attr('disabled', true);

            var data = new FormData(),
                images = true;

            $('.color-variation').each(function(index) {
                if ($(this).find('.image.loaded-image').length >= 1) {
                    $(form).find('.json').each(function() {
                        data.append('products[' + index + '][' + $(this).attr('name') + ']', $(this).val());
                    });

                    $(form).find("input[name='size[]']:checked").each(function() {
                        data.append('products[' + index + '][sizes][]', $(this).val());
                    });

                    $(form).find("input[name='image_remove[]']:checked").each(function() {
                        data.append('products[' + index + '][images_remove][]', $(this).val());
                    });

                    $(this).find('input:file').each(function(index2, element) {
                        if (element.files[0]) {
                            data.append('products[' + index + '][images][]', element.files[0]);
                            data.append('products[' + index + '][images_position][]', $(this).data('position'));
                        }
                    });
                } else {
                    images = false;
                }
            });

            if (images == true) {
                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    dataType: 'json',
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        $(form).find('input[type=submit]').val('SALVAR').attr('disabled', false);

                        if (data.status) {
                            window.location = '/loja/admin/produtos';
                        } else {
                            modalAlert(data.msg);
                        }
                    }
                });
            } else {
                $(form).find('input[type=submit]').val('SALVAR').attr('disabled', false);

                modalAlert('Cada variação de cor precisa ter no mínimo uma imagem.');
            }

            return false;
        }
    });
});
