<?php

namespace App\Services;

use App\Models\Unidade;
use Exception;
use App\Repositories\UnidadeRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class UnidadeService
{
    protected $unidadeRepository;


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
        return DB::transaction(function () {
            return $this->unidadeRepository->all();
        });
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
            throw new ModelNotFoundException('Unidade não encontrado.');
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
        DB::beginTransaction();

        try {
            
            $unidade = $this->unidadeRepository->findById($id);
            
            if (!$unidade) {
                throw new ModelNotFoundException("Erro: Unidade não encontrada!");
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
    
     public function deleteUnidade(int $id): void
     {
        DB::beginTransaction();

        try {
     
            $unidade = $this->unidadeRepository->findById($id);

            if (!$unidade) {
                throw new ModelNotFoundException('Unidade não encontrada!');
            }

            $this->checkDependencies($unidade);
            $unidade->delete();
            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e; 

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao excluir a unidade: ' . $e->getMessage());
        }
    }

    /**
     * Verifica se existem dependências associadas a uma unidade, como endereços e lotações.
     *
     * @param \App\Models\Unidade $unidade A unidade que será verificada quanto a dependências.
     * 
     * @throws \Exception Se a unidade tiver endereços ou lotações associadas, uma exceção será lançada 
     *                    com uma mensagem informando qual tipo de dependência está impedindo a exclusão.
     * 
     * @return void
     */
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