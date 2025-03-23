<?php

namespace App\Services;

use App\Models\Pessoas;
use Exception;
use App\Repositories\PessoaRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class PessoaService
{
    protected $pessoaRepository;

    public function __construct(PessoaRepositoryInterface $pessoaRepository)
    {
        $this->pessoaRepository = $pessoaRepository;
    }
    /**
     * Retorna todas as relações Pessoa cadastradas.
     *
     * @return mixed Lista de relações Pessoa.
     */
    public function getAllPessoas()
    {
        return DB::transaction(function () {
            return $this->pessoaRepository->all();
        });
    }

    /**
     * Retorna todas as relações Pessoa cadastradas com paginacao.
     *
     * @return mixed Lista de relações Pessoa paginada.
     */
    public function paginate(int $perPage = 10)
    {
        return $this->pessoaRepository->paginate($perPage);
    }

    /**
     * Retorna uma relação Pessoa pelo ID.
     *
     * @param int $id Identificador da relação Pessoa.
     * @return mixed Dados da relação Pessoa.
     * @throws Exception Se o ID não for encontrado.
     */
    public function getPessoaById($id)
    {
        $pessoa = $this->pessoaRepository->findById($id);
        if (!$pessoa) {
            throw new ModelNotFoundException('Pessoa não encontrado.');
        }
        return $pessoa;
    }

    /**
     * Cria uma nova relação Pessoa.
     *
     * @param array $data Dados da nova relação Pessoa.
     * @return mixed Dados da relação criada.
     */
    public function createPessoa(array $data)
    {
        DB::beginTransaction();

        try {
            
            $pessoa = $this->pessoaRepository->create($data);

            DB::commit(); 

            return $pessoa; 

        } catch (\Exception $e) {
            DB::rollBack(); 
            throw new Exception("Erro ao criar pessoa: " . $e->getMessage());
        }
    }
    /**
     * Atualiza uma relação Pessoa existente.
     *
     * @param int $id Identificador da relação Pessoa a ser atualizada.
     * @param array $data Dados para atualização.
     * @return mixed Dados da relação atualizada.
     * @throws Exception Se o ID não for encontrado.
     */
    public function updatePessoa(array $data, $id)
    {
        DB::beginTransaction();

        try {

            $pessoa = $this->pessoaRepository->findById($id);

            if (!$pessoa) {
                throw new Exception('Erro: Pessoa não encontrada!');
            }
    
            $pessoa->update($data);

            DB::commit();
            return $pessoa;

        } catch (Exception $e) {
            DB::rollBack(); 
            throw new Exception("Falha ao atualizar pessoa: " . $e->getMessage());
        }   
    }

    /**
     * Exclui uma relação Pessoa pelo ID.
     *
     * @param int $id Identificador da relação Pessoa a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */ 
    public function deletePessoa($id)
    {
        DB::beginTransaction();
            
        try {

            $pessoa = $this->pessoaRepository->findById($id);
            
            if (!$pessoa) {
                throw new Exception('Pessoa não encontrada!');
            }

            $this->checkDependencies($pessoa);
            $pessoa->delete();
            DB::commit();
        
        }catch (ModelNotFoundException $e){
            DB::rollBack();
            throw $e;
        
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Erro ao excluir a pessoa: '. $e->getMessage());
        }
    }

    /**
     * Verifica se existem dependências associadas a uma pessoa como fotos, lotações, servidores temporarios,
     *                     servidores efetivos e endereços.
     *
     * @param \App\Models\Pessoa $pessoa A unidade que será verificada quanto a dependências.
     * 
     * @throws \Exception Se a pessoa tiver fotos, lotações, sevidores temporarios, efetivos ou endereços
     *                     associadas, uma exceção será lançada com uma mensagem informando qual tipo de 
     *                     dependência está impedindo a exclusão.
     * 
     * @return void
     */
    public function checkDependencies(Pessoas $pessoa)
    {
        if ($pessoa->fotos()->exists()) {
            throw new Exception('Não é possível excluir a pessoa. Existem fotos associados a ela.');
        }

        if ($pessoa->lotacoes()->exists()) {
            throw new Exception('Não é possível excluir a pessoa. Existem lotações associadas a ela.');
        }

        if ($pessoa->ServidorTemporario()->exists()) {
            throw new Exception('Não é possível excluir a pessoa. Existem servidores temporarios associadas a ela.');
        }

        if ($pessoa->servidorEfetivo()->exists()) {
            throw new Exception('Não é possível excluir a pessoa. Existem servidor efetivo associadas a ela.');
        }

        if ($pessoa->enderecos()->exists()) {
            throw new Exception('Não é possível excluir a pessoa. Existem endereços associados a ela.');
        }
    }

}