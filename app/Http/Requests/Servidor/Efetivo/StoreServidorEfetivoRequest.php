<?php

namespace App\Http\Requests\Servidor\Efetivo;

use App\Rules\Servidor\UniqueServidor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ServidorTemporario;

class StoreServidorEfetivoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
{
    return [
        // Regras para Pessoa
        'pes_nome' => 'required|string|max:200|min:3',
        'pes_cpf' => [
            'required',
            'string',
            'max:14',
            Rule::unique('pessoas', 'pes_cpf')
        ],
        'se_matricula' => [
            'required',
            'string',
            Rule::unique('servidores_efetivos', 'se_matricula')
        ],
        'pes_data_nascimento' => 'required|date',
        'pes_sexo' => 'required|string|max:9',
        'pes_mae' => 'required|string|max:200|min:3',
        'pes_pai' => 'required|string|max:200|min:3',

        // Regras para Endereço
        'cid_id' => 'required|exists:cidades,cid_id',
        'end_tipo_logradouro' => 'required|string|max:50|min:3',
        'end_logradouro' => 'required|string|max:200|min:3',
        'end_numero' => 'required|integer|min:0',
        'end_complemento' => 'nullable|string|max:100',
        'end_bairro' => 'required|string|max:100|min:3',
    ];
}
    

    public function messages()
    {
        return [
            // Mensagens para Pessoa
            'pes_nome.required' => 'O nome é obrigatório.',
            'pes_nome.string' => 'O nome deve ser um texto.',
            'pes_nome.max' => 'O nome pode ter no máximo 200 caracteres.',
            'pes_nome.min' => 'O nome deve ter no mínimo 3 caracteres.',

            'pes_cpf.required' => 'O CPF é obrigatório.',
            'pes_cpf.string' => 'O CPF deve ser um texto.',
            'pes_cpf.max' => 'O CPF deve ter no máximo 14 caracteres.',
            'pes_cpf.unique' => 'Este CPF já está cadastrado.',

            'pes_data_nascimento.required' => 'A data de nascimento é obrigatória.',
            'pes_data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',

            'pes_sexo.required' => 'O campo sexo é obrigatório.',
            'pes_sexo.string' => 'O sexo deve ser um texto.',
            'pes_sexo.max' => 'O sexo pode ter no máximo 9 caracteres.',

            'pes_mae.required' => 'O nome da mãe é obrigatório.',
            'pes_mae.string' => 'O nome da mãe deve ser um texto.',
            'pes_mae.max' => 'O nome da mãe pode ter no máximo 200 caracteres.',
            'pes_mae.min' => 'O nome da mãe deve ter no mínimo 3 caracteres.',

            'pes_pai.required' => 'O nome do pai é obrigatório.',
            'pes_pai.string' => 'O nome do pai deve ser um texto.',
            'pes_pai.max' => 'O nome do pai pode ter no máximo 200 caracteres.',
            'pes_pai.min' => 'O nome do pai deve ter no mínimo 3 caracteres.',

            // Mensagens para Endereço
            'cid_id.required' => 'O campo cidade é obrigatório',
            'cid_id.exists' => 'A cidade selecionada não existe',

            'end_tipo_logradouro.required' => 'O tipo de logradouro é obrigatório',
            'end_tipo_logradouro.string' => 'O tipo de logradouro deve ser texto',
            'end_tipo_logradouro.max' => 'O tipo de logradouro não pode exceder 50 caracteres',
            'end_tipo_logradouro.min' => 'O tipo de logradouro deve ter pelo menos 3 caracteres',

            'end_logradouro.required' => 'O logradouro é obrigatório',
            'end_logradouro.string' => 'O logradouro deve ser texto',
            'end_logradouro.max' => 'O logradouro não pode exceder 200 caracteres',
            'end_logradouro.min' => 'O logradouro deve ter pelo menos 3 caracteres',

            'end_numero.required' => 'O número é obrigatório',
            'end_numero.integer' => 'O número deve ser um valor inteiro',
            'end_numero.min' => 'O número não pode ser negativo',

            'end_complemento.string' => 'O complemento deve ser texto',
            'end_complemento.max' => 'O complemento não pode exceder 100 caracteres',

            'end_bairro.required' => 'O bairro é obrigatório',
            'end_bairro.string' => 'O bairro deve ser texto',
            'end_bairro.max' => 'O bairro não pode exceder 100 caracteres',
            'end_bairro.min' => 'O bairro deve ter pelo menos 3 caracteres',
        ];
    }
}