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

use App\Http\Requests\Request;
use App\Http\Requests\ZoneCreateRequest;
use App\Http\Requests\ZoneUpdateRequest;
use App\Zone;
use Yajra\Datatables\Datatables;

class ZoneController extends Controller
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
        return view('zone.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('zone.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ZoneCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ZoneCreateRequest $request)
    {
        try {
            $zone = new Zone();
            $zone->domain = $request->input('domain');

            $this->fillZoneFromRequest($zone, $request);

            $zone->save();
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('zone/messages.create.error'));
        }

        return redirect()->route('zones.index')
            ->with('success', __('zone/messages.create.success'));
    }

    /**
     * Fill the zone with the correct values from the request.
     *
     * @param  \App\Zone  $zone
     * @param  \App\Http\Requests\Request  $request
     */
    private function fillZoneFromRequest(Zone $zone, Request $request): void
    {
        $zone->reverse_zone = Zone::isReverseZoneName($zone->domain);
        if ($request->input('zone_type') === 'secondary-zone') {
            $zone->master_server = $request->input('master_server');
        } else {
            $zone->serial = Zone::generateSerialNumber();
            $zone->setPendingChanges();

            // deal with checkboxes
            $zone->custom_settings = $request->has('custom_settings');

            $zone->fill($request->only([
                'refresh',
                'retry',
                'expire',
                'negative_ttl',
                'default_ttl',
            ]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Zone  $zone
     * @return \Illuminate\View\View
     */
    public function show(Zone $zone)
    {
        return view('zone.show')
            ->with('zone', $zone);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Zone  $zone
     * @return \Illuminate\View\View
     */
    public function edit(Zone $zone)
    {
        return view('zone.edit')
            ->with('zone', $zone);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ZoneUpdateRequest  $request
     * @param  Zone  $zone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ZoneUpdateRequest $request, Zone $zone)
    {
        try {
            // if it's a Master zone, assign new Serial Number and flag pending changes.
            if ($zone->isMasterZone()) {
                $zone->getNewSerialNumber();
                $zone->setPendingChanges();
            }

            $this->fillZoneFromRequest($zone, $request);

            $zone->saveOrFail();
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('zone/messages.update.error'));
        }

        return redirect()->route('zones.index')
            ->with('success', __('zone/messages.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Zone  $zone
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Zone $zone)
    {
        try {
            $zone->delete();
        } catch (\Throwable $exception) {
            return redirect()->back()
                ->with('error', __('zone/messages.delete.error'));
        }

        return redirect()->route('zones.index')
            ->with('success', __('zone/messages.delete.success'));
    }

    /**
     * Show a list of all the levels formatted for DataTables.
     *
     * @param  \Yajra\Datatables\Datatables  $dataTable
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function data(DataTables $dataTable)
    {
        $zones = Zone::withCount('records')
            ->orderBy('domain', 'ASC');

        return $dataTable->eloquent($zones)
            ->addColumn('type', function (Zone $zone) {
                return __('zone/model.types.' . $zone->getTypeOfZone());
            })
            ->editColumn('has_modifications', function (Zone $zone) {
                return $zone->present()->statusIcon;
            })
            ->addColumn('actions', function (Zone $zone) {
                return view('zone._actions')
                    ->with('zone', $zone)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
