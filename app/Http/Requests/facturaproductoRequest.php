<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class facturaproductoRequest extends FormRequest
{
    public function messages()
    {
        return [
            'codigo_barras.required' => 'Codigo de barras del producto es obligatorio.',
            'cantidad.numeric'       => 'Cantidad de productos debe ser numerico.',            
            'cantidad.required'      => 'Cantidad de productos es obligatorio'
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
            'codigo_barras' => 'required',
            'cantidad'      => 'required'
            //'rubro_desc'   => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
