<?php

namespace App\Http\Controllers;

use App\Server;
use App\Zone;
use Illuminate\Http\Request;

use App\Http\Requests;

class ToolsController extends Controller
{
    /**
     * Show the summary page before push updates to servers.
     *
     * @return \Illuminate\Http\Response
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
        if ($zonesToUpdate->isEmpty() and $zonesToDelete->isEmpty()) {
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
     * @return \Illuminate\Http\Response
     */
    public function pushUpdates()
    {
        // create config files
        // create zone files
        // push zone files
        // mark zones delete

        return redirect()->route('home')
            ->with('success', trans('tools/messages.push_updates.success'));
    }
}
