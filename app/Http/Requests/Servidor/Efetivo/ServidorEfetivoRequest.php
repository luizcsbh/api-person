<?php

namespace App\Http\Requests\Servidor\Efetivo;

use App\Rules\Servidor\UniqueServidor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServidorEfetivoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rulePrefix = $this->isMethod('PUT') ? 'sometimes' : 'required';

        return [
            'pes_id' => [
                $rulePrefix,
                'exists:pessoas,pes_id',
                new UniqueServidor
            ],
            'se_matricula' => [
                $rulePrefix,
                'string',
                'max:20',
                'min:3',
                $this->isMethod('PUT')
                    ? Rule::unique('servidores_efetivos')->ignore($this->servidorEfetivo->pes_id, 'pes_id')
                    : Rule::unique('servidores_efetivos', 'se_matricula')
            ]
        ];
    }

    public function messages()
    {
        return [
            // PES_ID
            'pes_id.required' => 'O campo pessoa é obrigatório',
            'pes_id.exists' => 'A pessoa selecionada não existe',

            // SE_MATRICULA
            'se_matricula.required' => 'O campo matrícula é obrigatório',
            'se_matricula.string' => 'A matrícula deve ser texto',
            'se_matricula.max' => 'A matrícula não pode exceder 20 caracteres',
            'se_matricula.min' => 'A matrícula deve ter pelo menos 3 caracteres',
            'se_matricula.unique' => 'Esta matrícula já está em uso.',
        ];
    }
}