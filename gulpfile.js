var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */


/**
 *      INSTALL DEPENDENCIES
 *      npm install admin-lte@2.3.11 --save
 *
 */


elixir(function(mix) {

    // Copy bootstrap and AdminLTE CSS files to public directory
    mix.copy('node_modules/admin-lte/bootstrap/css/bootstrap.css', 'public/themes/admin-lte/bootstrap/css/bootstrap.min.css');
    mix.copy('node_modules/admin-lte/dist/css/AdminLTE.css', 'public/themes/admin-lte/dist/css/AdminLTE.min.css');
    mix.copy('node_modules/admin-lte/dist/css/skins/skin-blue.css', 'public/themes/admin-lte/dist/css/skins/skin-blue.min.css');

    mix.copy('node_modules/admin-lte/plugins/jQuery/jquery-2.2.3.min.js', 'public/themes/admin-lte/plugins/jQuery/jquery-2.2.3.min.js');
    mix.copy('node_modules/admin-lte/bootstrap/js/bootstrap.min.js', 'public/themes/admin-lte/bootstrap/js/bootstrap.min.js');
    mix.copy('node_modules/admin-lte/dist/js/app.js', 'public/themes/admin-lte/dist/js/app.min.js');

    // iCheck
    mix.copy('node_modules/admin-lte/plugins/iCheck/square/blue.css', 'public/themes/admin-lte/plugins/iCheck/square/blue.css');
    mix.copy('node_modules/admin-lte/plugins/iCheck/icheck.min.js', 'public/themes/admin-lte/plugins/iCheck/icheck.min.js');

    // dataTables
    mix.copy('node_modules/admin-lte/plugins/datatables/dataTables.bootstrap.css', 'public/themes/admin-lte/plugins/datatables/dataTables.bootstrap.css');
    mix.copy('node_modules/admin-lte/plugins/datatables/jquery.dataTables.js', 'public/themes/admin-lte/plugins/datatables/jquery.dataTables.min.js');
    mix.copy('node_modules/admin-lte/plugins/datatables/dataTables.bootstrap.js', 'public/themes/admin-lte/plugins/datatables/dataTables.bootstrap.min.js');

});