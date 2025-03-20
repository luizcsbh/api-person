<?php

namespace App\Http\Requests\Unidade;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUnidadeRequest extends FormRequest
{
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'unid_nome' => 'sometimes|required|string|max:200|min:3',
            'unid_sigla' => 'sometimes|required|string|max:20|min:3',
        ];
    }

    /**
     * Get the validation messages for the defined rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'unid_nome.required' => 'O nome é obrigatório.',
            'unid_nome.string' => 'O nome deve ser um texto.',
            'unid_nome.max' => 'O nome pode ter no máximo 200 caracteres.',
            'unid_nome.min' => 'O nome deve ter no mínimo 3 caracteres.',
    
            'unid_sigla.required' => 'A sigla é obrigatória.',
            'unid_sigla.string' => 'A sigla deve ser um texto.',
            'unid_sigla.max' => 'A sigla pode ter no máximo 20 caracteres.',
            'unid_sigla.min' => 'A sigla deve ter no mínimo 3 caracteres.',
        ];
    }
}