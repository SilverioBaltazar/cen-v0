<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class recibo01Request extends FormRequest
{
    public function messages()
    {
        return [
            //'placa_id.required'    => 'Código de las placas es obligatorio.',
            //'recibo_ki.required'   => 'Kilometraje inicial es obligatorio.',
            //'recibo_ki.numeric'    => 'Kilometraje inicial debe ser númerico.',
            //'recibo_kf.required'   => 'Kilometraje final es obligatorio.',
            //'recibo_ki.numeric'    => 'Kilometraje final debe ser númerico.',
            //'periodo_id.required'  => 'El periodo fiscal es obligatorio.',
            //'mes_id.requered'      => 'El mes a reportar es obligatorio.',
            'recibo_folio.required'  => 'Folio de recibo de bitacora a comprobar es obligatorio.'            
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
            //'placa_id'    => 'required',            
            //'recibo_ki'   => 'required',
            //'recibo_kf'   => 'required',
            //'periodo_id'  => 'required',
            //'mes_id'      => 'required',
            'recibo_folio'  => 'required'
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
