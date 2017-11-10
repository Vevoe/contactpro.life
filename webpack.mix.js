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

mix.scripts([
        'node_modules/lodash/lodash.js',
        'node_modules/jquery-validation/dist/jquery.validate.js',
        'node_modules/tableable/dist/jquery.tableable.js',
        'node_modules/jquery-toast-plugin/src/jquery.toast.js',
    ], 'public/js/vendor.js')
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');
