<?php

namespace App\Services;

use App\Models\Lotacao;
use Exception;
use App\Repositories\LotacaoRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function() {
            return $this->lotacaoRepository->all();
        });
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
        DB::beginTransaction();

        try {
            
            $lotacao = $this->lotacaoRepository->create($data);

            DB::commit(); 

            return $lotacao; 

        } catch (\Exception $e) {
            DB::rollBack(); 
            throw new Exception("Erro ao criar lotação: " . $e->getMessage());
        }
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
        DB::beginTransaction();

        try {
            
            $lotacao = $this->lotacaoRepository->findById($id);
            
            if (!$lotacao) {
                throw new ModelNotFoundException("Erro: Lotação não encontrada!");
            }
    
            $lotacao->update($data);
    
            DB::commit();
            return $lotacao; 
    
        } catch (Exception $e) {
            DB::rollBack(); 
            throw new Exception("Falha ao atualizar lotação: " . $e->getMessage());
        }
    }
    /**
     * Exclui uma relação Lotação pelo ID.
     *
     * @param int $id Identificador da relação Lotação a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */ 
    public function deleteLotacao($id)
    {
        DB::beginTransaction();

        try {
        
            $lotacao = $this->lotacaoRepository->findById($id);

            if (!$lotacao) {
                throw new ModelNotFoundException('Lotação não encontrada!');
            }

            $this->checkDependencies($lotacao);
            $lotacao->delete();
            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e; 

        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao excluir a lotação: ' . $e->getMessage());
        }
    }

    /**
     * Verifica se existem dependências associadas a uma √, como endereços e lotações.
     *
     * @param \App\Models\Lotacao $lotacao A lotação que será verificada quanto a dependências.
     * 
     * @throws \Exception Se a lotação tiver endereços ou lotações associadas, uma exceção será lançada 
     *                    com uma mensagem informando qual tipo de dependência está impedindo a exclusão.
     * 
     * @return void
     */
    public function checkDependencies(Lotacao $lotacao)
    {
        if ($lotacao->pessoa()->exists()) {
            throw new Exception('Não é possível excluir a lotação. Existem pessoas associados a ela.');
        }

        if ($lotacao->unidade()->exists()) {
            throw new Exception('Não é possível excluir a lotação. Existem unidades associadas a ela.');
        }
    }

}