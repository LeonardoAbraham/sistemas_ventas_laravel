<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    //sirve para roles y permisos
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    //reglas de validacion - https://laravel.com/docs/8.x/validation#main-content
    public function rules()
    {
        return [
            'codigo' => "required|unique:productos,codigo",
            'nombre' => "required|min:3|max:100",
            'precio' => "required|numeric|min:1|max:9999"
        ];
    }
}
