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
            'domain'          => 'required|string|unique:zones,domain,' . $zone->id,
            'master'          => 'sometimes|required|ip',
            'custom_settings' => 'sometimes|boolean',
            'refresh'         => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
            'retry'           => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
            'expire'          => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
            'negative_ttl'    => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
            'default_ttl'     => 'required_if:custom_settings,1|integer|min:0|max:2147483647',
        ];
    }
}
