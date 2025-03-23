<?php

namespace App\Http\Requests\Servidor\Efetivo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\ServidorEfetivo;

class ServidorEfetivoUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $pesId = $this->route('servidorEfetivo');
        
        return [
            'se_matricula' => [
                'sometimes',
                'string',
                'max:20',
                Rule::unique('servidores_efetivos', 'se_matricula')
                    ->ignore($pesId, 'pes_id')
            ],
            'pes_nome' => 'sometimes|string|max:200|min:3',
            'pes_cpf' => [
                'sometimes',
                'string',
                'max:14',
                Rule::unique('pessoas', 'pes_cpf')
                    ->ignore($pesId, 'pes_id')
            ],
            'pes_data_nascimento' => 'sometimes|date',
            'end_logradouro' => 'sometimes|string|max:200|min:3'
        ];
    }

    public function messages()
    {
        return [
            'se_matricula.unique' => 'Matrícula já cadastrada',
            'pes_cpf.unique' => 'CPF já cadastrado',
            'end_logradouro.min' => 'Logradouro deve ter pelo menos 3 caracteres'
        ];
    }
}
