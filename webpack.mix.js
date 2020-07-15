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
    .sass('resources/sass/mobile/global-mobile.scss', 'public/css')
    .babel([
        'resources/plugins/jquery.min.js',
        'resources/plugins/bootstrap.min.js',
        'resources/plugins/jquery.validate.min.js',
        'resources/plugins/bootstrap-select.min.js',
        'resources/plugins/jquery.mask.min.js',
        'resources/plugins/dropzone.min.js',
        'resources/plugins/exif.min.js',
        'resources/plugins/slick.min.js',
        'resources/js/mobile/geral.js',
        'resources/js/mobile/show-product.js',
        'resources/js/mobile/bag.js',
        'resources/js/mobile/store-config.js',
        'resources/js/mobile/admin-products.js',
        'resources/js/mobile/create-edit-product.js'
    ], 'public/js/global-mobile.js')
    .babel([
        'resources/plugins/jquery.min.js',
        'resources/plugins/bootstrap.min.js',
        'resources/plugins/jquery.validate.min.js',
        'resources/plugins/bootstrap-select.min.js',
        'resources/plugins/jquery.mask.min.js',
        'resources/plugins/dropzone.min.js',
        'resources/plugins/exif.min.js',
        'resources/plugins/slick.min.js',
        'resources/js/geral.js',
        'resources/js/show-product.js',
        'resources/js/bag.js',
        'resources/js/store-config.js',
        'resources/js/product-images.js',
        'resources/js/product-edit.js'
    ], 'public/js/global.js')
    .copyDirectory('resources/images', 'public/images')
    .copyDirectory('resources/fonts', 'public/fonts');

if(mix.inProduction()) {
    mix.version([
        'public/css/global.css',
        'public/css/global-mobile.css',
        'public/js/global.js',
        'public/js/global-mobile.js'
    ]);
}
