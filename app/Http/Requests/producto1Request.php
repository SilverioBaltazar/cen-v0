<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class producto1Request extends FormRequest
{
    public function messages()
    {
        return [
            //'apor_entrega.max' => 'El nombre de la persona que entrega la aportación monetaria es de máximo 80 caracteres.'
            'prod_foto1.required' => 'Foto del producto es obligatorio formato jpeg, jpg o png. Puede pesar hasta 3000 kilobytes'
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
            //'prod_foto1'  => 'sometimes|mimetypes:application/pdf|max:2048',
            //'prod_foto1'  => 'sometimes|mimetypes:pdf|max:2048',
            //'prod_foto1'  => 'sometimes|mimes:application/pdf|max:2048',
            //'prod_foto1'  => 'sometimes|mimes:pdf|max:2048'      
            //'prod_foto1'    => 'required|image|mimes:jpeg,gif,png|max:3000' //Puede pesar hasta 3000 kilobytes
            //'prod_foto1'    => 'image|mimes:image/jpeg, image/jpg, image/png|max:3000'
            'prod_foto1'    => 'mimes:jpeg,jpg,png|max:3000'
        ];
    }
}
