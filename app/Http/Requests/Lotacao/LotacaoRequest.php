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
        // Define o prefixo das regras (required/sometimes)
        $rulePrefix = $this->isMethod('PUT') ? 'sometimes' : 'required';

        return [
            'pes_id' => "$rulePrefix|exists:pessoas,pes_id",
            'uni_id' => "$rulePrefix|exists:unidades,uni_id",
            'lot_data_lotacao' => "$rulePrefix|date",
            'lot_data_remocao' => 'date',
            'lot_portaria' => "$rulePrefix|string|max:100|min:3",
        ];
    }

    public function messages()
    {
        return [
            // Mensagens para POST (required)
            'pes_id.required' => 'O campo pessoa é obrigatório.',
            'uni_id.required' => 'O campo unidade é obrigatório.',
            'lot_data_lotacao.required' => 'A data de lotação é obrigatória.',
            'lot_portaria.required' => 'O número da portaria é obrigatório.',
            
            // Mensagens comuns a ambos os métodos
            'pes_id.exists' => 'A pessoa selecionada não existe no sistema.',
            'uni_id.exists' => 'A unidade selecionada não existe no sistema.',
            
            'lot_data_lotacao.date' => 'A data de lotação deve ser uma data válida.',
            'lot_data_remocao.date' => 'A data de remoção deve ser uma data válida.',
            
            'lot_portaria.string' => 'A portaria deve ser um texto.',
            'lot_portaria.max' => 'A portaria não pode ter mais de 100 caracteres.',
            'lot_portaria.min' => 'A portaria deve ter pelo menos 3 caracteres.'
        ];
    }
}