<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Zone;
use App\Record;
use App\Http\Requests;
use App\Http\Requests\RecordCreateRequest;
use App\Http\Requests\RecordUpdateRequest;
use Yajra\Datatables\Datatables;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Zone $zone
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function store(RecordCreateRequest $request, Zone $zone)
    {
        $zone->records()->create($request->all());

        return redirect()->route('zones.records.index', $zone)
            ->with('success', trans('record/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Zone $zone
     * @param  Record $record
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(RecordUpdateRequest $request, Zone $zone, Record $record)
    {
        $record->fill($request->all())->save();

        return redirect()->route('zones.records.index', $zone)
            ->with('success', trans('record/messages.update.success'));
    }

    /**
     * Remove record page.
     *
     * @param Zone $zone
     * @param Record $record
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $zone, Record $record)
    {
        $record->delete();

        return redirect()->route('zones.records.index', $zone)
            ->with('success', trans('record/messages.delete.success'));
    }

    /**
     * Show a list of all the levels formatted for Datatables.
     *
     * @param Request $request
     * @param Datatables $dataTable
     * @param Zone $zone
     * @return Datatables JsonResponse
     */
    public function data(Request $request, Datatables $dataTable, Zone $zone)
    {
        // Disable this query if isn't AJAX
        if (!$request->ajax()) {
            abort(400);
        }

        $records = $zone->records();

        return $dataTable::of($records)
            ->addColumn('actions', function (Record $record) {
                return view('record._actions')
                    ->with('zone', $record->zone)
                    ->with('record', $record)
                    ->render();
            })
            ->removeColumn('id')
            ->make(true);
    }
}
