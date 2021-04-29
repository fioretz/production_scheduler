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

mix.js([
        'node_modules/popper.js/dist/umd/popper.js',
    ], 'public/js/app.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps()
    .copy('node_modules/datatables.net-dt/images', 'public/images')
    .scripts([
        'node_modules/popper.js/dist/umd/popper.js',
        'node_modules/jquery/dist/jquery.js',
        'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
        'node_modules/bootstrap-select/dist/js/i18n/defaults-it_IT.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/datatables.net/js/jquery.dataTables.js',
        'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js',
    ], 'public/js/scripts.js')
    .styles([
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/bootstrap/dist/css/bootstrap-grid.css',
        'node_modules/bootstrap/dist/css/bootstrap-reboot.css',
        'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.css',
        'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css'
    ], 'public/css/bootstrap.css');
