<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ventaclienteRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'placa_id.required'     => 'Código de las placas es obligatorio.',
            'cliente_id.required'     => 'Cliente es obligatorio.',
            'emp_id.required'         => 'Vendedor que hizo la venta es obligatorio.',
            'tipocredito_id.required' => 'Tipo de crédito es obligatorio.'            
            //'iap_cp.numeric'        => 'El Código postal debe ser numerico.',            
            //'iap_foto1.required'    => 'La imagen es obligatoria'
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
            'cliente_id'     => 'required',
            'emp_id'         => 'required',
            'tipocredito_id' => 'required'
            //'rubro_desc'   => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
