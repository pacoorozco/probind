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

use App\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

/**
 * Class BroadcastServiceProvider
 *
 * @package App\Providers
 * @codeCoverageIgnore
 */
class BroadcastServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes();

        /*
         * Authenticate the user's personal channel...
         */
        Broadcast::channel('App.User.*', function (User $user, $userId) {
            return (int)$user->id === (int)$userId;
        });
    }
}
