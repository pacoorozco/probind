<?php
/*
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

use App\Http\Requests\Request;
use App\Http\Requests\ZoneCreateRequest;
use App\Http\Requests\ZoneUpdateRequest;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Yajra\Datatables\Datatables;

class ZoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('zone.index');
    }

    public function create(): View
    {
        return view('zone.create');
    }

    public function store(ZoneCreateRequest $request): RedirectResponse
    {
        $zone = new Zone();
        $zone->domain = $request->input('domain');
        $this->fillZoneFromRequest($zone, $request);
        $zone->save();

        return redirect()->route('zones.index')
            ->with('success', __('zone/messages.create.success'));
    }

    private function fillZoneFromRequest(Zone $zone, Request $request): void
    {
        $zone->reverse_zone = Zone::isReverseZoneName($zone->domain);
        if ($request->input('zone_type') === 'secondary-zone') {
            $zone->server = $request->input('server');
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

    public function show(Zone $zone): View
    {
        return view('zone.show')
            ->with('zone', $zone);
    }

    public function edit(Zone $zone): View
    {
        return view('zone.edit')
            ->with('zone', $zone);
    }

    public function update(ZoneUpdateRequest $request, Zone $zone): RedirectResponse
    {
        // if it's a Master zone, assign new Serial Number and flag pending changes.
        if ($zone->isPrimary()) {
            $zone->getNewSerialNumber();
            $zone->setPendingChanges();
        }

        $this->fillZoneFromRequest($zone, $request);

        $zone->saveOrFail();

        return redirect()->route('zones.index')
            ->with('success', __('zone/messages.update.success'));
    }

    public function destroy(Zone $zone): RedirectResponse
    {
        $zone->delete();

        return redirect()->route('zones.index')
            ->with('success', __('zone/messages.delete.success'));
    }

    public function data(DataTables $datatable): JsonResponse
    {
        $zones = Zone::withCount('records')
            ->orderBy('domain', 'ASC');

        return $datatable->eloquent($zones)
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
