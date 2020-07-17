<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class formaspagoRequest extends FormRequest
{
    public function messages()
    {
        return [
            'fpago_desc.min'      => 'Forma de pago es de mínimo 1 carácteres.',
            'fpago_desc.max'      => 'Forma de pago es de máximo 80 carácteres.',
            'fpago_desc.required' => 'Forma de pago es obligatorio.'
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
            'fpago_desc' => 'required|min:1|max:50'
            //'trx_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
}
