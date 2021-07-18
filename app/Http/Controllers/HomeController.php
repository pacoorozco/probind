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

namespace App\Http\Controllers;

use App\Models\ResourceRecord;
use App\Models\Server;
use App\Models\User;
use App\Models\Zone;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(): View
    {
        $serversCount = Server::all()->count();
        $zonesCount = Zone::all()->count();
        $resourceRecordsCount = ResourceRecord::all()->count();
        $usersCount = User::all()->count();

        $activities = Activity::all()->sortByDesc('created_at')->sortByDesc('id')->take(10);

        return view('dashboard.index')
            ->with('serversCount', $serversCount)
            ->with('zonesCount', $zonesCount)
            ->with('resourceRecordsCount', $resourceRecordsCount)
            ->with('usersCount', $usersCount)
            ->with('activities', $activities);
    }
}
