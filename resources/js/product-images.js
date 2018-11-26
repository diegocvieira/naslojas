$(function() {
    $(document).on('click', '.page-product-images .btn-finish', function(e) {
        e.preventDefault();

        var images = $('.dz-preview.grouped img'),
            form = $('#form-images-dropzone');

        $.each(images, function(index, val) {
            var dz_preview = $(val).parent().parent();

            if (dz_preview.data('product') != undefined && !dz_preview.hasClass('dz-error')) {
                form.append('<input type="hidden" name="images[' + dz_preview.data('product') + '][]" value="' + val.alt + '">');
            }
        });

        var p = 99999,
            images = $('.dz-preview img');

        $.each(images, function(index, val) {
            var dz_preview = $(val).parent().parent();

            if (dz_preview.data('product') == undefined && !dz_preview.hasClass('dz-error')) {
                p = p + 1;

                form.append('<input type="hidden" name="images[' + p + '][]" value="' + val.alt + '">');
            }
        });

        form.submit();
    });

    $(document).on('click', '.page-product-images .btn-agroup', function(e) {
        e.preventDefault();

        var actives = $('.dz-preview.selected'),
            next_product = 1;

        if(actives.length > 1) {
            actives.addClass('product').removeClass('selected');

            $.each($('.dz-preview.grouped'), function(index, val) {
                var c = $(this).attr('data-product');

                if (c >= next_product) {
                    next_product = (parseInt(c) + 1);
                }
            });

            $('.dz-preview.product').attr('data-product', next_product);
            $('.dz-preview.product .dz-success-mark').text('Grupo ' + next_product);

            actives.addClass('grouped').removeClass('product');
        }
    });

    $("#form-images-dropzone").dropzone({
        url: '/loja/produtos/dropzone',
        params: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        paramName: 'image',
        maxFilesize: 5, // MB
        maxFiles: 50,
        parallelUploads: 25,
        addRemoveLinks: true,
        dictFileTooBig: "A imagem não pode ter mais de 5mb",
        dictMaxFilesExceeded: "Máximo de 50 imagens por vez",
        dictRemoveFile: "",
        dictCancelUploadConfirmation: "Quer cancelar o upload?",
        clickable: true,
        thumbnailWidth: 300,
        thumbnailHeight: 300,
        accept: function(file, done) {
            if ((file.type).toLowerCase() != "image/jpg" && (file.type).toLowerCase() != "image/gif" && (file.type).toLowerCase() != "image/jpeg" && (file.type).toLowerCase() != "image/png") {
                done('Formato inválido');
            } else {
                done();
            }
        },
        sending:function(file) {
            $('.btn-finish').val('AGUARDE').attr('disabled', true);
            $('.dz-remove').text('');
        },
        success:function(file, response) {
            setTimeout(function() {
                $('img[alt="' + file.name + '"]').attr('alt', response);

                $('.btn-finish').val('PRÓXIMO').attr('disabled', false);
            }, 700);
        },
        queuecomplete:function(file, done) {
            $('.top-images').show();

            $('.dz-preview').unbind('click');
            $('.dz-preview').on('click', function(e) {
                if(!$(this).hasClass('dz-error')) {
                    if($(this).hasClass('grouped')) {
                        var data_product = $('.dz-preview[data-product=' + $(this).data('product') + ']'),
                            group = data_product.length == 2 ? data_product : $(this);

                        group.removeAttr('data-product').removeClass('grouped');
                    } else {
                        $(this).hasClass('selected') ? $(this).removeClass('selected') : $(this).addClass('selected');
                    }
                }
            });
        },
        removedfile: function(file) {
            var _ref;

            if (file.previewElement) {
                if ((_ref = file.previewElement) != null) {
                    if ($(file.previewElement).hasClass('grouped')) {
                        var data_product = $('.dz-preview[data-product=' + $(file.previewElement).data('product') + ']');

                        if (data_product.length == 2) {
                            data_product.removeAttr('data-product').removeClass('grouped');
                        }
                    }

                    if (!$(file.previewElement).hasClass('dz-error')) {
                        $.ajax({
                            url: 'loja/produtos/delete-images',
                            method: 'POST',
                            dataType: 'json',
                            data: 'image_name=' + $(file.previewElement).find('img').attr('alt'),
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                            }
                        });
                    }

                    _ref.parentNode.removeChild(file.previewElement);
                }
            }

            return this._updateMaxFilesReachedClass();
        }
    });
});
