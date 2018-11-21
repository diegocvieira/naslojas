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

	Route::group(['middleware' => 'auth:store'], function () {
		Route::get('config', 'StoreController@getConfig')->name('get-store-config');
		Route::post('config', 'StoreController@setConfig')->name('set-store-config');

		Route::get('produtos/cadastro', 'ProductController@register');

		Route::post('dropzone/upload-images', 'ProductController@uploadImages');
		Route::post('dropzone/save-images', 'ProductController@saveImages')->name('save-images');

		Route::post('delete-account', 'StoreController@deleteAccount')->name('delete-store-account');
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

	Route::group(['middleware' => 'auth:client'], function () {
		Route::get('config', 'ClientController@getConfig')->name('get-client-config');
		Route::post('config', 'ClientController@setConfig')->name('set-client-config');

		Route::post('delete-account', 'ClientController@deleteAccount')->name('delete-client-account');
	});
});

// Store/Client logout
Route::get('user/logout', 'GlobalController@logout')->name('logout');
