<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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

            'username' => 'required|string|unique:users',
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users',
            'password' => 'required|alpha_num|min:6|confirmed',

        ];
    }
}
