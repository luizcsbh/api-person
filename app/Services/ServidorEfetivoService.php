<?php

namespace App\Services;

use Exception;
use App\Repositories\ServidorEfetivoRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ServidorEfetivoService
{
    protected $servidorEfetivoRepository;

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
        return $this->servidorEfetivoRepository->findById($id)
            ?? throw new Exception('Servidor efetivo não encontrado.');
    }

    /**
     * Cria uma nova relação Servidor Efetivo.
     *
     * @param array $data Dados da nova relação Servidor Efetivo.
     * @return mixed Dados da relação criada.
     */
    public function createServidorEfetivo(array $data)
    {  
        DB::beginTransaction();
        try {
            $servidorEfetivo = $this->servidorEfetivoRepository->create($data);
            DB::commit();
            return $servidorEfetivo;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Falha ao criar servidor efetivo: "  . $e->getMessage());
        }
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
        DB::beginTransaction();
        try {
            // Busca o servidor efetivo pelo ID
            $servidorEfetivo = $this->servidorEfetivoRepository->findById($id);
            
            if (!$servidorEfetivo) {
                throw new Exception('Servidor Efetivo não encontrado!');
            }
    
            // Atualiza os dados do servidor efetivo
            $servidorEfetivo = $this->servidorEfetivoRepository->update($data, $id);
    
            DB::commit();
            return $servidorEfetivo;
    
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Falha ao atualizar servidor efetivo: " . $e->getMessage());
        }
    }
    

    /**
     * Exclui uma relação Servidor Efetivo pelo ID.
     *
     * @param int $id Identificador da relação Servidor Efetivo a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */    public function deleteServidorEfetivo($id)
    {
         DB::beginTransaction();
        try {
            $this->servidorEfetivoRepository->delete($id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Falha ao excluir servidor efetivo: " . $e->getMessage());
        }
    }

}