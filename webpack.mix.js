const mix = require('laravel-mix');

mix
    .js("resources/js/main.js", "dist/js")
    .sass('resources/sass/app.scss', 'dist/css');
