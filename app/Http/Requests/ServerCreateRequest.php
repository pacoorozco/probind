<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ServerCreateRequest extends Request
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
        return [
            'hostname'     => 'required|string|unique:servers',
            'ip_address'   => 'required|ip|unique:servers',
            'type'         => 'required|in:master,slave',
            'ns_record'    => 'sometimes|boolean',
            'push_updates' => 'sometimes|boolean',
            'directory'    => 'required_if:push_updates,1|string',
            'template'     => 'required_if:push_updates,1|string',
            'script'       => 'required_if:push_updates,1|string',
            'active'       => 'required|boolean'

        ];
    }
}
