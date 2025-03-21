<?php

namespace App\Rules\Servidor;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ServidorEfetivo;
use App\Models\ServidorTemporario;

class UniqueServidor implements Rule
{
    /**
     * Determina se a regra de validação passa
     */
    public function passes($attribute, $value)
    {
        return !ServidorEfetivo::where('pes_id', $value)->exists() &&
               !ServidorTemporario::where('pes_id', $value)->exists();
    }

    /**
     * Mensagem de erro da validação
     */
    public function message()
    {
        return 'Este ID já está vinculado a outro servidor (efetivo ou temporário).';
    }
}