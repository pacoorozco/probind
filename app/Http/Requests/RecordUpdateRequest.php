<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Record;

class RecordUpdateRequest extends Request
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
        $validInputTypes = join(',', array_keys(Record::$validInputTypes));

        return [
            'name'     => 'required|string',
            'ttl'      => 'integer|min:0|max:2147483647',
            'type'     => 'required|string|in:' . $validInputTypes,
            'priority' => 'required_if:type,MX,SRV|integer|min:0|max:65535',
            'data'     => 'required|string'
        ];
    }
}
