<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class facturaRequest extends FormRequest
{
    public function messages()
    {
        return [
            'cliente_id.required'                => 'Cliente es obligatorio.',
            'emp_id.required'                    => 'Vendedor que hizo la venta es obligatorio.',
            'tipocredito_id.required'            => 'Tipo de crédito es obligatorio.',            
            'efactura_montosubsidio.numeric'     => 'Monto del subsidio debe ser numerico.',            
            'efactura_montosubsidio.required'    => 'Monto del subsidio es obligatorio',
            'efactura_montoaportaciones.numeric' => 'Monto de la aportacion debe ser numerico.',            
            'efactura_montoaportaciones.required'=> 'Monto de la aportacion es obligatorio',
            'efactura_fecaportacion1.required'   => 'Fecha de la aportación es obligatoria'                        
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
            'cliente_id'                => 'required',
            'emp_id'                    => 'required',
            'tipocredito_id'            => 'required',
            'efactura_montosubsidio'    => 'required',
            'efactura_montoaportaciones'=> 'required',
            'efactura_fecaportacion1'   => 'required'
            //'rubro_desc'   => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
