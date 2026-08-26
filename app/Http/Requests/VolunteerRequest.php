<?php

namespace App\Http\Requests;

use App\Models\Person;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Rule as ValidationRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\FormRequest;

class VolunteerRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ine' => [
                'string',
                'size:18',
                Rule::unique('people')->ignore($this->id),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('people')->ignore($this->id),
            ],
            'v_type' => [
                new ValidateNumberOfPeople,
            ]
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

class ValidateNumberOfPeople implements ValidationRule
{
    public function passes($attribute, $value)
    {
        // Verifica si el número de registros es menor que 100
        $count = DB::table('people')
            ->whereNull('deleted_at')
            ->where('type', Person::VOLUNTARIO)
            ->count();
            
        return $count < 100;
    }

    public function message()
    {
        return 'No se pueden crear más personas, se ha alcanzado el límite máximo.';
    }
}

