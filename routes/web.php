<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::singularResourceParameters();

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

/* ------------------------------------------
 *  Record management
 *  ------------------------------------------
 */
// Datatables Ajax route.
// NOTE: We must define this route first as it is more specific than
// the default show resource route for /zones/{zone}
Route::get('zones/{zone}/records/data',
    ['as' => 'zones.records.data', 'uses' => 'RecordController@data']);
// Our special delete confirmation route - uses the show/details view.
Route::get('zones/{zone}/records/{record}/delete',
    ['as' => 'zones.records.delete', 'uses' => 'RecordController@delete']);
Route::resource('zones.records', 'RecordController');

/* ------------------------------------------
 *  Search
 *  ------------------------------------------
 */
Route::get('search',
    ['as' => 'search.index', 'uses' => 'SearchController@index']);
Route::get('search/results',
    ['as' => 'search.results', 'uses' => 'SearchController@search']);

/* ------------------------------------------
 *  Tools
 *  ------------------------------------------
 */
Route::get('tools/push',
    ['as' => 'tools.view_updates', 'uses' => 'ToolsController@viewUpdates']);
Route::post('tools/push',
    ['as' => 'tools.push_updates', 'uses' => 'ToolsController@pushUpdates']);
Route::get('tools/update',
    ['as' => 'tools.bulk_update', 'uses' => 'ToolsController@showBulkUpdate']);
Route::post('tools/update',
    ['as' => 'tools.do_bulk_update', 'uses' => 'ToolsController@doBulkUpdate']);
Route::get('tools/import',
    ['as' => 'tools.import_zone', 'uses' => 'ToolsController@importZone']);
Route::post('tools/import',
    ['as' => 'tools.import_zone_post', 'uses' => 'ToolsController@importZonePost']);
/*
 * ------------------------------------------
 * Settings
 * ------------------------------------------
 */
Route::get('settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
Route::put('settings', ['as' => 'settings.update', 'uses' => 'SettingsController@update']);
