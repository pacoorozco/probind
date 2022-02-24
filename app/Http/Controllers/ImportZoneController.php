<?php
/*
 * Copyright (c) 2016-2022 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
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
        // $filename could be false (if error) or null (if submitted file was wrong).
        $filename = $request->zoneFile()?->store('temp');
        if (! is_string($filename)) {
            return view('tools.import_zone_result')
                ->with('output', 'ERROR: Could not write import the zone file');
        }

        Artisan::call('probind:import', [
            '--domain' => $request->domain(),
            '--file' => Storage::path($filename),
        ]);

        Storage::delete($filename);

        return view('tools.import_zone_result')
            ->with('output', Artisan::output());
    }
}
