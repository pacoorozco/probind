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
use Illuminate\Validation\Rule;

class ServerCreateRequest extends Request
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
            'hostname' => ['required', 'string', Rule::unique('servers')],
            'ip_address' => ['required', 'ip', Rule::unique('servers')],
            'type' => ['required', Rule::in(Server::$validServerTypes)],
            'ns_record' => ['required', 'boolean'],
            'push_updates' => ['required', 'boolean'],
            'active' => ['required', 'boolean'],
        ];
    }
}
