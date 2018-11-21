function modalAlert(body, btn = 'OK') {
    var modal = $('#modal-alert');

    modal.find('.modal-footer .btn-back').remove();

    modal.find('.modal-body').html(body);
    modal.find('.modal-footer .btn').removeClass('btn-confirm').text(btn);
    modal.find('.modal-footer .btn').text(btn);
    modal.modal('show');

    $('.modal-backdrop:last').css('z-index', '1080');
}

$(function() {
    $('body').css('opacity', '1');

    // Alert app
    $(document).on('click', '.show-app', function (e) {
        e.preventDefault();

        modalAlert('Em breve você poderá baixar o nosso aplicativo para android e ios.');
    });

    // Open cities
    $(document).on('click', '.open-cities', function() {
        modalAlert('Em breve os usuários de outras cidades também poderão utilizar o naslojas.com.');

        //$(this).next().show();
    });

    // Close cities
    /*$(document).click(function(e) {
        if(!$(e.target).closest('.cities').length) {
            $('.cities').find('.drop-down').hide();
        }
    });*/

    // Filters product
    $(document).on('change', '.product-filter select', function() {
        var val = $(this).val();

        $(this).attr('name') == 'order' ? $('#search-order').val(val) : $('#search-gender').val(val);

        $('#form-search').submit();
    });

    // Login store
    $(document).on('submit', '#form-login-store', function() {
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if(data.status) {
                    window.location = '/';
                } else {
                    modalAlert(data.msg);
                }
            }
        });

        return false;
    });

    // Register store
    $('#form-register-store').validate({
        rules: {
            email: {
                required: true,
                minlength: 1,
                maxlength: 100,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if(data.status == true) {
                        window.location = '/';
                    } else {
                        modalAlert(data.msg);
                    }
                }
            });

            return false;
        }
    });

    // Login client
    $(document).on('submit', '#form-login-client', function() {
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if(data.status) {
                    window.location = '/';
                } else {
                    modalAlert(data.msg);
                }
            }
        });

        return false;
    });

    // Register client
    $('#form-register-client').validate({
        rules: {
            name: {
                required: true,
                minlength: 1,
                maxlength: 200
            },
            email: {
                required: true,
                minlength: 1,
                maxlength: 100,
                email: true
            },
            password: {
                required: true,
                minlength: 8
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                equalTo: "#password"
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass(errorClass).removeClass(validClass);
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass(errorClass).addClass(validClass);
        },
        errorPlacement: function(error, element) {
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                method: 'POST',
                dataType: 'json',
                data: $(form).serialize(),
                success: function (data) {
                    if(data.status == true) {
                        window.location = '/';
                    } else {
                        modalAlert(data.msg);
                    }
                }
            });

            return false;
        }
    });

    // Modal how works
    $('.open-how-works').on('click', function(e) {
        e.preventDefault();

        var modal = $('.modal-how-works');

        modal.find('.arrow').data('position', 1);

        modal.find('.next').show();
        modal.find('.prev').hide();

        modal.find('.advance').removeClass('active');
        modal.find('.advance[data-position=1]').addClass('active');

        modal.modal('show');
    });
    // Passar imagens do modal nas flechas do teclado
    $('.modal-how-works').on('keydown', function(e) {
        var modal = $('.modal-how-works'),
            position = parseInt(modal.find('.position .active').data('position'));

        if(e.which == 39 && position < 6) {
            modal.find('.next').trigger('click'); // right
        } else if(e.which == 37 && position > 1) {
            modal.find('.prev').trigger('click'); // left
        }
    });
    $('.modal-how-works').on('click', '.arrow, .advance', function(e) {
        e.preventDefault();

        var modal = $('.modal-how-works'),
            position = parseInt($(this).data('position'));

        // Verifica se o click foi nas flechas
        if($(this).hasClass('arrow')) {
            // Faz o calculo para next ou prev
            position = $(this).hasClass('next') ? position + 1 : position - 1;
        }

        // Adiciona a imagem
        modal.find('img').attr('src', '/images/how-works-desktop/' + position + '.png');

        // Atualiza a posicao da imagem na flecha
        modal.find('.arrow').data('position', position);
        // Atualiza a class active nos circulos
        modal.find('.advance').removeClass('active');
        modal.find('.advance[data-position=' + position + ']').addClass('active');

        // Oculta a flecha next se estiver na ultima imagem
        position == 6 ? modal.find('.next').hide() : modal.find('.next').show();
        // Oculta a flecha prev se estiver na primeira imagem
        position == 1 ? modal.find('.prev').hide() : modal.find('.prev').show();
    });



























    $(document).on('click', '.show-client-config', function(e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('href'),
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                $('#modal-default').addClass('page-client-config').find('.modal-content').html(data.body);
                $('#modal-default').modal('show');

                $('#form-client-config').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 1,
                            maxlength: 200
                        },
                        email: {
                            required: true,
                            minlength: 1,
                            maxlength: 100,
                            email: true
                        },
                        password: {
                            minlength: 8
                        },
                        password_confirmation: {
                            minlength: 8,
                            equalTo: "#password"
                        }
                    },
                    highlight: function (element, errorClass, validClass) {
                        $(element).addClass(errorClass).removeClass(validClass);
                    },
                    unhighlight: function (element, errorClass, validClass) {
                        $(element).removeClass(errorClass).addClass(validClass);
                    },
                    errorPlacement: function(error, element) {
                    },
                    submitHandler: function(form) {
                        var modal = $('#modal-alert');

                        modalAlert("Confirme sua senha atual.<input type='password' name='current_password' placeholder='digite aqui' />", 'ENVIAR');

                        modal.find('.btn').addClass('btn-confirm');

                        modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
                            $(form).find('input[name=current_password]').val(modal.find('input[name=current_password]').val());

                            $.ajax({
                                url: $(form).attr('action'),
                                method: 'POST',
                                dataType: 'json',
                                data: $(form).serialize(),
                                success: function (data) {
                                    modal.find('.modal-footer .invalid-field').remove();

                                    if(data.status == '0' || data.status == '1') {
                                        modal.find('.modal-body').html(data.msg);
                                        modal.find('.modal-footer .btn-confirm').removeClass('btn-confirm').text('OK');

                                        modal.find('.modal-footer .btn').unbind().on('click', function() {
                                            return true;
                                        });
                                    }

                                    if(data.status == '1') {
                                        $(form).find('input[type=password]').val('');
                                    }

                                    if(data.status == '2') {
                                        modal.find('.modal-footer').prepend("<span class='invalid-field'>Senha inválida</span>");
                                    }
                                }
                            });

                            return false;
                        });
                    }
                });
            }
        });
    });

    // Delete client account
    $(document).on('click', '#delete-client-account', function(e) {
        e.preventDefault();

        var modal = $('#modal-alert');

        modalAlert("Tem certeza que deseja deletar sua conta? <br> Você perderá todos os dados do seu perfil e este processo não poderá ser desfeito.<input type='password' name='current_password' placeholder='confirme aqui a sua senha atual' />", 'DELETAR');

        modal.find('.btn-default').addClass('btn-confirm invert-color');

        modal.find('.modal-footer').prepend("<button type='button' class='btn btn-back' data-dismiss='modal'>VOLTAR</button>");
        modal.find('.modal-footer .invalid-field').remove();

        modal.find('.modal-footer .btn-confirm').unbind().on('click', function() {
            $.ajax({
                url: $('#delete-client-account').attr('href'),
                method: 'POST',
                dataType: 'json',
                data: 'password=' + modal.find('input[name=current_password]').val(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if(data.status) {
                        window.location = '/';
                    } else {
                        modal.find('.modal-footer .invalid-field').remove();
                        modal.find('.modal-footer').prepend("<span class='invalid-field'>Senha inválida</span>");
                    }
                }
            });

            return false;
        });
    });



















































































































    /*$('a.finalizar').on('click',function(e){
    	e.preventDefault();
    	var images = $('.dz-preview.agrupados img');

    	$.each(images, function(index, val) {
    		if($(val).parent().parent().data('produto') != undefined){
    			var p = $(val).parent().parent().data('produto');
    			$('.form-dropzone').append('<input type="hidden" name="images['+p+'][]" value="'+val.alt+'">');
    		}
    	});

        var p = 99999;
    	var images = $('.dz-preview img');
    	$.each(images, function(index, val) {
    		if($(val).parent().parent().data('produto')== undefined){
        		p = p +1;
        		$('.form-dropzone').append('<input type="hidden" name="images['+p+'][]" value="'+val.alt+'">');
    		}
    	});
    	$('form.form-dropzone').submit();
    });
   	$('a.desagrupar').on('click',function(e){
   		e.preventDefault();

 		$('.dz-preview.desagrupar.agrupados').each(function(){
            var nragrupamento = $(this).attr('data-produto');
            $(this).removeClass('agrupados desagrupar').removeAttr('data-produto');

            if($('.dz-preview[data-produto="' + nragrupamento +'"]').length == 1){
                $('.dz-preview[data-produto="' + nragrupamento +'"]').removeClass('agrupados desagrupar').removeAttr('data-produto');
            }
        });

        //.removeClass('agrupados desagrupar');


		$('a.desagrupar').hide();
   	});

    $('a.agrupar').on('click',function(e){
    	e.preventDefault();

    	var ativos 		= $('.dz-preview.active');
    	ativos.addClass('produto').removeClass('active');

    	var	produtos 	= $('.dz-preview.agrupados');
        var proximoproduto = 1;
    	$.each(produtos, function(index, val) {
    		var c = $(this).attr('data-produto');
            if(c >= proximoproduto){
                proximoproduto = (parseInt(c)+1);
            }
    	});
    	$('.dz-preview.produto').attr('data-produto',proximoproduto);
    	$('.dz-preview.produto .dz-error-mark').text(proximoproduto);
    	ativos.addClass('agrupados').removeClass('produto');
        $('a.agrupar').hide();
    });

    var forms = $('form.produtos-form');
		$.each(forms, function(index, val) {
			$.validator.setDefaults({ ignore: ":hidden:not(.chosen-select)" });

            $(val).validate({

		        rules: {
		            titulo: {
		                required: true,
		                minlength: 2
		            },
		            categoria: {
		                required: true,
		                minlength: 1,
                        min: 1
		            },
		            genero: {
		                required: true,
		                minlength: 1
		            },
		            preco: {
		                required: true,
		                minlength: 2
		            }

		        },
		        onclick: false,
		        onkeyup: false,
		        onfocusout: false,
				highlight: function (element, errorClass, validClass) {
                    $(element).addClass(errorClass).removeClass(validClass);

                },
                unhighlight: function (element, errorClass, validClass) {
					$(element).removeClass(errorClass).addClass(validClass);
					if($(element).hasClass('chosen-select')) {
		                var id = $(element).attr("id");
		                $("#"+id+"_chosen").removeClass("error");
		            }
				},
		        errorPlacement: function(error, element) {
		            if(element.hasClass('chosen-select')) {
		                var id = element.attr("id");
		                $("#"+id+"_chosen").addClass("error");
		            }
		        },
		        submitHandler: function(form) {
		        	console.log('aaaaa');
		            var dados = $(form).serialize();
            		var url = $(form).attr('action');
		            $.ajax({
		                url: url,
		                type: 'POST',
		                data: dados,
		                dataType: 'json',
		                success: function(data) {
		                    var modal = $('#myModal');
								modal.find('.modal-title').text('PRODUTOS');
								var nome = $(form).find('input#titulo').val();
								modal.find('.modal-body .col-md-12').text("PRODUTO "+nome+" CADASTRADO COM SUCESSO");
								$('#myModal').modal('show');
		                }

		            });
		        }

		    });
		});
        $('.btn.adicionar').on('click',function(e){
        	e.preventDefault();
            $.each(forms, function(index, val) {
                if ($(val).valid()) {
                    var dados = $(val).serialize();
                    var url = $(val).attr('action');
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: dados,
                        dataType: 'json',
                        success: function(data) {
                            var modal = $('#myModal');
                            if(data.status === 3){
                                modal.find('.modal-title').text('Limite de produtos');
                                modal.find('.modal-body .col-md-12').text(data.msg);
                                $('#myModal').modal('show');
                            } else if(data.status === 0){
                                window.location = base_url +'/admin/lojas/produtos/edit';
                            } else {
                                    modal.find('.modal-title').text('PRODUTOS');
                                    var nome = $(val).find('input#titulo').val();
                                    modal.find('.modal-body .col-md-12').text("PRODUTOS CADASTRADOS COM SUCESSO");

                                    $(val).remove();

                                    $('#myModal').modal('show');
                            }

                        }

                    });
                }

            });
        });*/




    $("#my-awesome-dropzone").dropzone({
        url: "/loja/dropzone/upload-images",
        params: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        //paramName: "file",
        maxFilesize: 2, // MB
        maxFiles: 25,
        parallelUploads: 25,
        addRemoveLinks: true,
        dictMaxFilesExceeded: "You can only upload upto 5 images",
        dictRemoveFile: "Delete",
        dictCancelUploadConfirmation: "Are you sure to cancel upload?",
        clickable: true,
        thumbnailWidth: 300,
        thumbnailHeight: 300,
        accept: function (file, done) {
            if ((file.type).toLowerCase() != "image/jpg" && (file.type).toLowerCase() != "image/gif" && (file.type).toLowerCase() != "image/jpeg" && (file.type).toLowerCase() != "image/png") {
                done("Invalid file");
            } else {
                done();
            }
        },
        sending:function (file) {
            $('.btn.finalizar').text('AGUARDE').addClass('disabled');
        },
        success:function (file, response) {
            console.log('file',file.name,$('.dz-preview img[alt="'+file.name+'"]'));

            setTimeout(function() {
                $('img[alt="'+file.name+'"]').attr('alt', response);

                $('.btn.finalizar').text('PRÓXIMO').removeClass('disabled');
            }, 700);
        },
        queuecomplete:function(file, done) {
            if ($('.dz-preview').length > 1) {
                $('.box-images h4').text($('.dz-preview').length+' imagens carregadas');

            } else if($('.dz-preview').length == 1) {
                $('.box-images h4').text('1 imagem carregada');
            } else {
                $('.box-images h4').text('');
            }

            $('.box-images').show();

            $( ".dz-preview").unbind( "click" );

            $('.dz-preview').on('click', function(e) {
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active');

                    if ($('.dz-preview.active').length <= 1) {
                        $('a.agrupar').hide();
                    }
                } else {
                    if ($(this).hasClass('desagrupar')) {
                        $(this).removeClass('desagrupar');
                    } else if ($(this).hasClass('agrupados')) {
                        $(this).addClass('desagrupar');

                        $('a.desagrupar').show();
                    } else {
                        $(this).addClass('active');

                        if ($('.dz-preview.active').length > 1) {
                            $('a.agrupar').show();
                        }
                    }
                }
            });
        },
        removedfile: function(file) {
            var _ref;

            if (file.previewElement) {
                if ((_ref = file.previewElement) != null) {
                    _ref.parentNode.removeChild(file.previewElement);
                }
            }

            if ($('.dz-preview').length > 1) {
                $('.box-images h4').text($('.dz-preview').length+' imagens carregadas');
            } else if ($('.dz-preview').length == 1) {
                $('.box-images h4').text('1 imagem carregada');
            } else {
                $('.box-images h4').text('');
            }

            return this._updateMaxFilesReachedClass();
        }
    });













































});
