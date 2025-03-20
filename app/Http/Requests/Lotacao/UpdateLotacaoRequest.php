<?php

namespace App\Http\Requests\Lotacao;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLotacaoRequest extends FormRequest
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
            'pes_id'=> 'sometimes|required|exists:pessoas,pes_id',
            'uni_id'=> 'sometimes|required|exists:unidades,uni_id',
            'lot_data_lotacao'=> 'sometimes|required|date',
            'lot_data_remocao'=> 'sometimes|date',
            'lot_portaria'=> 'sometimes|required|string|max:100|min:3',
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
            'pes_id.required' => 'O campo pessoa é obrigatório.',
            'pes_id.exists' => 'A pessoa selecionada não existe no sistema.',
            
            'uni_id.required' => 'O campo unidade é obrigatório.',
            'uni_id.exists' => 'A unidade selecionada não existe no sistema.',
            
            'lot_data_lotacao.required' => 'A data de lotação é obrigatória.',
            'lot_data_lotacao.date' => 'A data de lotação deve ser uma data válida.',
            
            'lot_data_remocao.date' => 'A data de remoção deve ser uma data válida.',
            
            'lot_portaria.required' => 'O número da portaria é obrigatório.',
            'lot_portaria.string' => 'A portaria deve ser um texto.',
            'lot_portaria.max' => 'A portaria não pode ter mais de 100 caracteres.',
            'lot_portaria.min' => 'A portaria deve ter pelo menos 3 caracteres.'
        ];
    }
}