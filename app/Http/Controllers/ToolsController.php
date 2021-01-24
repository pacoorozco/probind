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
use App\Server;
use App\Zone;
use Illuminate\Support\Facades\Artisan;

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

    /**
     * Call Artisan 'probind:import' command with supplied data.
     *
     * @param ImportZoneRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * FIXME
     * Use queues for doing long tasks on background. This will require some notification system.
     * Errors processing import Zone.
     */
    public function importZonePost(ImportZoneRequest $request)
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
            'zone'     => $request->input('domain'),
            'zonefile' => storage_path('app/' . $zonefile),
            '--force'  => $request->has('overwrite'),
        ]);

        return redirect()->route('home')
            ->with('success',
                __('tools/messages.import_zone_success', ['zone' => $request->input('domain')]));
    }
}
