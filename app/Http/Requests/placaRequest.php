<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class placaRequest extends FormRequest
{
    public function messages()
    {
        return [
            'placa_placa.required'     => 'Las placas es obligatorio.',
            'placa_desc.required'      => 'Descripcion del automovil es obligatorio.',
            'placa_desc.min'           => 'Descripcion del automovil es de mínimo 1 caracter.',
            'placa_desc.max'           => 'Descripcion del automovil es de máximo 100 caracteres.',
            'placa_cilindros.required' => 'El número de cilindros es obligatorio.',
            //'placa_cilindros.min'    => 'El número de cilindros es es de mínimo 1 caracter numericos.',
            //'placa_cilindros.max'    => 'El número de cilindros es es de máximo 3 caracteres numericos.',
            'marca_id.required'        => 'La marca es obligatoria.',
            'tipog_id.required'        => 'El tipo de gasto es obligatorio.',            
            'tipoo_id.required'        => 'El tipo de operación admon. es obligatorio.',
            'dependencia_id.required'  => 'Unidad administrativa responsable es obligatoria.',
            'placas_gasolina.required' => 'Tipo de gasina es obligatorio.',
            'placa_inventario.required'=> 'No. de inventario es obligatorio.',
            'placa_modelo2.required'   => 'Modelo es obligatorio.',
            'placa_modelo2.numeric'    => 'Modelo es númerico' 
            //'iap_cp.numeric' => 'El Código postal debe ser numerico.',            
            //'placa_status1.required' => 'El estado  es obligatorio.'
            //'iap_foto1.required' => 'La imagen es obligatoria'
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
            'placa_placa'     => 'required',            
            'placa_desc'      => 'required|min:1|max:100',
            'dependencia_id'  => 'required',
            'placa_gasolina'  => 'required',
            'placa_inventario'=> 'required',
            'placa_modelo2'   => 'required|numeric',
            'placa_cilindros' => 'required|numeric',
            'marca_id'        => 'required',
            'tipog_id'        => 'required',
            'tipoo_id'        => 'required'
            //'iap_regcons'  => 'required|min:1|max:50',
            //'iap_rfc'      => 'required|min:18|max:18',
            //'iap_cp'       => 'required|numeric|min:5|min:5',            
            //'iap_feccons'  => 'required',
            //'iap_telefono' => 'required|min:1|max:60',
            //'iap_email'    => 'required|email|min:1|max:60',
            //'iap_pres'     => 'required|min:1|max:80',
            //'iap_replegal' => 'required|min:1|max:80',
            //'iap_srio'     => 'required|min:1|max:80',
            //'iap_tesorero' => 'required|min:1|max:80',
            //'iap_status'   => 'required'
            //'iap_foto1'    => 'required|image',
            //'iap_foto2'    => 'required|image'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
