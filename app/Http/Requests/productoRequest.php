<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class productoRequest extends FormRequest
{
    public function messages()
    {
        return [ 
            'codigo_barras.required'    => 'Código de barras es obligatorio.',
            'codigo_barras.min'         => 'Código de barras es de mínimo 5 carácteres.',
            'codigo_barras.max'         => 'Código de barras es de máximo 30 carácteres.',
            'descripcion.required'      => 'Descripción del producto es obligatorio.',
            'descripcion.min'           => 'Descripción del producto es de mínimo 1 carácteres.',
            'descripcion.max'           => 'Descripción del producto es de máximo 150 carácteres.',     
            //'cliente_curp.required'   => 'CURP es obligatorio.',
            //'cliente_curp.min'        => 'CURP es de mínimo 18 carácteres.',
            //'cliente_curp.max'        => 'CURP es de máximo 18 carácteres.',                       
            //'entidad_fed_id.required' => 'Entidad federativa es obligatoria.',
            //'municipio_id.required'   => 'Municipio es obligatorio.',            
            'precio_compra.required'    => 'Precio de compra es obligatorio.',
            'precio_compra.min'         => 'Precio de compra es de mínimo 1 caracter.',
            'precio_compra.max'         => 'Precio de compra es de máximo 15 caracteres.',
            'precio_compra.numeric'     => 'Precio de compra debe ser numerico.',            
            'precio_ventaa.required'    => 'Precio de venta es obligatorio.',
            'precio_venta.min'          => 'Precio de venta es de mínimo 1 caracter.',
            'precio_venta.max'          => 'Precio de venta es de máximo 15 caracteres.',
            'precio_venta.numeric'      => 'Precio de venta debe ser numerico.'
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
            'codigo_barras'       => 'required|min:5|max:30',
            'descripcion'         => 'required|min:1|max:150',
            //'municipio_id'      => 'required',            
            'precio_compra'       => 'required|min:1|max:10',
            'precio_venta'        => 'required|min:1|max:10'
            //'iap_foto2'         => 'required|image'
            //'accion'            => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'            => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc'        => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
