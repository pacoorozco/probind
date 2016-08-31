<?php
/**
 * ProBIND v3 - Professional DNS management made easy.
 *
 * Copyright (c) 2016 by Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of some open source application.
 *
 * Licensed under GNU General Public License 3.0.
 * Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2016 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 * @link        https://github.com/pacoorozco/probind
 *
 */

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
/*
 * ------------------------------------------
 * Settings
 * ------------------------------------------
 */
Route::get('settings', ['as' => 'settings.index', 'uses' => 'SettingsController@index']);
Route::put('settings', ['as' => 'settings.update', 'uses' => 'SettingsController@update']);
