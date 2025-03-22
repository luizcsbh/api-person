<?php

namespace App\Services;

use Exception;
use App\Repositories\LotacaoRepositoryInterface;

class LotacaoService
{
    protected $lotacaoRepository;

    public function __construct(LotacaoRepositoryInterface $lotacaoRepository)
    {
        $this->lotacaoRepository = $lotacaoRepository;
    }
    /**
     * Retorna todas as relações Lotacao cadastradas.
     *
     * @return mixed Lista de relações Lotacao.
     */
    public function getAllLotacoes()
    {
        return $this->lotacaoRepository->all();
    }

        /**
     * Retorna todas as relações Pessoa cadastradas com paginacao.
     *
     * @return mixed Lista de relações Pessoa paginada.
     */
    public function paginate(int $perPage = 10)
    {
        return $this->lotacaoRepository->paginate($perPage);
    }

    /**
     * Retorna uma relação Lotação pelo ID.
     *
     * @param int $id Identificador da relação Lotação.
     * @return mixed Dados da relação Lotação.
     * @throws Exception Se o ID não for encontrado.
     */
    public function getLotacaoById($id)
    {
        $lotacao = $this->lotacaoRepository->findById($id);
        if (!$lotacao) {
            throw new Exception('Lotação não encontrado.');
        }
        return $lotacao;
    }

    /**
     * Cria uma nova relação Lotação.
     *
     * @param array $data Dados da nova relação Lotação.
     * @return mixed Dados da relação criada.
     */
    public function createLotacao(array $data)
    {  
        return $this->lotacaoRepository->create($data);
    }

    /**
     * Atualiza uma relação Lotação existente.
     *
     * @param int $id Identificador da relação Lotação a ser atualizada.
     * @param array $data Dados para atualização.
     * @return mixed Dados da relação atualizada.
     * @throws Exception Se o ID não for encontrado.
     */
    public function updateLotacao(array $data, $id)
    {
        $lotacao = $this->lotacaoRepository->findById($id);
        if (!$lotacao) {
            throw new Exception('Lotação não encontrado.');
        }

        return $this->lotacaoRepository->update($data, $id);
    }

    /**
     * Exclui uma relação Lotação pelo ID.
     *
     * @param int $id Identificador da relação Lotação a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */    public function deleteLotacao($id)
    {
        return $this->lotacaoRepository->delete($id);
    }
}