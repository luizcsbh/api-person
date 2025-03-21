<?php

namespace App\Http\Requests\Endereco;

use Illuminate\Foundation\Http\FormRequest;

class EnderecoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rulePrefix = $this->isMethod('PUT') ? 'sometimes' : 'required';

        return [
            'cid_id' => "$rulePrefix|exists:cidades,cid_id",
            'end_tipo_logradouro' => "$rulePrefix|string|max:50|min:3",
            'end_logradouro' => "$rulePrefix|string|max:200|min:3",
            'end_numero' => "$rulePrefix|integer|min:0",
            'end_complemento' => "$rulePrefix|nullable|string|max:100", // Removido min:3 para permitir valores vazios
            'end_bairro' => "$rulePrefix|string|max:100|min:3",
        ];
    }

    public function messages()
    {
        return [
            // Mensagens para POST (required)
            'cid_id.required' => 'O campo cidade é obrigatório',
            'end_tipo_logradouro.required' => 'O tipo de logradouro é obrigatório',
            'end_logradouro.required' => 'O logradouro é obrigatório',
            'end_numero.required' => 'O número é obrigatório',
            'end_complemento.required' => 'O complemento é obrigatório', // Corrigido texto da mensagem
            'end_bairro.required' => 'O bairro é obrigatório',

            // Mensagens comuns
            'cid_id.exists' => 'A cidade selecionada não existe',
            
            'end_tipo_logradouro.string' => 'O tipo de logradouro deve ser texto',
            'end_tipo_logradouro.max' => 'O tipo de logradouro não pode exceder 50 caracteres',
            'end_tipo_logradouro.min' => 'O tipo de logradouro deve ter pelo menos 3 caracteres',

            'end_logradouro.string' => 'O logradouro deve ser texto',
            'end_logradouro.max' => 'O logradouro não pode exceder 200 caracteres',
            'end_logradouro.min' => 'O logradouro deve ter pelo menos 3 caracteres',
            
            'end_numero.integer' => 'O número deve ser um valor inteiro',
            'end_numero.min' => 'O número não pode ser negativo',
            
            'end_complemento.string' => 'O complemento deve ser texto',
            'end_complemento.max' => 'O complemento não pode exceder 100 caracteres',
            
            'end_bairro.string' => 'O bairro deve ser texto',
            'end_bairro.max' => 'O bairro não pode exceder 100 caracteres',
            'end_bairro.min' => 'O bairro deve ter pelo menos 3 caracteres',
        ];
    }
}