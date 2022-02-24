<?php
/*
 * Copyright (c) 2016-2022 Paco Orozco <paco@pacoorozco.info>
 *
 * This file is part of ProBIND v3.
 *
 * ProBIND v3 is free software: you can redistribute it and/or modify it under the terms of the GNU
 * General Public License as published by the Free Software Foundation, either version 3 of the
 * License, or any later version.
 *
 * ProBIND v3 is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with ProBIND v3. If not,
 * see <https://www.gnu.org/licenses/>.
 *
 */

namespace App\Http\Requests;

use App\Rules\FullyQualifiedDomainName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;

class ImportZoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'domain' => [
                'required',
                new FullyQualifiedDomainName,
                Rule::unique('zones'),
            ],
            'zonefile' => [
                'required',
                'file',
                'mimetypes:text/plain',
                'max:2048',
            ],
        ];
    }

    public function domain(): string
    {
        return $this->input('domain');
    }

    public function zoneFile(): ?UploadedFile
    {
        if ($this->file('zonefile') instanceof UploadedFile) {
            return $this->file('zonefile');
        }

        return null;
    }
}
