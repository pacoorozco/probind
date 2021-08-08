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

use App\Rules\ResourceRecordName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecordCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var \App\Models\Zone $zone */
        $zone = $this->route('zone');

        return [
            'name' => [
                'required',
                new ResourceRecordName(),
            ],
            'type' => [
                'required',
                Rule::in($zone->getValidRecordTypesForThisZone()),
            ],
            'data' => [
                'required',
                'string',
            ],
            'ttl' => 'nullable|integer|min:0|max:2147483647',
        ];
    }

    public function name(): string
    {
        return $this->input('name');
    }

    public function type(): string
    {
        return $this->input('type');
    }

    public function data(): string
    {
        return $this->input('data');
    }

    public function ttl(): ?int
    {
        return $this->input('ttl');
    }
}
