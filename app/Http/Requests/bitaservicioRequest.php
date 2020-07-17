<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class bitaservicioRequest extends FormRequest
{
    public function messages()
    {
        return [
            'periodo_id1.required'       => 'Año de registro del servicio es obligatorio.',
            'mes_id1.required'           => 'Mes de registro del servicio es obligatorio.',
            'dia_id1.required'           => 'Día de registro del servicio es obligatorio.',
            'sp_nomb.required'           => 'El servidor público que realizo el servicio es obligatorio',
            'km_inicial.required'        => 'Kilometraje inicial es obligatorio.',
            'km_inicial.numeric'         => 'Kilometraje inicial debe ser númerico.',
            'km_final.required'          => 'Kilometraje final es obligatorio.',
            'km_final.numeric'           => 'Kilometraje final debe ser númerico.',            
            'servcio_lugar.required'     => 'Destino del servicio es obligatorio.',
            'servcio_lugar.min'          => 'Destino del servicio es de mínimo 1 carácter.',
            'servcio_lugar.max'          => 'Destino del servicio es de máximo 250 carácteres.',
            'servicio_hrsalida.required' => 'Hr. de salida del servicio es obligatorio.',
            'servicio_hrregreso.required'=> 'Hr. de regreso del servicio es obligatorio.'
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
            'periodo_id1'       => 'required',
            'mes_id1'           => 'required',
            'dia_id1'           => 'required',
            'sp_nomb'           => 'required|min:1|max:80',
            'km_inicial'        => 'required',
            'km_final'          => 'required',
            //'km_final'          => 'max:km_incial',
            'servicio_lugar'    => 'required|min:1|max:250',
            'servicio_hrsalida' => 'required|min:1|max:5',
            'servicio_hrregreso'=> 'required|min:1|max:5'   
                     
            //'iap_foto2'    => 'required|image'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
