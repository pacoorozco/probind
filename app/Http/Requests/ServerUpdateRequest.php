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

use App\Server;

class ServerUpdateRequest extends Request
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
        $zone = $this->route('server');
        $validServerTypes = join(',', array_values(Server::$validServerTypes));

        return [
            'hostname'     => 'required|string|unique:servers,hostname,' . $zone->id,
            'ip_address'   => 'required|ip|unique:servers,ip_address,' . $zone->id,
            'type'         => 'required|in:' . $validServerTypes,
            'ns_record'    => 'sometimes|boolean',
            'active'       => 'required|boolean',
            'push_updates' => 'sometimes|boolean'
        ];
    }
}
