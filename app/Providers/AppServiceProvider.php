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
 */

namespace App\Providers;

use App\Record;
use Illuminate\Support\ServiceProvider;
use Setting;
use View;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Touch Zone when save/delete a Record
        Record::saving(function (Record $record) {
            $zone = $record->zone()->first();
            $zone->setPendingChanges(true);
        });
        Record::deleting(function (Record $record) {
            $zone = $record->zone()->first();
            $zone->setPendingChanges(true);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Load Service Providers for 'local' environment
        if ($this->app->environment('local')) {
            // @codeCoverageIgnoreStart
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            // @codeCoverageIgnoreEnd
        }
    }
}
