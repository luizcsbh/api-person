<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnderecoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'end_id' => $this->end_id,
            'cid_id' => $this->cid_id,
            'end_tipo_logradouro' => $this->end_tipo_logradouro,
            'end_logradouro' => $this->end_logradouro,
            'end_numero' => $this->end_numero,
            'end_complemento' => $this->end_complemento,
            'end_bairro' => $this->end_bairro,
        ];
    }
}
