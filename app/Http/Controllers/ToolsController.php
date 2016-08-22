<?php

namespace App\Http\Controllers;

use App\Server;
use App\Zone;

class ToolsController extends Controller
{

    /**
     * Show the summary page before push updates to servers.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewUpdates()
    {
        $servers = Server::where('push_updates', 1)
            ->orderBy('hostname')
            ->get();

        // Test if there are servers to be pushed
        if ($servers->isEmpty()) {
            return redirect()->route('home')
                ->with('warning', trans('tools/messages.push_updates.no_servers'));
        }

        $zonesToUpdate = Zone::where('updated', 1)
            ->orderBy('domain')
            ->get();

        $zonesToDelete = Zone::onlyTrashed()
            ->orderBy('domain')
            ->get();

        // Test if there are zones to be pushed
        if ($zonesToUpdate->isEmpty() && $zonesToDelete->isEmpty()) {
            return redirect()->route('home')
                ->with('warning', trans('tools/messages.push_updates.nothing_to_do'));
        }

        return view('tools.push')
            ->with('servers', $servers)
            ->with('zonesToUpdate', $zonesToUpdate)
            ->with('zonesToDelete', $zonesToDelete);
    }

    /**
     * Push updates to servers.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pushUpdates()
    {
        // create config files

        // create zone files and push to servers
        \Artisan::call('probind:push');

        // mark zones delete

        return redirect()->route('home')
            ->with('success', trans('tools/messages.push_updates.success'));
    }

    /**
     * Show the summary page before bulk update.
     *
     * @return \Illuminate\Http\Response
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
            ->with('success', trans('tools/messages.bulk_update_success'));
    }
}
