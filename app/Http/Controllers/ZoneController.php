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
 *
 */

namespace App\Http\Controllers;

use App\Http\Requests\ZoneCreateRequest;
use App\Http\Requests\ZoneUpdateRequest;
use App\Zone;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ZoneController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $zones = Zone::all();

        return view('zone.index')
            ->with('zones', $zones);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('zone.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ZoneCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ZoneCreateRequest $request)
    {
        $zone = new Zone();

        // assign new serial and flag as updated
        if ($request->input('type') == 'master') {
            $zone->serial = Zone::createSerialNumber();
            $zone->updated = true;
        }

        $zone->fill($request->all())->save();

        return redirect()->route('zones.index')
            ->with('success', trans('zone/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Zone $zone
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zone)
    {
        return view('zone.show')
            ->with('zone', $zone);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Zone $zone
     * @return \Illuminate\Http\Response
     */
    public function edit(Zone $zone)
    {
        return view('zone.edit')
            ->with('zone', $zone);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ZoneUpdateRequest $request
     * @param  Zone $zone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ZoneUpdateRequest $request, Zone $zone)
    {
        if ($zone->isMasterZone()) {
            // assign new serial and flag as updated
            $zone->setSerialNumber();
            $zone->setPendingChanges(true);
        }
        // deal with checkboxes
        $zone->custom_settings = $request->has('custom_settings');

        $zone->fill($request->all())->save();

        return redirect()->route('zones.index')
            ->with('success', trans('zone/messages.update.success'));
    }

    /**
     * Remove zone page.
     *
     * @param Zone $zone
     * @return \Illuminate\Http\Response
     */
    public function delete(Zone $zone)
    {
        return view('zone/delete')
            ->with('zone', $zone);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Zone $zone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Zone $zone)
    {
        $zone->delete();

        return redirect()->route('zones.index')
            ->with('success', trans('zone/messages.delete.success'));
    }

    /**
     * Show a list of all the levels formatted for Datatables.
     *
     * @param Request $request
     * @param Datatables $dataTable
     * @return Datatables JsonResponse
     */
    public function data(Request $request, Datatables $dataTable)
    {
        $zones = Zone::select([
            'id',
            'domain',
            'master',
            'updated'
        ]);

        return $dataTable::of($zones)
            ->addColumn('type', function (Zone $zone) {
                return ($zone->isMasterZone()) ? trans('zone/model.types.master') : trans('zone/model.types.slave');
            })
            ->editColumn('updated', function (Zone $zone) {
                return ($zone->hasPendingChanges()) ? trans('general.yes') : trans('general.no');
            })
            ->addColumn('actions', function (Zone $zone) {
                return view('zone._actions')
                    ->with('zone', $zone)
                    ->render();
            })
            ->removeColumn('id')
            ->make(true);
    }
}
