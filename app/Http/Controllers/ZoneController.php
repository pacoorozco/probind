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

use App\Enums\ZoneType;
use App\Http\Requests\ZoneCreateRequest;
use App\Http\Requests\ZoneRequest;
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
        $zone->domain = $request->domain();
        $zone->reverse_zone = Zone::isReverseZoneName($zone->domain);

        if ($request->zoneType() == ZoneType::Primary) {
            $zone->server = null;
            $this->fillCustomSettingsFromRequest($zone, $request);
            $zone->serial = $zone->calculateNewSerialNumber();
        } else {
            $zone->server = $request->serverAddress();
        }

        $zone->has_modifications = true;
        $zone->save();

        return redirect()->route('zones.index')
            ->with('success', __('zone/messages.create.success'));
    }

    private function fillCustomSettingsFromRequest(Zone $zone, ZoneRequest $request): void
    {
        $zone->custom_settings = $request->customizedSettings();
        if ($request->customizedSettings() === true) {
            $zone->fill([
                'refresh' => $request->refresh(),
                'retry' => $request->retry(),
                'expire' => $request->expire(),
                'negative_ttl' => $request->negativeTTL(),
                'default_ttl' => $request->defaultTTL(),
            ]);
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
        if ($zone->isPrimary()) {
            $this->fillCustomSettingsFromRequest($zone, $request);
            $zone->serial = $zone->calculateNewSerialNumber();
        } else {
            $zone->server = $request->serverAddress();
        }

        $zone->has_modifications = true;
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
