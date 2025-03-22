<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServidorEfetivoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pes_id' => $this->pes_id,
            'nome' => $this->pessoa->pes_nome,
            'pes_cpf' => $this->pessoa->pes_cpf,
            'matricula' => $this->se_matricula,
            'nascimento' => $this->pessoa->pes_data_nascimento,
            'pes_sexo' => $this->pessoa->pes_sexo,
            'pes_mae' => $this->pessoa->pes_mae,
            'pes_pai' => $this->pessoa->pes_pai,
            
            'lotacoes' => $this->pessoa->lotacoes->map(function($lotacao) {
                return [
                    'unidade' => $lotacao->unidade->unid_nome,
                    'data_lotacao' => $lotacao->lot_data_lotacao
                ];
            })
        ];
    }
}