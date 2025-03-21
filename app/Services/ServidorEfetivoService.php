<?php

namespace App\Services;

use Exception;
use App\Repositories\ServidorEfetivoRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ServidorEfetivoService
{
    protected ServidorEfetivoRepositoryInterface $servidorEfetivoRepository;

    public function __construct(ServidorEfetivoRepositoryInterface $servidorEfetivoRepository)
    {
        $this->servidorEfetivoRepository = $servidorEfetivoRepository;
    }
    /**
     * Retorna todas as relações Servidor Efetivo cadastradas.
     *
     * @return mixed Lista de relações Servidor Efetivo.
     */
    public function getAllServidoresEfetivos()
    {
        return $this->servidorEfetivoRepository->all();
    }

        /**
     * Retorna todas as relações Pessoa cadastradas com paginacao.
     *
     * @return mixed Lista de relações Pessoa paginada.
     */
    public function paginate(int $perPage = 10)
    {
        return $this->servidorEfetivoRepository->paginate($perPage);
    }

    /**
     * Retorna uma relação Servidor Efetivo pelo ID.
     *
     * @param int $id Identificador da relação Servidor Efetivo.
     * @return mixed Dados da relação Servidor Efetivo.
     * @throws Exception Se o ID não for encontrado.
     */
    public function getServidoresEfetivosById($id)
    {
        $servidorEfetivo = $this->servidorEfetivoRepository->findById($id);
        if (!$servidorEfetivo) {
            throw new Exception('Servidor efetivo não encontrado.');
        }
        return $servidorEfetivo;
    }

    /**
     * Cria uma nova relação Servidor Efetivo.
     *
     * @param array $data Dados da nova relação Servidor Efetivo.
     * @return mixed Dados da relação criada.
     */
    public function createServidorEfetivo(array $data)
    {  
        return $this->servidorEfetivoRepository->create($data);
    }

    /**
     * Atualiza uma relação Servidor Efetivo existente.
     *
     * @param int $id Identificador da relação Servidor Efetivo a ser atualizada.
     * @param array $data Dados para atualização.
     * @return mixed Dados da relação atualizada.
     * @throws Exception Se o ID não for encontrado.
     */
    public function updateServidorEfetivo(array $data, $id)
    {
        $servidorEfetivo = $this->servidorEfetivoRepository->findById($id);
        if (!$servidorEfetivo) {
            throw new Exception('Servidor efetivo não encontrado.');
        }

        return $this->servidorEfetivoRepository->update($data, $id);
    }

    /**
     * Exclui uma relação Servidor Efetivo pelo ID.
     *
     * @param int $id Identificador da relação Servidor Efetivo a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */    public function deleteServidorEfetivo($id)
    {
        return $this->servidorEfetivoRepository->delete($id);
    }

}