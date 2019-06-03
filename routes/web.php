<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('images/thumb', function () {
	$images = \App\ProductImage::whereHas('product', function ($q) {
			$q->withoutGlobalScopes(['active', 'active-store']);
		})
		->with(['product' => function($query) {
                $query->withoutGlobalScopes(['active', 'active-store']);
        }])
		->orderBy('id', 'ASC')
		->offset(0)->limit(500)
		->get();

	$store_id = 18;

	foreach ($images as $img) {
		$store_id = $img->product->store_id;

		if (file_exists(public_path('uploads/' . $store_id . '/products/' . _originalImage($img->image)))) {
			$image = new \Imagick(public_path('uploads/' . $store_id . '/products/' . _originalImage($img->image)));

			if ($image->getImageAlphaChannel()) {
				$image->setImageAlphaChannel(11);
			}

			$image->setImageBackgroundColor('#ffffff');
			$image->setColorspace(\Imagick::COLORSPACE_SRGB);
			$image->setImageFormat('jpg');
			$image->stripImage();
			$image->setImageCompressionQuality(100);
			$image->setSamplingFactors(array('2x2', '1x1', '1x1'));
			$image->setInterlaceScheme(\Imagick::INTERLACE_JPEG);
			$image->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);

			$image->resizeImage('248', '248', \imagick::FILTER_LANCZOS, 1, TRUE);

			switch ($image->getImageOrientation()) {
				case \Imagick::ORIENTATION_TOPLEFT:
					break;
				case \Imagick::ORIENTATION_TOPRIGHT:
					$image->flopImage();
					break;
				case \Imagick::ORIENTATION_BOTTOMRIGHT:
					$image->rotateImage("#fff", 180);
					break;
				case \Imagick::ORIENTATION_BOTTOMLEFT:
					$image->flopImage();
					$image->rotateImage("#fff", 180);
					break;
				case \Imagick::ORIENTATION_LEFTTOP:
					$image->flopImage();
					$image->rotateImage("#fff", -90);
					break;
				case \Imagick::ORIENTATION_RIGHTTOP:
					$image->rotateImage("#fff", 90);
					break;
				case \Imagick::ORIENTATION_RIGHTBOTTOM:
					$image->flopImage();
					$image->rotateImage("#fff", 90);
					break;
				case \Imagick::ORIENTATION_LEFTBOTTOM:
					$image->rotateImage("#fff", -90);
					break;
				default: // Invalid orientation
					break;
			}

			$image->setImageOrientation(\Imagick::ORIENTATION_TOPLEFT);

			$image->writeImage(public_path('uploads/' . $store_id . '/products/' . $img->image));

			$image->destroy();
		}
	}
});*/

Route::get('/', 'GlobalController@home')->name('home');

Route::group(['prefix' => 'site'], function () {
	Route::post('newsletter/register', 'NewsletterController@register')->name('newsletter-register');

	Route::get('regras', function() {
		if (Agent::isDesktop()) {
			return view('rules');
		} else {
			return view('mobile.rules');
		}
	})->name('rules');

	Route::get('termos-de-uso', function () {
		if (Agent::isDesktop()) {
			return view('terms-use');
		} else {
			return view('mobile.terms-use');
		}
	})->name('terms-use');

	Route::get('politica-de-privacidade', function () {
		if (Agent::isDesktop()) {
			return view('privacy-policy');
		} else {
			return view('mobile.privacy-policy');
		}
	})->name('privacy-policy');

	Route::get('como-funciona', function () {
		if (Agent::isDesktop()) {
			session()->flash('session_flash_how_works', 'true');

        	return redirect()->route('home');
		} else {
			$section = 'how-works';

			return view('mobile.how-works', compact('section'));
		}
	})->name('how-works');
});

// Search
Route::get('produtos/busca', 'ProductController@formSearch')->name('form-search');
Route::get('busca/{city}/{state}', 'ProductController@search')->name('search-products');

