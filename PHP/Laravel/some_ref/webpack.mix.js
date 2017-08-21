const { mix } = require('laravel-mix');

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

mix.copy('node_modules/bulma/css/bulma.css', 'public/css')
    .copy('node_modules/autosize/dist/autosize.min.js', 'public/js')
    .copy('node_modules/easy-autocomplete/dist/jquery.easy-autocomplete.min.js', 'public/js')
    .copy('node_modules/easy-autocomplete/dist/easy-autocomplete.min.css', 'public/css')
    .copy('node_modules/easy-autocomplete/dist/easy-autocomplete.themes.min.css', 'public/css')
    .js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');