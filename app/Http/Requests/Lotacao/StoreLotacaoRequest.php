<?php

namespace App\Http\Requests\Lotacao;

use Illuminate\Foundation\Http\FormRequest;

class StoreLotacaoRequest extends FormRequest
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
            'pes_id'=> 'required|integer',
            'uni_id'=> 'required|integer',
            'lot_data_lotacao'=> 'required|date',
            'lot_data_remocao'=> 'date',
            'lot_portaria'=> 'required|string|max:100|min:3',
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
            'pes_id.required' => 'O id da pessoa é obrigatório.',
            'pes_id.integer' => 'O id da pessoa deve ser um número inteiro.',
            
            'uni_id.required' => 'O id da unidade é obrigatório.',
            'uni_id.integer' => 'O id da unidade deve ser um número inteiro.',
            
            'lot_data_lotacao.required' => 'A data de lotação é obrigatória.',
            'lot_data_lotacao.date' => 'A data de lotação deve ser uma data válida.',
            
            'lot_data_remocao.date' => 'A data de remoção deve ser uma data válida.',
            
            'lot_portaria.required' => 'A portaria é obrigatória.',
            'lot_portaria.string' => 'A portaria deve ser um texto.',
            'lot_portaria.max' => 'A portaria pode ter no máximo 100 caracteres.',
            'lot_portaria.min' => 'A portaria deve ter no mínimo 3 caracteres.',
        ];
    }

}