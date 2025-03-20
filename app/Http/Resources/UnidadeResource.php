<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnidadeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'unid_id' => $this->unid_id,
            'unid_nome' => $this->unid_nome,
            'unid_sigla' => $this->unid_sigla,
            //'enderecos' => EnderecoResource::collection($this->whenLoaded('enderecos')),
            'lotacoes' => LotacaoResource::collection($this->whenLoaded('lotacoes')),
        ];
    }
}
