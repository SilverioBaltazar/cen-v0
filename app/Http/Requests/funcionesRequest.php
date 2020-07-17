<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class funcionesRequest extends FormRequest
{
    public function messages()
    {
        return [
            'funcion_id.required'   => 'Id de la funcion es obligatoria.',
            'funcion_desc.required' => 'El nombre de la funcion del proceso es obligatorio.',
            'funcion_desc.min'      => 'El nombre de la funcion del proceso es de mínimo 1 caracteres.',
            'funcion_desc.max'      => 'El nombre de la funcion del proceso es de máximo 100 caracteres.',
            'funcion_desc.required' => 'El nombre de la funcion del proceso es obligatorio.',
            'funcion_desc.regex'    => 'El nombre de la funcion del proceso contiene caracteres inválidos.'
            //'proceso_id.required'   => 'Proceso es obligatorio.'
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
            'funcion_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
            //'proceso_id'   => 'required'
        ];
    }
}
