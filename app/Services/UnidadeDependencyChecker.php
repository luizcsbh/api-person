<?php
namespace App\Services;

use App\Models\Unidade;
use Exception;

class UnidadeDependencyChecker
{
    public function checkDependencies(Unidade $unidade)
    {
        if ($unidade->enderecos()->exists()) {
            throw new Exception('Não é possível excluir a unidade. Existem endereços associados a ela.');
        }

        if ($unidade->lotacoes()->exists()) {
            throw new Exception('Não é possível excluir a unidade. Existem lotações associadas a ela.');
        }
    }
}
