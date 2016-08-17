<?php

namespace App\Http\Controllers;

use App\Record;
use App\Server;
use App\User;
use App\Zone;

class DashboardController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['servers'] = Server::all()->count();
        $data['zones'] = Zone::all()->count();
        $data['records'] = Record::all()->count();
        $data['users'] = User::all()->count();

        return view('dashboard.index')
            ->with('data', $data);
    }
}
