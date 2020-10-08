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
    .js('resources/js/registerApp.js', 'public/js')
    .js('resources/js/adminApp.js', 'public/js')
    .js('resources/js/skillApp.js', 'public/js')
    .js('resources/js/myPageApp.js', 'public/js')
    .js('resources/js/managerApp.js', 'public/js')
    .js('resources/js/expertApp.js', 'public/js')
    .js('resources/js/learningApp.js', 'public/js')
    .js('resources/js/mainApp.js', 'public/js')
    .js('resources/js/certificationApp.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

