<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class bancosRequest extends FormRequest
{
    public function messages()
    {
        return [
            'banco_desc.min'      => 'El nombre del banco es de mínimo 1 caracteres.',
            'banco_desc.max'      => 'El nombre del banco es de máximo 50 caracteres.',
            'banco_desc.required' => 'El nombre del banco es obligatorio.'
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
            'banco_desc' => 'required|min:1|max:50'
            //'trx_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
}
