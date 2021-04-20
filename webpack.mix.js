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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .scripts([
        'node_modules/underscore/underscore.js',
        'node_modules/jquery/dist/jquery.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/backbone/backbone.js',
    ], 'public/js/scripts.js')
    .styles([
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/bootstrap/dist/css/bootstrap-grid.css',
        'node_modules/bootstrap/dist/css/bootstrap-reboot.css'
    ], 'public/css/bootstrap.css')
