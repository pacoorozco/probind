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

use App\Helpers\Helper;
use App\Http\Requests\ServerCreateRequest;
use App\Http\Requests\ServerUpdateRequest;
use App\Server;
use DataTables;

class ServerController extends Controller
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
     * @param  ServerCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ServerCreateRequest $request)
    {
        try {
            Server::create($request->validated());
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('server/messages.create.error'));
        }

        return redirect()->route('servers.index')
            ->with('success', __('server/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Server  $server
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
     * @param  Server  $server
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
     * @param  ServerUpdateRequest  $request
     * @param  Server  $server
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ServerUpdateRequest $request, Server $server)
    {
        try {
            $server->update($request->validated());
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('server/messages.update.error'));
        }

        return redirect()->route('servers.index')
            ->with('success', __('server/messages.update.success'));
    }

    /**
     * Remove level page.
     *
     * @param  Server  $server
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
     * @param  Server  $server
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Server $server)
    {
        $server->delete();

        return redirect()->route('servers.index')
            ->with('success', __('server/messages.delete.success'));
    }

    /**
     * Show a list of all the Servers formatted for DataTables.
     *
     * @param  DataTables  $dataTable
     * @return DataTables JsonResponse
     */
    public function data(DataTables $dataTable)
    {
        $servers = Server::get([
            'id',
            'hostname',
            'type',
            'ip_address',
            'push_updates',
            'ns_record',
            'active',
        ]);

        return $dataTable::of($servers)
            ->editColumn('hostname', function (Server $server) {
                return Helper::addStatusLabel($server->active, $server->hostname);
            })
            ->editColumn('push_updates', function (Server $server) {
                return trans_choice('general.boolean', intval($server->push_updates));
            })
            ->editColumn('ns_record', function (Server $server) {
                return trans_choice('general.boolean', intval($server->ns_record));
            })
            ->addColumn('actions', function (Server $server) {
                return view('partials.actions_dd', [
                    'model' => 'servers',
                    'id' => $server->id,
                ])->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->make(true);
    }
}
