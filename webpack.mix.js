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
    .copy('node_modules/datatables.net-dt/images', 'public/images')
    .scripts([
        'node_modules/underscore/underscore.js',
        'node_modules/jquery/dist/jquery.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/backbone/backbone.js',
        'node_modules/datatables.net/js/jquery.dataTables.js',
        'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js',
    ], 'public/js/scripts.js')
    .styles([
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/bootstrap/dist/css/bootstrap-grid.css',
        'node_modules/bootstrap/dist/css/bootstrap-reboot.css',
        'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css'
    ], 'public/css/bootstrap.css');
