<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ServidorEfetivoResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'matricula' => $this->matricula,
            'cpf' => $this->cpf,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'endereco' => $this->endereco,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'cep' => $this->cep,
            'cargo' => $this->cargo,
            'lotacao' => $this->lotacao,
            'data_admissao' => $this->data_admissao,
            'data_nascimento' => $this->data_nascimento,
            'data_criacao' => $this->data_criacao,
            'data_atualizacao' => $this->data_atualizacao
        ];
    }
}