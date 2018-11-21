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
    .babel([
        'resources/js/geral.js',
        'resources/js/store-config.js'
    ], 'public/js/global.js')
    .copyDirectory('resources/images', 'public/images')
    .copyDirectory('resources/offline-developer', 'public/offline-developer')
    .copyDirectory('resources/fonts', 'public/fonts');

if(mix.inProduction()) {
    mix.version(['public/css/global.css', 'public/js/global.js']);
}
