<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class tipocreditoRequest extends FormRequest
{
    public function messages()
    {
        return [
            'tipocredito_desc.min'      => 'Tipo de crédito es de mínimo 1 caracteres.',
            'tipocredito_desc.max'      => 'Tipo de crédito es de máximo 80 caracteres.',
            'tipocredito_desc.required' => 'Tipo de crédito es obligatorio.',
            'tipocredito_dias.required' => 'Días de crédito es obligatorio.',
            'tipocredito_dias.numeric'  => 'Días de crédito debe ser númerico.'            
            //'trx_desc.regex' => 'El nombre de la función contiene caracteres inválidos.'
        ];
    }
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
            'tipocredito_desc' => 'required|min:1|max:80',
            'tipocredito_dias' => 'required'
            //'trx_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
}
