<?php

namespace App\Services;

use Exception;
use App\Repositories\PessoaRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PessoaService
{
    protected $pessoaRepository;

    protected $checkRelations = [
        'enderecos',
        'lotacoes',
        'servidorEfetivo',
        'servidorTemporario'
    ];

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
        return $this->pessoaRepository->all();
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
        $Pessoa = $this->pessoaRepository->findById($id);
        if (!$Pessoa) {
            throw new Exception('Pessoa não encontrado.');
        }
        return $Pessoa;
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
                throw new Exception('Pessoa não encontrada!');
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
            $pessoa = $this->pessoaRepository->findWithRelations($id, $this->checkRelations);
            
            if (!$pessoa) {
                throw new Exception('Pessoa não encontrada.');
            }

            $this->checkRelations($pessoa);
            
            $this->pessoaRepository->delete($id);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Pessoa excluída com sucesso'
            ];

        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    protected function checkRelations($pessoa)
    {
        $errors = [];
        
        foreach ($this->checkRelations as $relation) {
            if ($this->hasActiveRelation($pessoa, $relation)) {
                $errors[] = $this->getRelationMessage($relation);
            }
        }

        if (!empty($errors)) {
            throw new Exception(
                'Não é possível excluir pessoa. ' . implode(' ', $errors)
            );
        }
    }

    protected function hasActiveRelation($pessoa, $relation)
    {
        return match($relation) {
            'enderecos' => $pessoa->enderecos->isNotEmpty(),
            'lotacoes' => $pessoa->lotacoes->isNotEmpty(),
            'servidores_efetivos' => $pessoa->servidoresEfetivos->isNotEmpty(),
            'servidores_temporarios' => $pessoa->servidoresTemporarios->isNotEmpty(),
            default => (bool) $pessoa->$relation
        };
    }

    protected function getRelationMessage($relation)
    {
        $messages = [
            'enderecos' => 'Possui endereços vinculados.',
            'lotacoes' => 'Possui lotações ativas.',
            'servidorEfetivo' => 'É servidor efetivo.',
            'servidorTemporario' => 'É servidor temporário.'
        ];

        return $messages[$relation] ?? 'Possui relacionamentos ativos.';
    }
 
}