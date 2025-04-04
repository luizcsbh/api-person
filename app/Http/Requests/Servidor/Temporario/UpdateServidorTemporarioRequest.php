<?php

namespace App\Http\Requests\Servidor\Temporario;

use App\Rules\Servidor\UniqueServidor;
use Illuminate\Foundation\Http\FormRequest;

class UpdateServidorTemporarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pes_id' => ['required', 'exists:pessoas,pes_id', new UniqueServidor],
            'st_data_admissao' => 'required|date',
            'st_data_demissao' => 'nullable|date|after_or_equal:st_data_admissao'
        ];
    }

    public function messages()
    {
        return [
            // Mensagens para pes_id
            'pes_id.required' => 'O campo ID da pessoa é obrigatório.',
            'pes_id.exists' => 'O ID da pessoa informado não existe no sistema.',
            'pes_id.unique_servidor' => 'Este ID já está vinculado a outro servidor.',
            
            // Mensagens para st_data_admissao
            'st_data_admissao.required' => 'A data de admissão é obrigatória.',
            'st_data_admissao.date' => 'Formato de data inválido para admissão.',
            
            // Mensagens para st_data_demissao
            'st_data_demissao.date' => 'Formato de data inválido para demissão.',
            'st_data_demissao.after_or_equal' => 'A data de demissão deve ser igual ou posterior à data de admissão.'
        ];
    }
}