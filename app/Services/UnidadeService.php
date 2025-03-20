<?php

namespace App\Services;

use Exception;
use App\Repositories\UnidadeRepositoryInterface;

class UnidadeService
{
    protected UnidadeRepositoryInterface $unidadeRepository;

    public function __construct(UnidadeRepositoryInterface $unidadeRepository)
    {
        $this->unidadeRepository = $unidadeRepository;
    }
    /**
     * Retorna todas as relações Unidade cadastradas.
     *
     * @return mixed Lista de relações Unidade.
     */
    public function getAllUnidades()
    {
        return $this->unidadeRepository->all();
    }

    /**
     * Retorna uma relação Unidade pelo ID.
     *
     * @param int $id Identificador da relação Unidade.
     * @return mixed Dados da relação Unidade.
     * @throws Exception Se o ID não for encontrado.
     */
    public function getUnidadeById($id)
    {
        $unidade = $this->unidadeRepository->findById($id);
        if (!$unidade) {
            throw new Exception('Unidade não encontrado.');
        }
        return $unidade;
    }

    /**
     * Cria uma nova relação Unidade.
     *
     * @param array $data Dados da nova relação Unidade.
     * @return mixed Dados da relação criada.
     */
    public function createUnidade(array $data)
    {  
        return $this->unidadeRepository->create($data);
    }

    /**
     * Atualiza uma relação Unidade existente.
     *
     * @param int $id Identificador da relação Unidade a ser atualizada.
     * @param array $data Dados para atualização.
     * @return mixed Dados da relação atualizada.
     * @throws Exception Se o ID não for encontrado.
     */
    public function updateUnidade(array $data, $id)
    {
        $unidade = $this->unidadeRepository->findById($id);
        if (!$unidade) {
            throw new Exception('Unidade não encontrado.');
        }

        return $this->unidadeRepository->update($data, $id);
    }

    /**
     * Exclui uma relação Unidade pelo ID.
     *
     * @param int $id Identificador da relação Unidade a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */    public function deleteUnidade($id)
    {
        return $this->unidadeRepository->delete($id);
    }
}