<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServidorEfetivoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'pessoa' => [
                'id' =>  $this->pessoa->pes_id,
                'nome' => $this->pessoa->pes_nome,
                'cpf' => $this->pessoa->pes_cpf,
                'matricula' => $this->se_matricula,
                'nascimento' => $this->pessoa->pes_data_nascimento,
                'enderecos' => $this->pessoa->enderecos->map(function($endereco) {
                    return [
                        'logradouro' => $endereco->end_logradouro,
                        'numero' => $endereco->end_numero,
                        'complemento' => $endereco->end_complemento,
                        'bairro' => $endereco->end_bairro,
                        'cidade_id' => $endereco->cid_id
                    ];
                })
            ],
            'criado_em' => $this->created_at->format('d/m/Y H:i')
        ];
    }
}