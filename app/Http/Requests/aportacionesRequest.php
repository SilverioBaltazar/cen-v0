<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class aportacionesRequest extends FormRequest
{
    public function messages()
    { 
        return [
            //'iap_desc.required'      => 'El nombre de la IAP es obligatorio.',
            'periodo_id.required'      => 'El periodo es obligatoria.',
            'cliente_id.required'      => 'Cliente es obligatorio.',            
            'emp_id.required'          => 'Empleado es obligatorio.',            
            'fpago_id.required'        => 'Forma de pago es obligatorio.',                        
            'banco_id.requered'        => 'Banco es obligatorio.',            
            'factura_folio.requered'   => 'Factura de venta a pagar es obligatorio.',            
            'factura_folio.numeric'    => 'Factura de venta debe ser numerica.',            
            'apor_concepto.required'   => 'Concepto de la aportación.',
            'apor_importe.numeric'     => 'Importe de la aportación debe ser numerica.',            
            'apor_importe.required'    => 'Importe de la aportacion.',
            'apor_fecproxpago.required'=> 'Fecha de proximo pago es obligatoria'
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
            'banco_id'        => 'required',
            'periodo_id'      => 'required',
            'cliente_id'      => 'required',            
            'emp_id'          => 'required',            
            'fpago_id'        => 'required',            
            'factura_folio'   => 'required',            
            'apor_concepto'   => 'required|min:1|max:100',
            'apor_importe'    => 'required',
            'apor_fecproxpago'=> 'required'
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc'    => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
