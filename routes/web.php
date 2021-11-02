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
 *
 * @link        https://github.com/pacoorozco/probind
 */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\BulkUpdateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImportZoneController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\ResourceRecordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SyncServersController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Middleware\EnsureNotPreviouslyInstalled;
use App\Http\Middleware\OnlyAjax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/* ------------------------------------------
 * Authentication routes
 *
 * Routes to be authenticated
 *  ------------------------------------------
 */
Auth::routes([
    'register' => false,  // User registration
    'verify' => false, // E-mail verification
]);

Route::middleware(['auth'])->group(function () {
    Route::get('/', HomeController::class)
        ->name('home');

    Route::get('search',
        [SearchController::class, 'index'])
        ->name('search.index');
    Route::get('search/results',
        [SearchController::class, 'search'])
        ->name('search.results');

    /**
     * ------------------------------------------
     * Users
     * ------------------------------------------.
     */
    // DataTables Ajax route.
    Route::middleware(OnlyAjax::class)
        ->get('users/data',
            [UserController::class, 'data'])
        ->name('users.data');

    // Our special delete confirmation route - uses the show/details view.
    Route::get('users/{user}/delete',
        [UserController::class, 'delete'])
        ->name('users.delete');

    // Pre-baked resource controller actions for index, create, store,
    // show, edit, update, destroy
    Route::resource('users', UserController::class);

    /**
     * ------------------------------------------
     * Servers
     * ------------------------------------------.
     */
    // DataTables Ajax route.
    Route::middleware(OnlyAjax::class)
        ->get('servers/data',
            [ServerController::class, 'data'])
        ->name('servers.data');

    // Our special delete confirmation route - uses the show/details view.
    Route::get('servers/{Server}/delete',
        [ServerController::class, 'delete'])
        ->name('servers.delete');

    // Pre-baked resource controller actions for index, create, store,
    // show, edit, update, destroy
    Route::resource('servers', ServerController::class);

    /**
     * ------------------------------------------
     * Zones
     * ------------------------------------------.
     */
    // DataTables Ajax route.
    Route::middleware(OnlyAjax::class)
        ->get('zones/data',
            [ZoneController::class, 'data'])
        ->name('zones.data');

    // Pre-baked resource controller actions for index, create, store,
    // show, edit, update, destroy
    Route::resource('zones', ZoneController::class);

    /**
     * ------------------------------------------
     * ResourceRecords
     * ------------------------------------------.
     */
    // DataTables Ajax route.
    Route::middleware(OnlyAjax::class)
        ->get('zones/{zone}/records/data',
            [ResourceRecordController::class, 'data'])
        ->name('zones.records.data');

    // Our special delete confirmation route - uses the show/details view.
    Route::get('zones/{zone}/records/{record}/delete',
        [ResourceRecordController::class, 'delete'])
        ->name('zones.records.delete');

    // Pre-baked resource controller actions for index, create, store,
    // show, edit, update, destroy
    Route::resource('zones.records', ResourceRecordController::class);

    /**
     * ------------------------------------------
     * Settings
     * ------------------------------------------.
     */
    Route::get('settings',
        [SettingsController::class, 'index'])
        ->name('settings.index');

    Route::put('settings',
        [SettingsController::class, 'update'])
        ->name('settings.update');

    /**
     * ------------------------------------------
     * Tools
     * ------------------------------------------.
     */

    // Push DNS servers tool
    Route::get('tools/push',
        [SyncServersController::class, 'index'])
        ->name('tools.view_updates');

    Route::post('tools/push',
        [SyncServersController::class, 'sync'])
        ->name('tools.push_updates');

    // Bulk update tool
    Route::get('tools/update',
        [BulkUpdateController::class, 'index'])
        ->name('tools.bulk_update');
    Route::post('tools/update',
        [BulkUpdateController::class, 'update'])
        ->name('tools.do_bulk_update');

    // Zone import tool
    Route::get('tools/import',
        [ImportZoneController::class, 'index'])
        ->name('tools.import_zone');
    Route::post('tools/import',
        [ImportZoneController::class, 'store'])
        ->name('tools.import_zone_post');
});

/*  ------------------------------------------
 *  Installer
 *  ------------------------------------------
 */
Route::middleware(EnsureNotPreviouslyInstalled::class)->group(function () {
    Route::get('install',
        [InstallController::class, 'index'])
        ->name('install.begin');

    Route::get('install/database',
        [InstallController::class, 'showDatabaseForm'])
        ->name('install.database');

    Route::post('install/database',
        [InstallController::class, 'createDatabase'])
        ->name('install.databaseSave');

    Route::get('install/end',
        [InstallController::class, 'end'])
        ->name('install.end');
});
