<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServerCreateRequest;
use App\Http\Requests\ServerUpdateRequest;
use App\Server;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class ServerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('server.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ServerCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ServerCreateRequest $request)
    {
        $server = new Server();

        // Deal with checkboxes
        $server->push_updates = $request->input('push_updates', false);
        $server->ns_record = $request->input('ns_record', false);
        $server->active = $request->input('active', false);

        $server->fill($request->all())->save();

        return redirect()->route('servers.index')
            ->with('success', trans('server/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Server $server
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ServerUpdateRequest $request, Server $server)
    {
        // First, deal with checkboxes
        $server->push_updates = $request->input('push_updates', false);
        $server->ns_record = $request->input('ns_record', false);
        $server->active = $request->input('active', false);

        $server->fill($request->all())->save();

        return redirect()->route('servers.index')
            ->with('success', trans('server/messages.update.success'));
    }

    /**
     * Remove level page.
     *
     * @param Server $server
     * @return \Illuminate\Http\Response
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
     * @param Request $request
     * @param Datatables $dataTable
     * @return Datatables JsonResponse
     */
    public function data(Request $request, Datatables $dataTable)
    {
        // Disable this query if isn't AJAX
        if ( ! $request->ajax()) {
            abort(400);
        }

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
