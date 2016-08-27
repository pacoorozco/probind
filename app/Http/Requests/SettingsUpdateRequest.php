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
 *  @author      Paco Orozco <paco@pacoorozco.info>
 *  @copyright   2016 Paco Orozco
 *  @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *  @link        https://github.com/pacoorozco/probind
 *
 */

namespace App\Http\Requests;

class SettingsUpdateRequest extends Request
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'zone_default_mname'        => 'required|string',
            'zone_default_rname'        => 'required|email',
            'zone_default_refresh'      => 'required|integer',
            'zone_default_retry'        => 'required|integer',
            'zone_default_expire'       => 'required|integer',
            'zone_default_negative_ttl' => 'required|integer|min:0|max:2147483647',
            'zone_default_default_ttl'  => 'required|integer|min:0|max:2147483647',

            'ssh_default_user'        => 'required|string',
            'ssh_default_key'         => 'required|string',
            'ssh_default_port'        => 'required|integer|min:1|max:65535',
            'ssh_default_remote_path' => 'required|string',
        ];
    }
}
