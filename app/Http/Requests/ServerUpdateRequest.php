<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ServerUpdateRequest extends Request
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
        $zone = $this->route('server');

        return [
            'hostname'     => 'required|string|unique:servers,hostname,' . $zone->id,
            'ip_address'   => 'required|ip|unique:servers,ip_address,' . $zone->id,
            'type'         => 'required|in:master,slave',
            'ns_record'    => 'sometimes|boolean',
            'active'       => 'required|boolean',
            'push_updates' => 'sometimes|boolean',
            'directory'    => 'required_if:push_updates,1|string',
            'template'     => 'required_if:push_updates,1|string',
            'script'       => 'required_if:push_updates,1|string',
            'active'       => 'required|boolean'
        ];
    }
}
