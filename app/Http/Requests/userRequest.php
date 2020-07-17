<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userRequest extends FormRequest
{
    public function messages()
    {
        return [
            'user_names.min'         => 'Nombre(s) debe ser de mínimo 4 carácteres.',
            'user_names.max'         => 'Nombre(s) debe ser de mínimo 80 carácteres.',
            'user_names.required'    => 'Nombre(s) es obligatorio.',
            'user_names.regex'       => 'Nombre(s) contiene campos inválidos.',
            'user_ap.min'            => 'Apellido Paterno debe ser de mínimo 4 carácteres.',
            'user_ap.max'            => 'Apellido Paterno debe ser de mínimo 80 carácteres.',
            'user_ap.required'       => 'Apellido Paterno es obligatorio.',
            'user_ap.regex'          => 'Apellido Paterno contiene campos inválidos.',
            //'materno.min'          => 'El Apellido Materno debe ser de mínimo 4 caracteres.',
            //'materno.max'          => 'El Apellido Materno debe ser de mínimo 80 caracteres.',
            //'materno.required'     => 'El Apellido Materno es obligatorio.',
            //'materno.regex'        => 'El Apellido Materno contiene campos inválidos.',
            //'correo.email'         => 'Formato incorrecto (ejemplo@ejemplo.ejemplo).',
            //'correo.required'      => 'El e-mail es obligatorio.',
            //'usuario.email'        => 'Formato incorrecto (ejemplo@ejemplo.ejemplo).',
            'user_name.required'     => 'El e-mail es obligatorio para accesar al sistema (ejemplo@hotmail.com).',
            'user_name.min'          => 'El usuario debe ser de mínimo 5 caracteres.',
            'user_name.max'          => 'El usuario debe ser de máximo 40 caracteres.',
            //'usuario.required'     => 'El usuario es necesario para entrar al sistema.',
            'user_password.min'      => 'La contraseña debe ser de mínimo 6 carácteres.',
            'user_password.max'      => 'La contraseña debe ser de máximo 15 carácteres.',
            'user_password.required' => 'La contraseña es necesaria para registrarse.',
            //'cve_arbol.required'   => 'Seleccionar la IAP a la que pertenece.',             
            'depto_id.required'      => 'Seleccionar departamento.'                         
            //'unidad.required'      => 'La Unidad Administrativa es obligatoria.',
            //'perfil.required'      => 'La Unidad Administrativa es obligatoria.'
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
            'user_names'     => 'min:4|max:80|required|regex:/(^([a-zA-z\s]+)?$)/i',
            'user_ap'        => 'min:4|max:80|required',   //|regex:/(^([a-zA-z\s]+)?$)/i',
            //'materno'      => 'min:4|max:80|required|regex:/(^([a-zA-z\s]+)?$)/i',
            //'correo'       => 'email|required',
            'user_name'      => 'email|min:5|max:40|required',
            'user_password'  => 'min:6|max:15|required',
            //'cve_arbol'    => 'required',
            'depto_id'       => 'required'
            //'perfil'       => 'required'
        ];
    }
}
