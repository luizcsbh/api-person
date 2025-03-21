<?php

namespace App\Http\Requests\Pessoa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PessoaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $ruleType = $this->isMethod('PUT') ? 'sometimes' : 'required';

        return [
            'pes_nome'            => "$ruleType|string|max:200|min:3",
            'pes_cpf'             => [
                $ruleType,
                'string',
                'max:14',
                $this->isMethod('PUT')
                    ? Rule::unique('pessoas', 'pes_cpf')->ignore($this->pessoa->pes_id, 'pes_id')
                    : Rule::unique('pessoas', 'pes_cpf')
            ],
            'pes_data_nascimento' => "$ruleType|date",
            'pes_sexo'            => "$ruleType|string|max:9",
            'pes_mae'             => "$ruleType|string|max:200|min:3",
            'pes_pai'             => "$ruleType|string|max:200|min:3",
        ];
    }

    public function messages()
    {
        return [
            // Mensagens para 'required'
            'pes_nome.required'            => 'O nome é obrigatório.',
            'pes_cpf.required'             => 'O CPF é obrigatório.',
            'pes_data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'pes_sexo.required'           => 'O campo sexo é obrigatório.',
            'pes_mae.required'             => 'O nome da mãe é obrigatório.',
            'pes_pai.required'             => 'O nome do pai é obrigatório.',
            
            // Mensagens comuns
            'pes_nome.string'              => 'O nome deve ser um texto.',
            'pes_nome.max'                 => 'O nome pode ter no máximo 200 caracteres.',
            'pes_nome.min'                 => 'O nome deve ter no mínimo 3 caracteres.',

            'pes_cpf.string'               => 'O CPF deve ser um texto.',
            'pes_cpf.max'                  => 'O CPF deve ter no máximo 14 caracteres.',
            'pes_cpf.unique'               => 'Este CPF já está cadastrado.',
            
            'pes_data_nascimento.date'     => 'A data de nascimento deve ser uma data válida.',
            
            'pes_sexo.string'              => 'O sexo deve ser um texto.',
            'pes_sexo.max'                 => 'O sexo pode ter no máximo 9 caracteres.',
            
            'pes_mae.string'               => 'O nome da mãe deve ser um texto.',
            'pes_mae.max'                 => 'O nome da mãe pode ter no máximo 200 caracteres.',
            'pes_mae.min'                 => 'O nome da mãe deve ter no mínimo 3 caracteres.',
            
            'pes_pai.string'               => 'O nome do pai deve ser um texto.',
            'pes_pai.max'                  => 'O nome do pai pode ter no máximo 200 caracteres.',
            'pes_pai.min'                  => 'O nome do pai deve ter no mínimo 3 caracteres.',
        ];
    }
}