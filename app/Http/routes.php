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

Route::resource('servers', 'ServerController');
// Our special delete confirmation route - uses the show/details view.
Route::get('servers/{level}/delete',
    ['as' => 'servers.delete', 'uses' => 'ServerController@delete']);
