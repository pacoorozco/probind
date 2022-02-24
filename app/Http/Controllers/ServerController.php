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

use App\Helpers\Helper;
use App\Http\Requests\ServerCreateRequest;
use App\Http\Requests\ServerUpdateRequest;
use App\Models\Server;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ServerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $servers = Server::all();

        return view('server.index')
            ->with('servers', $servers);
    }

    public function create(): View
    {
        return view('server.create');
    }

    public function store(ServerCreateRequest $request): RedirectResponse
    {
        Server::create([
            'hostname' => $request->hostname(),
            'ip_address' => $request->ipAddress(),
            'type' => $request->type(),
            'ns_record' => $request->requiresNSRecord(),
            'push_updates' => $request->requiresUpdatePushes(),
            'active' => $request->enabled(),
        ]);

        return redirect()->route('servers.index')
            ->with('success', __('server/messages.create.success'));
    }

    public function show(Server $server): View
    {
        return view('server.show')
            ->with('server', $server);
    }

    public function edit(Server $server): View
    {
        return view('server.edit')
            ->with('server', $server);
    }

    public function update(ServerUpdateRequest $request, Server $server): RedirectResponse
    {
        $server->update([
            'hostname' => $request->hostname(),
            'ip_address' => $request->ipAddress(),
            'type' => $request->type(),
            'ns_record' => $request->requiresNSRecord(),
            'push_updates' => $request->requiresUpdatePushes(),
            'active' => $request->enabled(),
        ]);

        return redirect()->route('servers.index')
            ->with('success', __('server/messages.update.success'));
    }

    public function delete(Server $server): View
    {
        return view('server/delete')
            ->with('server', $server);
    }

    public function destroy(Server $server): RedirectResponse
    {
        $server->delete();

        return redirect()->route('servers.index')
            ->with('success', __('server/messages.delete.success'));
    }

    public function data(DataTables $datatable): JsonResponse
    {
        $servers = Server::select([
            'id',
            'hostname',
            'type',
            'ip_address',
            'push_updates',
            'ns_record',
            'active',
        ]);

        return $datatable->eloquent($servers)
            ->editColumn('hostname', function (Server $server) {
                return $server->active
                    ? $server->present()->hostname
                    : $server->present()->hostname . ' ' . $server->present()->activeAsBadge();
            })
            ->editColumn('push_updates', function (Server $server) {
                return trans_choice('general.boolean', intval($server->push_updates));
            })
            ->editColumn('ns_record', function (Server $server) {
                return trans_choice('general.boolean', intval($server->ns_record));
            })
            ->editColumn('type', function (Server $server) {
                return $server->present()->type();
            })
            ->addColumn('actions', function (Server $server) {
                return view('partials.actions_dd', [
                    'model' => 'servers',
                    'id' => $server->id,
                ])->render();
            })
            ->rawColumns(['hostname', 'actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
