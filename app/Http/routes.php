<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', ['as' => 'home', 'uses' => 'DashboardController@index']);

/* ------------------------------------------
 *  Server management
 *  ------------------------------------------
 */
// Datatables Ajax route.
// NOTE: We must define this route first as it is more specific than
// the default show resource route for /servers/{server}
Route::get('servers/data',
    ['as' => 'servers.data', 'uses' => 'ServerController@data']);
// Our special delete confirmation route - uses the show/details view.
Route::get('servers/{server}/delete',
    ['as' => 'servers.delete', 'uses' => 'ServerController@delete']);
Route::resource('servers', 'ServerController');

/* ------------------------------------------
 *  Zone management
 *  ------------------------------------------
 */
// Datatables Ajax route.
// NOTE: We must define this route first as it is more specific than
// the default show resource route for /zones/{zone}
Route::get('zones/data',
    ['as' => 'zones.data', 'uses' => 'ZoneController@data']);
// Our special delete confirmation route - uses the show/details view.
Route::get('zones/{zone}/delete',
    ['as' => 'zones.delete', 'uses' => 'ZoneController@delete']);
Route::resource('zones', 'ZoneController');