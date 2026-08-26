<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        
        return [
            'password' => 'nullable|confirmed',

            'email' => [
                'required',
                'email',
                'max:50',
                Rule::unique('users')->ignore($this->route('user'))->whereNull('deleted_at'),
                // Rule::unique('users')->ignore($this->id, 'id')
                //     ->where(function ($query) {
                //     return $query->whereNull('deleted_at');
                // }),
                
            ],

        ];
    }

    public function messages()
    {
        return [
            'password.confirmed' => 'El campo Confirmar Contraseña no coincide.',
        ];
    }

    public function attributes()
    {
        return [
            'password' => 'Contraseña',
            'email' => 'Email',
        ];
    }
}
