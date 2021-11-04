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

use App\Enums\ZoneType;
use BenSampo\Enum\Rules\EnumValue;

abstract class ZoneRequest extends Request
{
    public function zoneType(): ZoneType
    {
        return ZoneType::fromValue($this->input('zone_type'));
    }

    public function serverAddress(): string
    {
        return $this->input('server', '');
    }

    public function customizedSettings(): bool
    {
        return (bool) $this->input('custom_settings');
    }

    public function refresh(): ?int
    {
        return (int) $this->input('refresh');
    }

    public function retry(): ?int
    {
        return (int) $this->input('retry');
    }

    public function expire(): ?int
    {
        return (int) $this->input('expire');
    }

    public function negativeTTL(): ?int
    {
        return (int) $this->input('negative_ttl');
    }

    public function defaultTTL(): ?int
    {
        return (int) $this->input('default_ttl');
    }

    protected function rules(): array
    {
        return [
            'zone_type' => [
                'required',
                new EnumValue(ZoneType::class),
            ],
            'server' => [
                'required_if:zone_type,' . ZoneType::Secondary,
                'ip',
            ],
            'custom_settings' => [
                'sometimes',
                'boolean',
            ],
            'refresh' => [
                'required_if:custom_settings,1',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'retry' => [
                'required_if:custom_settings,1',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'expire' => [
                'required_if:custom_settings,1',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'negative_ttl' => [
                'required_if:custom_settings,1',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'default_ttl' => [
                'required_if:custom_settings,1',
                'integer',
                'min:0',
                'max:2147483647',
            ],
        ];
    }
}
