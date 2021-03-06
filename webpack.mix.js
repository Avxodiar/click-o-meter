const mix = require('laravel-mix');

/**
 * Stop generating license file /js/app.js.LICENSE.txt
 */
mix.options({
    terser: {
        extractComments: false,
    }
});

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
    .copy('resources/css/style.css', 'public/css/')

    .copy('vendor/nnnick/chartjs/dist/Chart.min.css', 'public/css/chart.min.css')
    .copy('vendor/nnnick/chartjs/dist/Chart.min.js', 'public/js/chart.min.js')

    .copy('node_modules/simpleheat/simpleheat.js', 'public/js')
    .js('resources/js/map-heat.js', 'public/js')
    .copy('resources/css/map-heat.css', 'public/css')

    .js('resources/js/clicker.js', 'public/js/clicker.min.js')
    .version();

mix.disableNotifications();
