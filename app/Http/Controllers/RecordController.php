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

use App\Http\Requests\RecordCreateRequest;
use App\Http\Requests\RecordUpdateRequest;
use App\Record;
use App\Zone;
use DataTables;

class RecordController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Zone $zone
     *
     * @return \Illuminate\View\View
     */
    public function index(Zone $zone)
    {
        return view('record.index')
            ->with('zone', $zone);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Zone $zone
     *
     * @return \Illuminate\View\View
     */
    public function create(Zone $zone)
    {
        return view('record.create')
            ->with('zone', $zone);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RecordCreateRequest $request
     * @param  Zone $zone
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RecordCreateRequest $request, Zone $zone)
    {
        $record = new Record();

        // Only MX & SRV types use Priority
        $record->priority = ($request->input('type') == 'MX' || $request->input('type') == 'SRV')
            ? $request->input('priority')
            : null;

        $record->fill($request->all());

        $zone->records()->save($record);

        return redirect()->route('zones.records.index', ['zone' => $zone])
            ->with('success', trans('record/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Zone $zone
     * @param  Record $record
     *
     * @return \Illuminate\View\View
     */
    public function show(Zone $zone, Record $record)
    {
        return view('record.show')
            ->with('zone', $zone)
            ->with('record', $record);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Zone $zone
     * @param  Record $record
     *
     * @return \Illuminate\View\View
     */
    public function edit(Zone $zone, Record $record)
    {
        return view('record.edit')
            ->with('zone', $zone)
            ->with('record', $record);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RecordUpdateRequest $request
     * @param  Zone $zone
     * @param  Record $record
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RecordUpdateRequest $request, Zone $zone, Record $record)
    {
        // Only MX & SRV types use Priority
        $record->priority = ($record->type == 'MX' || $record->type == 'SRV')
            ? $request->input('priority')
            : null;

        $record->fill($request->except('type'))->save();

        return redirect()->route('zones.records.index', ['zone' => $zone])
            ->with('success', trans('record/messages.update.success'));
    }

    /**
     * Remove record page.
     *
     * @param Zone $zone
     * @param Record $record
     *
     * @return \Illuminate\View\View
     */
    public function delete(Zone $zone, Record $record)
    {
        return view('record.delete')
            ->with('zone', $zone)
            ->with('record', $record);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Zone $zone
     * @param Record $record
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Zone $zone, Record $record)
    {
        $record->delete();

        return redirect()->route('zones.records.index', ['zone' => $zone])
            ->with('success', trans('record/messages.delete.success'));
    }

    /**
     * Show a list of all the levels formatted for DataTables.
     *
     * @param DataTables $dataTable
     * @param Zone $zone
     *
     * @return DataTables JsonResponse
     */
    public function data(DataTables $dataTable, Zone $zone)
    {
        $records = $zone->records();

        return $dataTable::of($records)
            ->addColumn('actions', function (Record $record) {
                return view('record._actions')
                    ->with('zone', $record->zone)
                    ->with('record', $record)
                    ->render();
            })
            ->rawColumns(['actions'])
            ->removeColumn('id')
            ->make(true);
    }
}
