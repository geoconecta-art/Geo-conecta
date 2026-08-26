<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PromotedRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ine' => [
                'nullable',
                'string',
                'size:18',
                Rule::unique('people')->ignore($this->id),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('people')->ignore($this->id),
            ],
        ];
    }

    public function messages()
    {
        return [
            'ine.unique' => 'Ya existe una persona registrada con la Clave de Elector ingresada. No es posible realizar el registro.',
            'name.unique' => 'Ya existe una persona registrada con el Nombre ingresado. No es posible realizar el registro.',
        ];
    }


    public function attributes()
    {
        return [
            'ine' => 'Clave de Elector',
        ];
    }
}

