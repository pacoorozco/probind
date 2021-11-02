<?php
/**
 *  ProBIND v3 - Professional DNS management made easy.
 *
 *  Copyright (c) 2017 by Paco Orozco <paco@pacoorozco.info>
 *
 *  This file is part of some open source application.
 *
 *  Licensed under GNU General Public License 3.0.
 *  Some rights reserved. See LICENSE, AUTHORS.
 *
 * @author      Paco Orozco <paco@pacoorozco.info>
 * @copyright   2017 Paco Orozco
 * @license     GPL-3.0 <http://spdx.org/licenses/GPL-3.0>
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

abstract class ZoneRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'zone_type' => ['required', Rule::in(['primary-zone', 'secondary-zone'])],
            'server' => ['sometimes', 'required', 'ip'],
            'custom_settings' => ['sometimes', 'boolean'],
            'refresh' => ['required_if:custom_settings,1', 'integer', 'min:0', 'max:2147483647'],
            'retry' => ['required_if:custom_settings,1', 'integer', 'min:0', 'max:2147483647'],
            'expire' => ['required_if:custom_settings,1', 'integer', 'min:0', 'max:2147483647'],
            'negative_ttl' => ['required_if:custom_settings,1', 'integer', 'min:0', 'max:2147483647'],
            'default_ttl' => ['required_if:custom_settings,1', 'integer', 'min:0', 'max:2147483647'],
        ];
    }
}
