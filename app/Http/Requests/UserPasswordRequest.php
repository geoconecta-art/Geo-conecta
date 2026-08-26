<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|confirmed',
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
            'password' => 'Contraseña'
        ];
    }
}