// Set a new city
Route::get('cidade/set/{id}', 'GlobalController@setCity')->name('set-city');

// Store page
Route::get('{slug}', 'StoreController@show')->name('show-store');

// Product Page
Route::get('produto/{slug}', 'ProductController@show')->name('show-product');
Route::get('related-products/{product}/{pagination?}', 'ProductController@relatedProducts')->name('related-products');

// Search store products
Route::get('loja/produtos/busca', 'StoreController@formSearch')->name('form-search-store');
Route::get('{store}/busca', 'StoreController@search')->name('search-store-products');

Route::group(['prefix' => 'recuperar-senha'], function () {
	Route::post('request', 'PasswordResetController@request');

	Route::get('check/{token}', 'PasswordResetController@check')->name('password-check');

	Route::post('change', 'PasswordResetController@change')->name('password-change');
});

// Store
Route::group(['prefix' => 'loja'], function () {
	Route::get('divulgar', function() {
		if (Agent::isDesktop()) {
			return view('store.register-advertise');
		} else {
			return view('mobile.store.register-advertise');
		}
	})->name('store-advertise');

	Route::get('login', function () {
		if (Agent::isDesktop()) {
			return view('store.login');
		} else {
			return view('mobile.store.login');
		}
	})->name('store-login-get');
	Route::post('login', 'StoreController@login')->name('store-login-post');

	Route::get('cadastro', function () {
		if (Agent::isDesktop()) {
			return view('store.register');
		} else {
			return view('mobile.store.register');
		}
	})->name('store-register-get');
	Route::post('cadastro', 'StoreController@register')->name('store-register-post');

	// Confirm/refuse product confirmations from email
	Route::get('confirmacoes/{type}/{token}', 'ProductConfirmController@emailUrl')->name('product-confirm-email-url');
	// Confirm/refuse product reserves from email
	Route::get('reservas/{type}/{token}', 'ProductReserveController@emailUrl')->name('product-reserve-email-url');

	Route::group(['prefix' => 'admin', 'middleware' => 'auth-store-superadmin'], function () {
		Route::get('config/{navigation?}', 'StoreController@getConfig')->name('get-store-config');
		Route::post('config', 'StoreController@setConfig')->name('set-store-config');

		Route::post('profile-status/{status}', 'StoreController@profileStatus');

		Route::post('delete-account', 'StoreController@deleteAccount')->name('delete-store-account');

		Route::get('tutoriais/{type}', 'StoreController@tutorials')->name('tutorials');

		Route::group(['prefix' => 'produtos'], function () {
			Route::get('cadastro', 'ProductController@images')->name('product-images');
			Route::post('dropzone', 'ProductController@uploadImages');
			Route::get('cadastrar-editar/{id?}', 'ProductController@getCreateEdit')->name('get-create-edit-product');
			Route::post('save/{id?}', 'ProductController@save')->name('save-products');

			Route::get('/', 'ProductController@edit')->name('edit-products');

			Route::get('busca', 'ProductController@formSearchAdmin')->name('form-search-admin');
			Route::get('busca/{keyword?}', 'ProductController@edit');

			Route::post('disable', 'ProductController@disable')->name('product-disable');
			Route::post('enable', 'ProductController@enable')->name('product-enable');

			Route::post('color-variation', 'ProductController@colorVariation')->name('color-variation');

			Route::post('delete', 'ProductController@delete')->name('product-delete');

			Route::post('delete-images/{image}', 'ProductController@deleteImages');

			Route::post('free-freight', 'ProductController@freeFreight')->name('product-free-freight');

			Route::post('offtime/create', 'OffTimeController@create')->name('offtime-create');
			Route::post('offtime/remove', 'OffTimeController@remove')->name('offtime-remove');

			Route::post('save-excel', 'ProductController@saveExcel')->name('save-excel');
		});

		Route::group(['prefix' => 'mensagens'], function () {
			// List messages
			Route::get('/', 'MessageController@listStoreMessages')->name('list-store-messages');

			// Create message
			Route::post('create', 'MessageController@createStoreMessage');
		});

		Route::group(['prefix' => 'pedidos'], function () {
			// List orders
			Route::get('/', 'OrderController@storeOrders')->name('list-store-orders');

			// Confirm order
			Route::post('confirm/{id}', 'OrderController@confirm')->name('confirm-order');

			// Refuse order
			Route::post('refuse/{id}', 'OrderController@refuse')->name('refuse-order');
		});
	});
});

