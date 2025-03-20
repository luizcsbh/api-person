<?php

namespace App\Services;

use Exception;
use App\Repositories\PessoaRepositoryInterface;

class PessoaService
{
    protected PessoaRepositoryInterface $pessoaRepository;

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
        return $this->pessoaRepository->create($data);
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
        $Pessoa = $this->pessoaRepository->findById($id);
        if (!$Pessoa) {
            throw new Exception('Pessoa não encontrado.');
        }

        return $this->pessoaRepository->update($data, $id);
    }

    /**
     * Exclui uma relação Pessoa pelo ID.
     *
     * @param int $id Identificador da relação Pessoa a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */    public function deletePessoa($id)
    {
        return $this->pessoaRepository->delete($id);
    }
}