<?php

namespace App\Http\Requests;

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
                'string',
                Rule::unique('zones'),
            ],
            'zonefile' => [
                'required',
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
        return $this->file('zonefile');
    }
}
