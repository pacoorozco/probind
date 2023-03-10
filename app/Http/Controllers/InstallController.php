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

use App\Repositories\EnvironmentRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InstallController extends Controller
{
    public function index(): View
    {
        return view('install.welcome');
    }

    public function showDatabaseForm(): View
    {
        $host = env('DB_HOST');
        $dbname = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        return view('install.database')
            ->with('dbname', $dbname)
            ->with('username', $username)
            ->with('password', $password)
            ->with('host', $host);
    }

    public function createDatabase(Request $request, EnvironmentRepository $environmentRepository): RedirectResponse
    {
        // Set config for migrations and seeds
        $connection = config('database.default');
        config([
            'database.connections.'.$connection.'.host' => $request->input('host'),
            'database.connections.'.$connection.'.database' => $request->input('dbname'),
            'database.connections.'.$connection.'.password' => $request->input('password'),
            'database.connections.'.$connection.'.username' => $request->input('username'),
        ]);

        // Update .env file
        $environmentRepository->setDatabaseSetting([
            'database' => $request->input('dbname'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'host' => $request->input('host'),
        ]);

        // Migrations and seeds
        try {
            Artisan::call('migrate:refresh');
            Artisan::call('db:seed');
        } catch (\Throwable $e) {
            return redirect()->route('install.database')
                ->with('error', __('installer.database.error-message'));
        }

        return redirect()->route('install.end');
    }

    public function end(): View
    {
        Storage::disk('local')->put('installed', config('app.version'));

        return view('install.end');
    }
}
