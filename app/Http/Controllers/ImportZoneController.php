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

use App\Http\Requests\ImportZoneRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;
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

    public function store(ImportZoneRequest $request): RedirectResponse
    {
        // Move uploaded file to local storage.
        $zonefile = $request->file('zonefile')->store('temp');
        if (false === $zonefile) {
            // Handle error
            redirect()->route('home')
                ->with('error',
                    __('tools/messages.import_zone_error', ['zone' => $request->input('domain')]));
        }

        Artisan::call('probind:import', [
            'zone' => $request->input('domain'),
            'zonefile' => storage_path('app/'.$zonefile),
            '--force' => $request->has('overwrite'),
        ]);

        return redirect()->route('home')
            ->with('success',
                __('tools/messages.import_zone_success', ['zone' => $request->input('domain')]));
    }
}
