<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class clienteRequest extends FormRequest
{
    public function messages()
    {
        return [ 
            //'iap_id.required'         => 'La IAP es obligatorio.',
            'cliente_ap.required'       => 'Apellido paterno es obligatorio.',
            'cliente_ap.min'            => 'Apellido paterno es de mínimo 1 carácteres.',
            'cliente_ap.max'            => 'Apellido paterno es de máximo 80 carácteres.',
            //'cliente_am.required'     => 'Apellido materno es obligatorio.',
            //'cliente_am.min'          => 'Apellido materno es de mínimo 1 carácteres.',
            //'cliente_pm.max'          => 'Apellido materno es de máximo 80 carácteres.',
            'cliente_nombres.required'  => 'Nombre(s) es obligatorio.',
            'cliente_nombres.min'       => 'Nombre(s) es de mínimo 1 carácteres.',
            'cliente_nombres.max'       => 'Nombre(s) es de máximo 80 carácteres.',     
            //'cliente_curp.required'     => 'CURP es obligatorio.',
            //'cliente_curp.min'          => 'CURP es de mínimo 18 carácteres.',
            //'cliente_curp.max'          => 'CURP es de máximo 18 carácteres.',                       
            //'entidad_fed_id.required' => 'Entidad federativa es obligatoria.',
            //'municipio_id.required'   => 'Municipio es obligatorio.',            
            'cliente_cp.required'       => 'Código postal es obligatorio.',
            'cliente_cp.min'            => 'Código postal es de mínimo 5 caracteres.',
            'cliente_cp.max'            => 'Código postal es de máximo 5 caracteres.',
            'cliente_cp.numeric'        => 'Código postal debe ser numerico.',            
            'cliente_tel.required'      => 'Teléfono es obligatorio y digitar soló numeros preferentemente.',
            'cliente_tel.min'           => 'Teléfono es de mínimo 1 caracteres númericos preferentemente.',
            'cliente_tel.max'           => 'Teléfono es de máximo 30 caracteres numéricos prefentemente.',
            //'cliente_fecing.required'   => 'Fecha de ingreso es obligatoria dd/mm/aaaa.',
            'cliente_dom.required'      => 'Domicilio es obligatorio.',
            'cliente_dom.min'           => 'Domicilio es de mínimo 1 carácteres.',
            'cliente_dom.max'           => 'Domicilio es de máximo 150 carácteres.',
            'cliente_col.required'      => 'Colonia es obligatorio.',
            'cliente_col.min'           => 'Colonia es de mínimo 1 carácteres.',
            'cliente_col.max'           => 'Colonia es de máximo 80 carácteres.',
            'cliente_email.required'    => 'Correo electrónico es obligatorio.',            
            'cliente_email.min'         => 'Correo electrónico es de mínimo 8 carácteres.',
            'cliente_email.max'         => 'Correo electrónico es de máximo 60 carácteres.'
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
            'cliente_ap'          => 'required|min:1|max:80',
            //'cliente_am'        => 'required|min:1|max:80',
            'cliente_nombres'     => 'required|min:1|max:80',
            //'cliente_curp'        => 'required|min:1|max:18',
            //'entidad_fed_id'    => 'required',
            //'municipio_id'      => 'required',            
            'cliente_cp'          => 'required|min:5|max:5',
            'cliente_tel'         => 'required|min:1|max:30',
            //'cliente_fecing'      => 'required',
            'cliente_email'       => 'required|email|min:8|max:60',
            'cliente_dom'         => 'required|min:1|max:150',
            'cliente_col'         => 'required|min:1|max:80'
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
