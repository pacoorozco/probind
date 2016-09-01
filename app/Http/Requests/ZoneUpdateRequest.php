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

namespace App\Http\Requests;

class ZoneUpdateRequest extends Request
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
        $zone = $this->route('zone');

        return [
            'domain'          => 'required|string|unique:zones,domain,' . $zone->id,
            'master'          => 'sometimes|required|ip',
            'custom_settings' => 'sometimes|boolean',
            'refresh'         => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
            'retry'           => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
            'expire'          => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
            'negative_ttl'    => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
            'default_ttl'     => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
        ];
    }
}
