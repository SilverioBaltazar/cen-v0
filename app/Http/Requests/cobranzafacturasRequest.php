<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class cobranzafacturasRequest extends FormRequest
{
    public function messages()
    {
        return [
            'perr.required'              => 'Periodo fiscal es obligatorio.',
            'mess.required'              => 'El mes es obligatorio.',
            //'visita_tipo1.required'    => 'Seleccionar el tipo, jurídica, asistencial o contable es obligatorio.'
            //'visita_tipo2.required'    => 'Seleccionar el formato del reporte de salida, Excel o PDF.'
            'empp.required'              => 'Seleccionar cobrador es obligatorio.'
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
            //'iap_desc.'      => 'required|min:1|max:100',
            'perr'             => 'required',
            'mess'             => 'required',
            //'visita_tipo1'   => 'required'
            'empp'             => 'required'
        ];
    }
}
