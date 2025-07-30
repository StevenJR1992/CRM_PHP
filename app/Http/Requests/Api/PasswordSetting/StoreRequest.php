<?php

namespace App\Http\Requests\Api\PasswordSetting;

use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
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
        $rules = [
            'lower_case' => 'required',
            'upper_case' => 'required',
            'special_character' => 'required',
            'number' => 'required',
            'password_length' => 'required'
        ];

        return $rules;
    }
}
