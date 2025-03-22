<?php

namespace App\Http\Requests\Lotacao;

use Illuminate\Foundation\Http\FormRequest;

class LotacaoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rulePrefix = $this->isMethod('PUT') ? 'sometimes' : 'required';

        return [
            'pes_id' => "$rulePrefix|exists:pessoas,pes_id",
            'unid_id' => "$rulePrefix|exists:unidades,unid_id",  // Nome correto do campo
            'lot_data_lotacao' => "$rulePrefix|date",
            'lot_data_remocao' => 'nullable|date', // Correção da ordem (nullable primeiro)
            'lot_portaria' => "$rulePrefix|string|max:100|min:3",
        ];
    }

    public function messages()
    {
        return [
            // Mensagens para POST (required)
            'pes_id.required' => 'O campo pessoa é obrigatório.',
            'unid_id.required' => 'O campo unidade é obrigatório.', // Corrigido para unid_id
            'lot_data_lotacao.required' => 'A data de lotação é obrigatória.',
            'lot_portaria.required' => 'O número da portaria é obrigatório.',
            
            // Mensagens comuns a ambos os métodos
            'pes_id.exists' => 'A pessoa selecionada não existe no sistema.',
            'unid_id.exists' => 'A unidade selecionada não existe no sistema.', // Corrigido para unid_id
            
            'lot_data_lotacao.date' => 'A data de lotação deve ser uma data válida.',
            'lot_data_remocao.date' => 'A data de remoção deve ser uma data válida.',
            
            'lot_portaria.string' => 'A portaria deve ser um texto.',
            'lot_portaria.max' => 'A portaria não pode ter mais de 100 caracteres.',
            'lot_portaria.min' => 'A portaria deve ter pelo menos 3 caracteres.'
        ];
    }
}