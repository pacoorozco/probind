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

use App\Record;
use App\Server;
use App\User;
use App\Zone;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = [];
        $data['servers'] = Server::all()->count();
        $data['zones'] = Zone::all()->count();
        $data['records'] = Record::all()->count();
        $data['users'] = User::all()->count();

        $activityLog = Activity::orderBy('created_at', 'desc')->orderBy('id', 'desc')->simplePaginate(10);

        return view('dashboard.index')
            ->with('data', $data)
            ->with('activityLog', $activityLog);
    }
}
