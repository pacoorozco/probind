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

use App\Http\Requests\RecordCreateRequest;
use App\Http\Requests\RecordUpdateRequest;
use App\Models\ResourceRecord;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class ResourceRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Zone $zone): View
    {
        return view('record.index')
            ->with('zone', $zone);
    }

    public function create(Zone $zone): View
    {
        return view('record.create')
            ->with('zone', $zone);
    }

    public function store(Zone $zone, RecordCreateRequest $request): RedirectResponse
    {
        $record = ResourceRecord::make([
            'name' => $request->name(),
            'ttl' => $request->ttl(),
            'type' => $request->type(),
            'data' => $request->data(),
        ]);
        $zone->records()->save($record);
        $zone->has_modifications = true;
        $zone->save();

        return redirect()->route('zones.records.index', ['zone' => $zone])
            ->with('success', __('record/messages.create.success'));
    }

    public function show(Zone $zone, ResourceRecord $record): View
    {
        return view('record.show')
            ->with('zone', $zone)
            ->with('record', $record);
    }

    public function edit(Zone $zone, ResourceRecord $record): View
    {
        return view('record.edit')
            ->with('zone', $zone)
            ->with('record', $record);
    }

    public function update(RecordUpdateRequest $request, Zone $zone, ResourceRecord $record): RedirectResponse
    {
        $zone->has_modifications = true;
        $zone->save();

        $record->update([
            'ttl' => $request->ttl(),
            'data' => $request->data(),
        ]);

        return redirect()->route('zones.records.index', ['zone' => $zone])
            ->with('success', __('record/messages.update.success'));
    }

    public function delete(Zone $zone, ResourceRecord $record): View
    {
        return view('record.delete')
            ->with('zone', $zone)
            ->with('record', $record);
    }

    public function destroy(Zone $zone, ResourceRecord $record): RedirectResponse
    {
        $zone = $record->zone()->first();
        $zone->has_modifications = true;
        $zone->save();

        $record->delete();

        return redirect()->route('zones.records.index', ['zone' => $zone])
            ->with('success', __('record/messages.delete.success'));
    }

    public function data(DataTables $datatable, Zone $zone): JsonResponse
    {
        $records = $zone->records();

        return $datatable->eloquent($records)
            ->addColumn('actions', function (ResourceRecord $record) {
                return view('record._actions')
                    ->with('zone', $record->zone)
                    ->with('record', $record)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->toJson();
    }
}
