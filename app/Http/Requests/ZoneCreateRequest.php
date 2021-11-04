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
 *
 * @link        https://github.com/pacoorozco/probind
 */

namespace App\Http\Requests;

use App\Rules\FullyQualifiedDomainName;
use Illuminate\Validation\Rule;

class ZoneCreateRequest extends ZoneRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'domain' => [
                'required',
                new FullyQualifiedDomainName, Rule::unique('zones'),
            ],
        ];

        return array_merge($rules, parent::rules());
    }

    public function domain(): string
    {
        return $this->input('domain');
    }
}
