<?php

namespace App\Http\Controllers;

use App\Server;
use App\Http\Requests\ServerCreateRequest;
use App\Http\Requests\ServerUpdateRequest;

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
     * @param  ServerCreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServerCreateRequest $request)
    {
        Server::create($request->all());

        return redirect()->route('servers.index')
            ->with('success', trans('server/messages.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $server = Server::findOrFail($id);
        return view('server.show')->with('server', $server);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $server = Server::findOrFail($id);
        return view('server.edit')->with('server', $server);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ServerUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServerUpdateRequest $request, $id)
    {
        $server = Server::findOrFail($id);
        $server->fill($request->all())->save();

        return redirect()->route('servers.index')
            ->with('success', trans('server/messages.update.success'));
    }

    /**
     * Remove level page.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $server = Server::findOrFail($id);
        return view('server/delete')->with('server', $server);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $server = Server::findOrFail($id);
        $server->delete();

        return redirect()->route('servers.index')
            ->with('success', trans('server/messages.delete.success'));
    }
}
