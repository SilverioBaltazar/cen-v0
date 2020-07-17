<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class cargaRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'proceso_desc.min' => 'El nombre del proceso es de mínimo 1 caracteres.',
            //'proceso_desc.max' => 'El nombre del proceso es de máximo 100 caracteres.',
            'tkpag_folaprob.required' => 'El folio de aprobación del ticket de pago es obligatorio.',
            'periodo_id1.required'    => 'El año de emisión del ticket de pago es obligatorio.',
            'mes_id1.required'        => 'El mes de emisión del ticket de pago es obligatorio.',
            'dia_id1.required'        => 'El día de emisión del ticket de pago es obligatorio.',
            'tkpag_importe.required'  => 'El importe del ticket de pago es obligatorio.',
            'tkpag_importe.numeric'   => 'El importe del ticket de pago debe ser númerico.',

            //'tkbomba_rfc.required'    => 'El rfc proveedor del ticket de bomba es obligatorio.',
            'periodo_id2.required'    => 'El año de emisión del ticket de bomba es obligatorio.',
            'mes_id2.required'        => 'El mes de emisión del ticket de bomba es obligatorio.',
            'dia_id2.required'        => 'El día de emisión del ticket de bomba es obligatorio.',
            'tkbomba_importe.required'=> 'El importe del ticket de bomba es obligatorio.',
            'tkbomba_importe.numeric' => 'El importe del ticket de bomba debe ser númerico.',
            'fp_id.required'          => 'Seleccionar forma de pago es obligatorio.'
            //'proceso_desc.regex' => 'El nombre del proceso contiene caracteres inválidos.'
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
            'tkpag_folaprob' => 'required',
            'periodo_id1'    => 'required',
            'mes_id1'        => 'required',
            'dia_id1'        => 'required',
            'tkpag_importe'  => 'required',

            //'tkbomba_rfc'    => 'required',
            'periodo_id2'    => 'required',
            'mes_id2'        => 'required',
            'dia_id2'        => 'required',
            'tkbomba_importe'=> 'required',
            'fp_id'          => 'required'
            //'proceso_desc' => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
}
