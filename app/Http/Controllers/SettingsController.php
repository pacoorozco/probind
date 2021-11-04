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

use App\Http\Requests\SettingsUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Larapacks\Setting\Setting;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('settings.index');
    }

    public function update(SettingsUpdateRequest $request): RedirectResponse
    {
        setting()->set([
            'zone_default_mname' => $request->primaryServer(),
            'zone_default_rname' => $request->hostmasterEmail(),
            'zone_default_refresh' => $request->defaultRefresh(),
            'zone_default_retry' => $request->defaultRetry(),
            'zone_default_expire' => $request->defaultExpire(),
            'zone_default_negative_ttl' => $request->defaultNegativeTTL(),
            'zone_default_default_ttl' => $request->defaultZoneTTL(),

            'ssh_default_user' => $request->defaultSSHUser(),
            'ssh_default_key' => $request->defaultSSHKey(),
            'ssh_default_port' => $request->defaultSSHPort(),
            'ssh_default_remote_path' => $request->defaultSSHRemotePath(),
        ]);

        return redirect()->route('settings.index')
            ->with('success', __('settings/messages.update.success'));
    }
}
