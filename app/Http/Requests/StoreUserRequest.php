<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
        $rules =  [
            'name' => [
                'required',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')
            ],
            'password' => [
                'required',
                'min:8',
            ],
            'role_id' => [
                'required'
            ],
        ];

        if ($this->method() === 'PUT') {
            $rules =  [
                'name' => [
                    'required',
                    'max:255'
                ],
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($this->id),
                    'max:255'
                ],
                'role_id' => [
                    'required'
                ],
            ];
        }

        return $rules;
    }
}
