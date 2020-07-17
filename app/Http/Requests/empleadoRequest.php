<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class empleadoRequest extends FormRequest
{
    public function messages()
    {
        return [ 
            //'iap_id.required'         => 'La IAP es obligatorio.',
            'emp_ap.required'           => 'Apellido paterno es obligatorio.',
            'emp_ap.min'                => 'Apellido paterno es de mínimo 1 carácteres.',
            'emp_ap.max'                => 'Apellido paterno es de máximo 80 carácteres.',
            //'emp_am.required'         => 'Apellido materno es obligatorio.',
            //'emp_am.min'              => 'Apellido materno es de mínimo 1 carácteres.',
            //'emp_pm.max'              => 'Apellido materno es de máximo 80 carácteres.',
            'emp_nombres.required'      => 'Nombre(s) es obligatorio.',
            'emp_nombres.min'           => 'Nombre(s) es de mínimo 1 carácteres.',
            'emp_nombres.max'           => 'Nombre(s) es de máximo 80 carácteres.',     
            //'emp_curp.required'       => 'CURP es obligatorio.',
            //'emp_curp.min'            => 'CURP es de mínimo 18 carácteres.',
            //'emp_curp.max'            => 'CURP es de máximo 18 carácteres.',                       
            //'entidad_fed_id.required' => 'Entidad federativa es obligatoria.',
            //'municipio_id.required'   => 'Municipio es obligatorio.',            
            'emp_cp.required'           => 'Código postal es obligatorio.',
            'emp_cp.min'                => 'Código postal es de mínimo 5 caracteres.',
            'emp_cp.max'                => 'Código postal es de máximo 5 caracteres.',
            'emp_cp.numeric'            => 'Código postal debe ser numerico.',            
            'emp_tel.required'          => 'Teléfono es obligatorio y digitar soló numeros preferentemente.',
            'emp_tel.min'               => 'Teléfono es de mínimo 1 caracteres númericos preferentemente.',
            'emp_tel.max'               => 'Teléfono es de máximo 30 caracteres numéricos prefentemente.',
            //'emp_fecing.required'     => 'Fecha de ingreso es obligatoria dd/mm/aaaa.',
            'emp_dom.required'          => 'Domicilio es obligatorio.',
            'emp_dom.min'               => 'Domicilio es de mínimo 1 carácteres.',
            'emp_dom.max'               => 'Domicilio es de máximo 150 carácteres.',
            'emp_col.required'          => 'Colonia es obligatorio.',
            'emp_col.min'               => 'Colonia es de mínimo 1 carácteres.',
            'emp_col.max'               => 'Colonia es de máximo 80 carácteres.',
            'emp_email.required'        => 'Correo electrónico es obligatorio.',            
            'emp_email.min'             => 'Correo electrónico es de mínimo 8 carácteres.',
            'emp_email.max'             => 'Correo electrónico es de máximo 60 carácteres.'
            //'integ_fam.required'      => 'Integrantes de la familia es obligatorio.',            
            //'cuota_recup.required'    => 'Cuota de recuperación es obligatoria.',
            //'periodo_id1.required'    => 'Año de nacimiento es obligatorio.',
            //'mes_id1.required'        => 'Mes de nacimiento es obligatorio.',
            //'dia_id1.required'        => 'Día de nacimiento es obligatorio.',
            //'periodo_id2.required'    => 'Año de ingreso es obligatorio.',
            //'mes_id2.required'        => 'Mes de ingreso es obligatorio.',
            //'dia_id2.required'        => 'Día de ingreso es obligatorio.'
            //'servicios_brian.required'=> 'Servicios que le brinda la IAP es obligatorio.'
            //'iap_foto1.required'      => 'La imagen es obligatoria'
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
            //'iap_id'            => 'required',
            'emp_ap'              => 'required|min:1|max:80',
            //'emp_am'            => 'required|min:1|max:80',
            'emp_nombres'         => 'required|min:1|max:80',
            //'emp_curp'          => 'required|min:1|max:18',
            //'entidad_fed_id'    => 'required',
            //'municipio_id'      => 'required',            
            'emp_cp'              => 'required|min:5|max:5',
            'emp_tel'             => 'required|min:1|max:30',
            //'emp_fecing'        => 'required',
            'emp_email'           => 'required|email|min:8|max:60',
            'emp_dom'             => 'required|min:1|max:150',
            'emp_col'             => 'required|min:1|max:80'
            //'cuota_recup'       => 'required',
            //'periodo_id1'       => 'required',
            //'mes_id1'           => 'required',
            //'dia_id1'           => 'required',
            //'periodo_id2'       => 'required',
            //'mes_id2'           => 'required',            
            //'dia_id2'           => 'required'
            //'servicios_brindan' => 'required'
            //'iap_foto1'         => 'required|image',
            //'iap_foto2'         => 'required|image'
            //'accion'            => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'            => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc'        => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
