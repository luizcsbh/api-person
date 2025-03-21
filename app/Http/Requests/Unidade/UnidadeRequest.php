<?php

namespace App\Http\Requests\Unidade;

use Illuminate\Foundation\Http\FormRequest;

class UnidadeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Define a regra inicial (required para POST, sometimes para PUT)
        $rulePrefix = $this->isMethod('PUT') ? 'sometimes' : 'required';

        return [
            'unid_nome'  => "$rulePrefix|string|max:200|min:3",
            'unid_sigla' => "$rulePrefix|string|max:20|min:3",
        ];
    }

    public function messages()
    {
        return [
            // Mensagens mantidas para compatibilidade com ambos os cenários
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