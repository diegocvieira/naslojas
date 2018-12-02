const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/sass/global.scss', 'public/css')
    .sass('resources/sass/global-store.scss', 'public/css')
    .babel([
        'resources/js/geral.js',
        'resources/js/show-product.js'
    ], 'public/js/global.js')
    .babel([
        'resources/js/store-config.js',
        'resources/js/product-images.js',
        'resources/js/product-edit.js'
    ], 'public/js/global-store.js')
    .copyDirectory('resources/images', 'public/images')
    .copyDirectory('resources/offline-developer', 'public/offline-developer')
    .copyDirectory('resources/fonts', 'public/fonts');

if(mix.inProduction()) {
    mix.version([
        'public/css/global.css',
        'public/css/global-store.css',
        'public/js/global.js',
        'public/js/global-store.js'
    ]);
}
