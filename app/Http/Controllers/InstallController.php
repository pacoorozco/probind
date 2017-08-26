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

use App\Repositories\EnvironmentRepository;
use Artisan;
use Exception;
use Illuminate\Http\Request;


class InstallController extends Controller
{
    /**
     * Display the installer welcome page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('install.welcome');

    }

    /**
     * Show Connection Settings Form
     *
     * @return \Illuminate\View\View
     */
    public function showDatabaseForm()
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

    /**
     * Manage Database form submission.
     *
     * @param Request               $request
     * @param EnvironmentRepository $environmentRepository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createDatabase(Request $request, EnvironmentRepository $environmentRepository)
    {
        // Set config for migrations and seeds
        $connection = config('database.default');
        config([
            'database.connections.' . $connection . '.host'     => $request->input('host'),
            'database.connections.' . $connection . '.database' => $request->input('dbname'),
            'database.connections.' . $connection . '.password' => $request->input('password'),
            'database.connections.' . $connection . '.username' => $request->input('username'),
        ]);

        // Update .env file
        $environmentRepository->setDatabaseSetting([
            'database' => $request->input('dbname'),
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'host'     => $request->input('host')
        ]);

        // Migrations and seeds
        try {
            Artisan::call('migrate:refresh');
            Artisan::call('db:seed');
        } catch (Exception $e) {
            return redirect()->route('Installer::database')
                ->with('error', trans('installer.database.error-message'));
        }

        return redirect()->route('Installer::end');
    }

    public function end()
    {
        \Storage::disk('local')->put('installed', config('app.version'));

        return view('install.end');
    }
}
