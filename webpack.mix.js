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
    .vue()
    .sass('resources/sass/app.scss', 'public/css');

// it will compile and merge 2 test css file into all.css created file
// mix.styles(
//     [
//         'public/css/css1.css',
//         'public/css/css2.css',
//     ], 'public/css/all.css'
// )

// it will compile main.css file put into all.css created file
mix.styles(
    [
        'public/css/grid.min.css',
        'public/css/main.css',
    ], 'public/css/all.css'
)