<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CorporationRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('corporations')->ignore($this->id),
            ],
            'manager' => [
                'required',
                'string',
                Rule::unique('corporations')->ignore($this->id),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'Ya existe una corporación registrada con el Nombre ingresado. No es posible realizar el registro.',
            'manager.unique' => 'Ya existe una corporación registrada con el Representante ingresado. No es posible realizar el registro.',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'Nombre',
            'manages' => 'Representante',
        ];
    }
}

