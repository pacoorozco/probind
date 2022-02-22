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

use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BulkUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        return view('tools.bulk_update');
    }

    public function update(): RedirectResponse
    {
        $zones = Zone::all();
        foreach ($zones as $zone) {
            $zone->save([
                'has_modifications' => true,
            ]);
        }

        return redirect()->route('home')
            ->with('success', __('tools/messages.bulk_update_success'));
    }
}
