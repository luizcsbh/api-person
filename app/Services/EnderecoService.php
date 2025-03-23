<?php

namespace App\Services;

use App\Models\Endereco;
use Exception;
use App\Repositories\EnderecoRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class EnderecoService
{
    protected $enderecoRepository;

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
        return DB::transaction(function () {
            return $this->enderecoRepository->all();
        });
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
            throw new Exception('Endereço não encontrado!');
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
        DB::beginTransaction();

        try {
            
            $endereco = $this->enderecoRepository->create($data);

            DB::commit(); 

            return $endereco; 

        } catch (\Exception $e) {
            DB::rollBack(); 
            throw new Exception("Erro ao criar endereço: " . $e->getMessage());
        }
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
        DB::beginTransaction();

        try {

            $endereco = $this->enderecoRepository->findById($id);

            if (!$endereco) {
                throw new Exception('Endereço não encontrado!');
            }
    
            $endereco->update($data);

            DB::commit();
            return $endereco;

        } catch (Exception $e) {
            DB::rollBack(); 
            throw new Exception("Falha ao atualizar endereço: " . $e->getMessage());
        }   
    }

    /**
     * Exclui uma relação Endereço pelo ID.
     *
     * @param int $id Identificador da relação Endereço a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */ 
    public function deleteEndereco($id)
    {
        DB::beginTransaction(); // Inicia a transação

        try {
            
            $endereco = $this->enderecoRepository->findById($id);

            if (!$endereco) {
                throw new Exception('Erro: Endereço não encontrada!');        
            }

            $this->checkDependencies($endereco);
            $endereco->delete();
            DB::commit(); 

        } catch (ModelNotFoundException $e) {
            DB::rollBack(); 
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao excluir o endereço: ' . $e->getMessage());
        }
    }

        /**
     * Verifica se existem dependências associadas a um pessoas, como unidades e cidades.
     *
     * @param \App\Models\Endereco $endereco O endereco que será verificada quanto a dependências.
     * 
     * @throws \Exception Se endereço tiver pessoas, unidades ou cidades associadas, uma exceção será lançada 
     *                    com uma mensagem informando qual tipo de dependência está impedindo a exclusão.
     * 
     * @return void
     */
    public function checkDependencies(Endereco $endereco)
    {
       
        if ($endereco->unidades()->exists()) {
            throw new Exception('Não é possível excluir o endereço. Existem unidades associadas a ela.');
        }

        if ($endereco->cidades()->exists()) {
            throw new Exception('Não é possível excluir o endereço. Existem cidades associadas a ela.');
        }
    }
}