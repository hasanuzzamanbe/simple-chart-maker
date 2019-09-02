let mix = require('laravel-mix');

mix.setResourceRoot('../');
mix
    .js('src/js/Boot.js', 'dist/js/boot.js')
    .js('src/js/main.js', 'dist/js/chart-maker.js')
    .js('src/js/frontend.js', 'dist/js/chart-maker-frontend.js')
    .sass('src/scss/admin/app.scss', 'dist/admin/css/chartmaker-admin.css')
