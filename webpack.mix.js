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

mix.js('resources/assets/js/app.js', 'public/assets/js');

// Combine all CSS files in one
mix.styles([
    'node_modules/bootstrap/dist/css/bootstrap.min.css',
    'node_modules/font-awesome/css/font-awesome.min.css',
    'node_modules/ionicons/dist/css/ionicons.min.css',
    'node_modules/admin-lte/plugins/iCheck/square/blue.css',
    'node_modules/datatables-all/media/css/dataTables.bootstrap.min.css',
], 'public/css/vendor.css');

mix.styles([
    'node_modules/admin-lte/dist/css/AdminLTE.min.css',
    'node_modules/admin-lte/dist/css/skins/_all-skins.min.css',
    'resources/assets/css/app.css'
], 'public/css/theme.css');

// Combine all JS files in one
mix.scripts([
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/bootstrap/dist/js/bootstrap.min.js',
    'public/assets/js/app.js',
    'node_modules/datatables-all/media/js/jquery.dataTables.min.js',
    'node_modules/datatables-all/media/js/dataTables.bootstrap.js'
], 'public/js/vendor.js');

mix.scripts([
    'node_modules/admin-lte/dist/js/adminlte.min.js',
    'node_modules/admin-lte/plugins/iCheck/icheck.min.js'
], 'public/js/theme.js');

// Version CSS & JS files
if (mix.inProduction()) {
    mix.version();
}

mix.copy(
    'node_modules/admin-lte/plugins/iCheck/square/blue.png',
    'public/css'
);

// Copy Fonts
mix.copyDirectory(
   'node_modules/font-awesome/fonts',
   'public/fonts'
);

mix.copyDirectory(
    'node_modules/bootstrap/fonts',
    'public/fonts'
);
