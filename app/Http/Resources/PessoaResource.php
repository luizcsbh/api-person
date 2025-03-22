<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PessoaResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->pes_id,
            'pes_nome' => $this->pes_nome,
            'pes_cpf' => $this->pes_cpf,
            'pes_data_nascimento' => $this->pes_data_nascimento,
            'pes_sexo' => $this->pes_sexo,  
            'pes_mae' => $this->pes_mae,
            'pes_pai' => $this->pes_pai,
            'lotacoes' => LotacaoResource::collection($this->whenLoaded('lotacoes')),
            'enderecos' => EnderecoResource::collection($this->whenLoaded('enderecos'))
        ];
    }
}
