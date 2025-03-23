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
        // Obter ID do servidor da rota
        $servidorId = $this->route('servidorEfetivo');
        
        // Buscar o servidor com relacionamento de pessoa
        $servidor = ServidorEfetivo::with('pessoa')->find($servidorId);

       

        if (!$servidor) {
            abort(404, 'Servidor efetivo não encontrado');
        }

        return [
            // Regras para Servidor Efetivo (usar se_id)
            'se_matricula' => [
                'sometimes',
                'string',
                Rule::unique('servidores_efetivos', 'se_matricula')
                    ->ignore($servidor->se_id, 'se_id')
            ],
            
            // Regras para Pessoa (usar pes_id da pessoa relacionada)
            'pes_nome' => 'sometimes|string|max:200|min:3',
            'pes_cpf' => [
                'sometimes',
                'string',
                'max:14',
                Rule::unique('pessoas', 'pes_cpf')
                    ->ignore($servidor->pessoa->pes_id, 'pes_id')
            ],
            'pes_data_nascimento' => 'sometimes|date',
            'pes_sexo' => 'sometimes|string|max:9',
            'pes_mae' => 'sometimes|string|max:200|min:3',
            'pes_pai' => 'sometimes|string|max:200|min:3',
            
            // Regras para Endereço 
            'cid_id' => 'sometimes|exists:cidades,cid_id',
            'end_tipo_logradouro' => 'sometimes|string|max:50|min:3',
            'end_logradouro' => 'sometimes|string|max:200|min:3',
            'end_numero' => 'sometimes|integer|min:0',
            'end_complemento' => 'nullable|string|max:100',
            'end_bairro' => 'sometimes|string|max:100|min:3'
        ];
    }
}