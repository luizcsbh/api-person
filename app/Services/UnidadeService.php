<?php

namespace App\Services;

use Exception;
use App\Repositories\UnidadeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UnidadeService
{
    protected  $unidadeRepository;

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
     * Retorna todas as relações unidades cadastradas com paginacao.
     *
     * @return mixed Lista de relações unidades paginada.
     */
    public function paginate(int $perPage = 10)
    {
        return $this->unidadeRepository->paginate($perPage);
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
        DB::beginTransaction();

        try {
            
            $unidade = $this->unidadeRepository->create($data);

            DB::commit(); 

            return $unidade; 

        } catch (\Exception $e) {
            DB::rollBack(); 
            throw new Exception("Erro ao criar unidade: " . $e->getMessage());
        }
    }

    /**
     * Atualiza uma relação Unidade existente.
     *
     * @param int $id Identificador da relação Unidade a ser atualizada.
     * @param array $data Dados para atualização.
     * @return mixed Dados da relação atualizada.
     * @throws Exception Se o ID não for encontrado.
     */
    public function updateUnidade(array $data, int $id)
    {
        try {
            DB::beginTransaction();
            $unidade = $this->unidadeRepository->findById($id);
            
            if (!$unidade) {
                throw new Exception("Erro: Unidade não encontrada!");
            }
    
            $unidade->update($data);
    
            DB::commit();
            return $unidade; 
    
        } catch (Exception $e) {
            DB::rollBack(); 
            throw new Exception("Falha ao atualizar unidade: " . $e->getMessage());
        }
    }
    
    /**
     * Exclui uma relação Unidade pelo ID.
     *
     * @param int $id Identificador da relação Unidade a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */    
    public function deleteUnidade(int $id)
    {
        DB::beginTransaction(); // Inicia a transação

        try {
            
            $unidade = $this->unidadeRepository->findById($id);

            if (!$unidade) {
                throw new Exception('Erro: Unidade não encontrada!');        
            }

            $unidade->delete($id);

            DB::commit(); 

            return $unidade;

        } catch (Exception $e) {
            DB::rollBack(); 
            throw new Exception('Falha ao deletar unidade: ' . $e->getMessage());
        }
    }

}