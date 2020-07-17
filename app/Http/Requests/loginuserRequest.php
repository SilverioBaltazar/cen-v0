<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class loginuserRequest extends FormRequest
{
    public function messages()
    {
        return [
            'user_name.min'         => 'El usuario debe ser de mínimo 5 caracteres.',
            'user_name.max'         => 'El usuario debe ser de máximo 80 caracteres.',
            //'usuario.e_mail'      => 'El usuario debe estar en un formato de email (ejemplo@ejemplo.com).',
            //'user_name.required'    => 'El usuario es necesario para entrar al sistema..',
            'user_password.min'     => 'La contraseña debe ser de mínimo 6 caracteres.',
            'user_password.max'     => 'La contraseña debe ser de máximo 15 caracteres.'
            //'user_password.required'=> 'La contraseña es necesaria para registrarse..'
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
            //'user_name'     =>  'min:5|max:80|required',
            //'user_password' =>  'min:6|max:15|required'
        ];
    }
}
