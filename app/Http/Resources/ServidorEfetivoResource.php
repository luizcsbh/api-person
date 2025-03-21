<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServidorEfetivoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pes_id' => $this->pes_id,
            'matricula' => $this->se_matricula,
            'pessoa' => [
                'nome' => $this->pessoa->pes_nome,
                'nascimento' => $this->pessoa->pes_data_nascimento
            ],
            'lotacoes' => $this->pessoa->lotacoes->map(function($lotacao) {
                return [
                    'unidade' => $lotacao->unidade->unid_nome,
                    'data_lotacao' => $lotacao->lot_data_lotacao
                ];
            })
        ];
    }
}