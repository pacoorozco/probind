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

namespace App\Http\Controllers;

use App\Http\Requests\ImportZoneRequest;
use App\Server;
use App\Zone;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ToolsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the summary page before push updates to servers.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewUpdates()
    {
        $servers = Server::withPushCapability()
            ->orderBy('hostname')
            ->get();

        $zonesToUpdate = Zone::withPendingChanges()
            ->orderBy('domain')
            ->get();

        $zonesToDelete = Zone::onlyTrashed()
            ->orderBy('domain')
            ->get();

        return view('tools.push')
            ->with('servers', $servers)
            ->with('zonesToUpdate', $zonesToUpdate)
            ->with('zonesToDelete', $zonesToDelete);
    }

    /**
     * Push updates to servers.
     *
     * @codeCoverageIgnore
     */
    public function pushUpdates()
    {
        Artisan::call('probind:push');

        return view('tools.push_result')
            ->with('output', Artisan::output());
    }

    /**
     * Show the summary page before bulk update.
     *
     * @return \Illuminate\View\View
     */
    public function showBulkUpdate()
    {
        return view('tools.bulk_update');
    }

    /**
     * Push updates to servers.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function doBulkUpdate()
    {
        $zones = Zone::all();
        foreach ($zones as $zone) {
            $zone->setPendingChanges();
        }

        return redirect()->route('home')
            ->with('success', __('tools/messages.bulk_update_success'));
    }

    /**
     * Show the form to import a zone from a RFC 1033 file.
     *
     * @return \Illuminate\View\View
     */
    public function importZone()
    {
        return view('tools.import_zone');
    }

    public function importZoneFromFile(ImportZoneRequest $request): View
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
