<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class bitarendi1Request extends FormRequest
{
    public function messages()
    {
        return [
            'bitaco_foto1.required' => 'El documento de bitacora de rendimiento de combustible 1 en formato PDF es obligatorio'
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
            //'iap_desc.'    => 'required|min:1|max:100',
            'bitaco_foto1'    => 'mimes:pdf'
            //'iap_foto2'    => 'required|image'
            //'accion'        => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'        => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
