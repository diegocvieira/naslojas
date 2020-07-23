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

Route::post('post/download', 'PostController@create')->name('download-post');

Route::get('/', 'LocationController@index')->name('home');

Route::get('lojas', 'StoreController@index')->name('store.index');

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
});

// Search
Route::get('produtos/busca', 'ProductController@formSearch')->name('form-search');
Route::get('busca/{city}/{state}', 'ProductController@search')->name('search-products');

// Set a new city
Route::get('cidade/set/{id}', 'GlobalController@setCity')->name('set-city');
Route::get('location/set', 'LocationController@set')->name('location.set');

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

	Route::group(['prefix' => 'admin', 'middleware' => 'auth:store'], function () {
		Route::get('config/{navigation?}', 'Admin\StoreController@getConfig')->name('get-store-config');
		Route::post('config', 'Admin\StoreController@setConfig')->name('set-store-config');

		Route::post('profile-status/{status}', 'Admin\StoreController@profileStatus');

		Route::post('delete-account', 'Admin\StoreController@deleteAccount')->name('delete-store-account');

		Route::get('tutoriais/{type}', 'Admin\StoreController@tutorials')->name('tutorials');

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

			// BUSCA
			Route::get('busca', 'OrderController@searchStoreOrders')->name('search-store-orders');

			// Confirm order
			Route::post('confirm/{id}', 'OrderController@confirm')->name('confirm-order');

			// Refuse order
			Route::post('refuse/{id}', 'OrderController@refuse')->name('refuse-order');
		});
	});
});

// Client
Route::group(['prefix' => 'cliente'], function () {
    // Route::get('district/set/{districtId}', 'ClientController@districtSet')->name('client-district-set');

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
		Route::get('config/{navigation?}', 'Admin\ClientController@getConfig')->name('get-client-config');
		Route::post('config', 'Admin\ClientController@setConfig')->name('set-client-config');

		Route::post('delete-account', 'Admin\ClientController@deleteAccount')->name('delete-client-account');

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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('sindilojas', 'SindilojasController@loginIndex')->name('admin.sindilojas.login');
    Route::post('sindilojas', 'SindilojasController@login')->name('admin.sindilojas.login');

    Route::get('sindilojas/lojas/cadastro', 'SindilojasController@storeRegisterIndex')->name('admin.sindilojas.store.register');
    Route::post('sindilojas/lojas/cadastro', 'SindilojasController@storeRegister')->name('admin.sindilojas.store.register');
});