// Client
Route::group(['prefix' => 'cliente'], function () {
	Route::get('login', function () {
		if (Agent::isDesktop()) {
			return view('client.login');
		} else {
			$section = 'client-login';

			return view('mobile.client.login', compact('section'));
		}
	})->name('client-login-get');
	Route::post('login', 'ClientController@login')->name('client-login-post');

	Route::get('cadastro', function () {
		if (Agent::isDesktop()) {
			return view('client.register');
		} else {
			$section = 'client-register';

			return view('mobile.client.register', compact('section'));
		}
	})->name('client-register-get');
	Route::post('cadastro', 'ClientController@register')->name('client-register-post');

	Route::group(['prefix' => 'admin', 'middleware' => 'auth:client'], function () {
		Route::get('config/{navigation?}', 'ClientController@getConfig')->name('get-client-config');
		Route::post('config', 'ClientController@setConfig')->name('set-client-config');

		Route::post('delete-account', 'ClientController@deleteAccount')->name('delete-client-account');

		// Rate product
		Route::post('product/rating', 'ProductController@rating')->name('rating-product');

		Route::get('pedidos', 'OrderController@clientOrders')->name('list-client-orders');

		Route::group(['prefix' => 'mensagens'], function () {
			// List messages
			Route::get('/', 'MessageController@listClientMessages')->name('list-client-messages');

			// Create message
			Route::post('create', 'MessageController@createClientMessage')->name('create-client-message');
		});
	});
});

// Superadmin
Route::group(['prefix' => 'superadmin'], function () {
	Route::get('login', function () {
		if (Agent::isDesktop()) {
			return view('superadmin.login');
		} else {
			return view('mobile.superadmin.login');
		}
	})->name('superadmin-login');
	Route::post('login', 'SuperadminController@login')->name('superadmin-login');

	Route::group(['middleware' => 'auth:superadmin'], function () {
		Route::get('loja/cadastro', function () {
			if (Agent::isDesktop()) {
				return view('superadmin.store-register');
			} else {
				return view('mobile.superadmin.store-register');
			}
		})->name('superadmin-store-register');
		Route::post('loja/cadastro', 'SuperadminController@storeRegister')->name('superadmin-store-register');

		Route::get('inicio', function () {
			if (Agent::isDesktop()) {
				return view('superadmin.index');
			} else {
				return view('mobile.superadmin.index');
			}
		})->name('superadmin-index');

		Route::get('set-store/{id}', 'SuperadminController@setStore')->name('superadmin-set-store');
	});
});

// User logout
Route::get('user/logout', 'GlobalController@logout')->name('logout');

Route::group(['prefix' => 'sacola'], function () {
	Route::post('add-product', 'BagController@add')->name('bag-add-product');

	Route::get('remove-product/{product_id}', 'BagController@remove')->name('bag-remove-product');

	Route::get('change-qtd/{product_id}/{qtd}', 'BagController@changeQtd')->name('bag-change-qtd');

	Route::get('change-size/{product_id}/{size}', 'BagController@changeSize')->name('bag-change-size');

	Route::get('produtos', 'BagController@products')->name('bag-products');

	Route::group(['middleware' => 'auth:client'], function () {
		Route::get('dados', 'BagController@data')->name('bag-data');

		Route::get('change-district/{id}', 'BagController@changeDistrict')->name('bag-change-district');

		Route::post('finish', 'BagController@finish')->name('bag-finish');

		Route::get('sucesso/{id}', 'BagController@success')->name('bag-success');
	});
});
