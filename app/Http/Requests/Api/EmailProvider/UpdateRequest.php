<?php

namespace App\Http\Requests\Api\EmailProvider;

use App\Http\Requests\Api\BaseRequest;

class UpdateRequest extends BaseRequest
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
            'name' => 'required',
            'auth_token' => 'required',
            'subdomain' => 'required'
        ];
    }
}