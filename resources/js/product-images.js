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
            if (actives.length <= 5) {
                actives.addClass('product').removeClass('selected');

                $.each($('.dz-preview.grouped'), function(index, val) {
                    var c = $(this).attr('data-product');

                    if (c >= next_product) {
                        next_product = (parseInt(c) + 1);
                    }
                });

                $('.dz-preview.product').attr('data-product', next_product);
                $('.dz-preview.product .dz-success-mark').text('Grupo ' + next_product);

                actives.addClass('grouped').removeClass('product').attr('title', 'Clique para remover esta imagem do grupo');
            } else {
                modalAlert('Você pode adicionar no máximo 5 imagens por produto.');
            }
        }
    });

    $("#form-images-dropzone").dropzone({
        url: '/loja/admin/produtos/dropzone',
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
        timeout: 0,
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

            if ($('.dz-preview').length == 0) {
                $('.top-images').hide();
            }

            $('.dz-preview').unbind('click');
        	$('.dz-preview').on('click', function(e) {
                if (!$(this).hasClass('dz-error')) {
    	        	if ($(this).hasClass('selected')) {
    	        		$(this).removeClass('selected');
    	        	} else if ($(this).hasClass('grouped')) {
            			$(this).addClass('ungroup');

                        $('.dz-preview.ungroup.grouped').each(function() {
                            var nragrupamento = $(this).attr('data-product');

                            $(this).removeClass('grouped ungroup').removeAttr('data-product title');

                            if ($('.dz-preview[data-product="' + nragrupamento +'"]').length == 1) {
                                $('.dz-preview[data-product="' + nragrupamento +'"]').removeClass('grouped ungroup').removeAttr('data-product title');
                            }
                        });
                    } else {
            			$(this).addClass('selected');
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

                    if ($('.dz-preview').length <= 1) {
                        $('.top-images').hide();
                    }

                    if (!$(file.previewElement).hasClass('dz-error')) {
                        $.ajax({
                            url: '/loja/admin/produtos/delete-images/' + $(file.previewElement).find('img').attr('alt'),
                            method: 'POST',
                            dataType: 'json',
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
