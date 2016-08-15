<?php

namespace App\Http\Requests;

class ZoneUpdateRequest extends Request
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
        $zone = $this->route('zone');

        return [
            'domain' => 'required|string|unique:zones,domain,' . $zone->id,
            'master' => 'sometimes|required|ip',
        ];
    }
}
