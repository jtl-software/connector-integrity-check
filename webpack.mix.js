const { mix } = require('laravel-mix');

mix.webpackConfig({
    output: {
        path: "./public"
    }
});

mix.js('resources/assets/js/application.js', 'js')
    .sass('resources/assets/sass/application.scss', 'css');
