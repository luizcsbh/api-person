<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Classe de recurso para Instituição
 */
class PessoaResource extends JsonResource
{
    /**
     * Transforma o recurso em um array.
     *
     * @param  \Illuminate\Http\Request  $request Requisição HTTP
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable Retorna os dados da instituição
     */
    public function toArray($request)
    {
        return [
            'id' => $this->pes_id,
            'pes_nome' => $this->pes_nome,
            'pes_data_nascimento' => $this->pes_data_nascimento,
            'pes_sexo' => $this->pes_sexo,  
            'pes_mae' => $this->pes_mae,
            'pes_pai' => $this->pes_pai,
        ];
    }
}
