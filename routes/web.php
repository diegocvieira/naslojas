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

Route::get('/', 'GlobalController@home')->name('home');

Route::get('site/regras', function() {
	if (Agent::isDesktop()) {
		return view('rules');
	} else {
		//return view('mobile.pagina-regras');
	}
})->name('rules');

Route::get('site/termos-de-uso', function () {
	if (Agent::isDesktop()) {
		return view('terms-use');
	} else {
		//return view('mobile.pagina-termos');
	}
})->name('terms-use');

Route::get('site/politica-de-privacidade', function () {
	if (Agent::isDesktop()) {
		return view('privacy-policy');
	} else {
		//return view('mobile.pagina-privacidade');
	}
})->name('privacy-policy');

// Search
Route::get('produtos/busca', 'ProductController@formSearch')->name('form-search');
Route::get('busca/{city}/{state}/{gender}/{order?}/{keyword?}', 'ProductController@search');

// Set a new city
Route::get('cidade/set/{id}', 'GlobalController@setCity')->name('set-city');

// Store page
Route::get('{slug}', 'StoreController@show')->name('show-store');

// Product Page
Route::get('produto/{slug}', 'ProductController@show')->name('show-product');

// Search store products
Route::get('loja/produtos/busca', 'StoreController@formSearch')->name('form-search-store');
Route::get('{store}/busca/{gender}/{order?}/{keyword?}', 'StoreController@search');

// Store
Route::group(['prefix' => 'loja'], function () {
	Route::get('login', function () {
	    return view('store.login');
	})->name('store-login-get');
	Route::post('login', 'StoreController@login')->name('store-login-post');

	Route::get('cadastro', function () {
	    return view('store.register');
	})->name('store-register-get');
	Route::post('cadastro', 'StoreController@register')->name('store-register-post');

	// Confirm/refuse product confirmations from email
	Route::get('confirmacoes/{type}/{token}', 'ProductConfirmController@emailUrl')->name('product-confirm-email-url');
	// Confirm/refuse product reserves from email
	Route::get('reservas/{type}/{token}', 'ProductReserveController@emailUrl')->name('product-reserve-email-url');

	Route::group(['prefix' => 'admin', 'middleware' => 'auth:store'], function () {
		Route::get('config', 'StoreController@getConfig')->name('get-store-config');
		Route::post('config', 'StoreController@setConfig')->name('set-store-config');

		Route::post('delete-account', 'StoreController@deleteAccount')->name('delete-store-account');

		Route::group(['prefix' => 'produtos'], function () {
			Route::get('cadastro', 'ProductController@images')->name('product-images');
			Route::post('dropzone', 'ProductController@uploadImages');

			Route::get('/', 'ProductController@edit')->name('edit-products');

			Route::get('busca', 'ProductController@formSearchAdmin')->name('form-search-admin');
			Route::get('busca/{keyword?}', 'ProductController@edit');

			Route::post('save/{id?}', 'ProductController@save')->name('save-products');

			Route::post('enable-disable/{id}', 'ProductController@enableDisable');

			Route::post('delete/{id}', 'ProductController@delete')->name('delete-product');

			Route::post('delete-images/{image}', 'ProductController@deleteImages');
		});

		Route::group(['prefix' => 'mensagens'], function () {
			// List messages
			Route::get('/', 'MessageController@listStoreMessages')->name('list-store-messages');

			// Create message
			Route::post('create', 'MessageController@createStoreMessage');
		});

		Route::group(['prefix' => 'confirmacoes'], function () {
			// List confirmations
			Route::get('/', 'ProductConfirmController@listStoreConfirms')->name('list-store-confirms');

			// Confirm product
			Route::post('confirm/{id}', 'ProductConfirmController@confirm')->name('product-confirm-confirm');

			// Refused product
			Route::post('refuse/{id}', 'ProductConfirmController@refuse')->name('product-refuse-confirm');
		});

		Route::group(['prefix' => 'reservas'], function () {
			// List reserves
			Route::get('/', 'ProductReserveController@listStoreReserves')->name('list-store-reserves');

			// Confirm product
			Route::post('confirm/{id}', 'ProductReserveController@confirm')->name('product-confirm-reserve');

			// Refused product
			Route::post('refuse/{id}', 'ProductReserveController@refuse')->name('product-refuse-reserve');
		});
	});
});

// Client
Route::group(['prefix' => 'cliente'], function () {
	Route::get('login', function () {
	    return view('client.login');
	})->name('client-login-get');
	Route::post('login', 'ClientController@login')->name('client-login-post');

	Route::get('cadastro', function () {
	    return view('client.register');
	})->name('client-register-get');
	Route::post('cadastro', 'ClientController@register')->name('client-register-post');

	Route::group(['prefix' => 'admin', 'middleware' => 'auth:client'], function () {
		Route::get('config', 'ClientController@getConfig')->name('get-client-config');
		Route::post('config', 'ClientController@setConfig')->name('set-client-config');

		Route::post('delete-account', 'ClientController@deleteAccount')->name('delete-client-account');

		Route::group(['prefix' => 'mensagens'], function () {
			// List messages
			Route::get('/', 'MessageController@listClientMessages')->name('list-client-messages');

			// Create message
			Route::post('create', 'MessageController@createClientMessage')->name('create-client-message');
		});

		Route::group(['prefix' => 'produto'], function () {
			// Rate product
			Route::post('rating', 'ProductController@rating')->name('rating-product');

			// Request product confirmation
			Route::post('create-confirmation', 'ProductConfirmController@create')->name('create-product-confirm');

			// Request product reserve
			Route::post('create-reserve', 'ProductReserveController@create')->name('create-product-reserve');

			// List reserves
			Route::get('reservas', 'ProductReserveController@listClientReserves')->name('list-client-reserves');

			// List confirmations
			Route::get('confirmacoes', 'ProductConfirmController@listClientConfirms')->name('list-client-confirms');
		});
	});
});

// Store/Client logout
Route::get('user/logout', 'GlobalController@logout')->name('logout');
