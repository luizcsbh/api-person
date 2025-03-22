<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LotacaoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'lot_id' => $this->lot_id,
            'pes_id' => $this->pes_id,
            'unid_id' => $this->unid_id,
            'lot_data_lotacao' => $this->lot_data_lotacao,
            'lot_data_remocao' => $this->lot_data_remocao,
            'lot_portaria' => $this->lot_portaria,
            'unidades' => UnidadeResource::collection($this->whenLoaded('unidades')),
        ];
    }
}
