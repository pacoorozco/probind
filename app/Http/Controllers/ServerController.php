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

use App\Http\Requests\ServerCreateRequest;
use App\Http\Requests\ServerUpdateRequest;
use App\Server;
use Yajra\Datatables\Datatables;

class ServerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $servers = Server::all();

        return view('server.index')
            ->with('servers', $servers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('server.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ServerCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ServerCreateRequest $request)
    {
        $server = new Server();

        // deal with checkboxes
        $server->ns_record = $request->has('ns_record');
        $server->push_updates = $request->has('push_updates');

        $server->fill($request->all())->save();

        return redirect()->route('servers.index')
            ->with('success', trans('server/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Server $server
     *
     * @return \Illuminate\View\View
     */
    public function show(Server $server)
    {
        return view('server.show')
            ->with('server', $server);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Server $server
     *
     * @return \Illuminate\View\View
     */
    public function edit(Server $server)
    {
        return view('server.edit')
            ->with('server', $server);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ServerUpdateRequest $request
     * @param  Server $server
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ServerUpdateRequest $request, Server $server)
    {
        // deal with checkboxes
        $server->ns_record = $request->has('ns_record');
        $server->push_updates = $request->has('push_updates');

        $server->fill($request->all())->save();

        return redirect()->route('servers.index')
            ->with('success', trans('server/messages.update.success'));
    }

    /**
     * Remove level page.
     *
     * @param Server $server
     *
     * @return \Illuminate\View\View
     */
    public function delete(Server $server)
    {
        return view('server/delete')
            ->with('server', $server);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Server $server
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Server $server)
    {
        $server->delete();

        return redirect()->route('servers.index')
            ->with('success', trans('server/messages.delete.success'));
    }

    /**
     * Show a list of all the levels formatted for Datatables.
     *
     * @param Datatables $dataTable
     *
     * @return Datatables JsonResponse
     */
    public function data(Datatables $dataTable)
    {
        $servers = Server::select([
            'id',
            'hostname',
            'type',
            'ip_address',
            'push_updates',
            'ns_record',
            'active'
        ]);

        return $dataTable::of($servers)
            ->editColumn('hostname', function (Server $server) {
                $label = ( ! $server->active)
                    ? ' <span class="label label-default">' . trans('general.inactive') . '</span>'
                    : '';

                return $server->hostname . $label;
            })
            ->editColumn('push_updates', function (Server $server) {
                return ($server->push_updates) ? trans('general.yes') : trans('general.no');
            })
            ->editColumn('ns_record', function (Server $server) {
                return ($server->ns_record) ? trans('general.yes') : trans('general.no');
            })
            ->addColumn('actions', function (Server $server) {
                return view('partials.actions_dd', [
                    'model' => 'servers',
                    'id'    => $server->id,
                ])->render();
            })
            ->removeColumn('id')
            ->make(true);
    }
}
