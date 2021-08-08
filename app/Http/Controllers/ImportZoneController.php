<?php
/*
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

use App\Http\Requests\ImportZoneRequest;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ImportZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('tools.import_zone');
    }

    public function store(ImportZoneRequest $request): View
    {
        $filename = $request->zoneFile()->store('temp');

        Artisan::call('probind:import', [
            '--domain' => $request->domain(),
            '--file' => Storage::path($filename),
        ]);

        Storage::delete($filename);

        return view('tools.import_zone_result')
            ->with('output', Artisan::output());
    }
}
