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

class SettingsUpdateRequest extends Request
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zone_default_mname' => [
                'required',
                'string',
            ],
            'zone_default_rname' => [
                'required',
                'email',
            ],
            'zone_default_refresh' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'zone_default_retry' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'zone_default_expire' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'zone_default_negative_ttl' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],
            'zone_default_default_ttl' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],

            'ssh_default_user' => [
                'required',
                'string',
            ],
            'ssh_default_key' => [
                'required',
                'string',
            ],
            'ssh_default_port' => [
                'required',
                'integer',
                'min:1',
                'max:65535',
            ],
            'ssh_default_remote_path' => [
                'required',
                'string',
            ],
        ];
    }

    public function primaryServer(): string
    {
        return $this->input('zone_default_mname');
    }

    public function hostmasterEmail(): string
    {
        return $this->input('zone_default_rname');
    }

    public function defaultRefresh(): int
    {
        return $this->input('zone_default_refresh');
    }

    public function defaultRetry(): int
    {
        return $this->input('zone_default_retry');
    }

    public function defaultExpire(): int
    {
        return $this->input('zone_default_expire');
    }

    public function defaultNegativeTTL(): int
    {
        return $this->input('zone_default_negative_ttl');
    }

    public function defaultZoneTTL(): int
    {
        return $this->input('zone_default_default_ttl');
    }

    public function defaultSSHUser(): string
    {
        return $this->input('ssh_default_user');
    }

    public function defaultSSHKey(): string
    {
        return $this->input('ssh_default_key');
    }

    public function defaultSSHPort(): string
    {
        return $this->input('ssh_default_port');
    }

    public function defaultSSHRemotePath(): string
    {
        return $this->input('ssh_default_remote_path');
    }
}
