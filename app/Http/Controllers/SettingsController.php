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

use App\Http\Requests\SettingsUpdateRequest;
use Setting;

class SettingsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = Setting::all();

        return view('settings.index')
            ->with('settings', $settings);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SettingsUpdateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SettingsUpdateRequest $request)
    {
        Setting::set($request->except('_token', '_method'));

        return redirect()->route('settings.index')
            ->with('success', trans('settings/messages.update.success'));
    }
}
