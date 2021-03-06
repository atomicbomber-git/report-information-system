let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/notification.js', 'public/js')
    .styles(
    [
        'node_modules/bootstrap/dist/css/bootstrap.css',
        'node_modules/font-awesome/css/font-awesome.css'
    ], 'public/css/app.css')
    .js('resources/assets/js/pre.js', 'public/js')
    .copy('node_modules/sweetalert/dist/sweetalert.min.js', 'public/js')
    .copy('node_modules/frappe-charts/dist/frappe-charts.min.iife.js', 'public/js')
    .copy('node_modules/datatables.net/js/jquery.dataTables.js', 'public/js')
    .copyDirectory('node_modules/font-awesome/fonts', 'public/fonts');