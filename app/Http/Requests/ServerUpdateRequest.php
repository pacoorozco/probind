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

use App\Enums\ServerType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Validation\Rule;

class ServerUpdateRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hostname' => [
                'required',
                'string',
                Rule::unique('servers')->ignore($this->route('server')),
            ],
            'ip_address' => [
                'required',
                'ip',
                Rule::unique('servers')->ignore($this->route('server')),
            ],
            'type' => [
                'required',
                new EnumValue(ServerType::class),
            ],
            'ns_record' => [
                'required',
                'boolean',
            ],
            'push_updates' => [
                'required',
                'boolean',
            ],
            'active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function hostname(): string
    {
        return $this->input('hostname');
    }

    public function ipAddress(): string
    {
        return $this->input('ip_address');
    }

    public function type(): ServerType
    {
        $serverTypeName = $this->input('type');

        return ServerType::fromValue($serverTypeName);
    }

    public function requiresNSRecord(): bool
    {
        return (bool) $this->input('ns_record');
    }

    public function requiresUpdatePushes(): bool
    {
        return (bool) $this->input('push_updates');
    }

    public function enabled(): bool
    {
        return (bool) $this->input('active');
    }
}
