<?php

namespace App\Services;

use Exception;
use App\Repositories\EnderecoRepositoryInterface;

class EnderecoService
{
    protected EnderecoRepositoryInterface $enderecoRepository;

    public function __construct(EnderecoRepositoryInterface $enderecoRepository)
    {
        $this->enderecoRepository = $enderecoRepository;
    }
    /**
     * Retorna todas as relações Endereços cadastradas.
     *
     * @return mixed Lista de relações Endereços.
     */
    public function getAllEnderecos()
    {
        return $this->enderecoRepository->all();
    }

    /**
     * Retorna todas as relações Endereço cadastradas com paginacao.
     *
     * @return mixed Lista de relações Endereço paginada.
     */
    public function paginate(int $perPage = 10)
    {
        return $this->enderecoRepository->paginate($perPage);
    }

    /**
     * Retorna uma relação Endereço pelo ID.
     *
     * @param int $id Identificador da relação Endereço.
     * @return mixed Dados da relação Endereço.
     * @throws Exception Se o ID não for encontrado.
     */
    public function getEnderecoById($id)
    {
        $endereco = $this->enderecoRepository->findById($id);
        if (!$endereco) {
            throw new Exception('Endereço não encontrado.');
        }
        return $endereco;
    }

    /**
     * Cria uma nova relação Endereço.
     *
     * @param array $data Dados da nova relação Endereço.
     * @return mixed Dados da relação criada.
     */
    public function createEndereco(array $data)
    {  
        return $this->enderecoRepository->create($data);
    }

    /**
     * Atualiza uma relação Endereço existente.
     *
     * @param int $id Identificador da relação Endereço a ser atualizada.
     * @param array $data Dados para atualização.
     * @return mixed Dados da relação atualizada.
     * @throws Exception Se o ID não for encontrado.
     */
    public function updateEndereco(array $data, $id)
    {
        $endereco = $this->enderecoRepository->findById($id);
        if (!$endereco) {
            throw new Exception('Endereço não encontrado.');
        }

        return $this->enderecoRepository->update($data, $id);
    }

    /**
     * Exclui uma relação Endereço pelo ID.
     *
     * @param int $id Identificador da relação Endereço a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */    public function deleteEndereco($id)
    {
        return $this->enderecoRepository->delete($id);
    }
}