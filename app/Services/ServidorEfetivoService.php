<?php

namespace App\Services;

use App\Models\ServidorEfetivo;
use App\Models\ServidorTemporario;
use App\Repositories\Eloquent\EnderecoRepository;
use App\Repositories\Eloquent\PessoaRepository;
use App\Repositories\Eloquent\ServidorEfetivoRepository;
use Exception;
use App\Repositories\ServidorEfetivoRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ServidorEfetivoService
{
    protected $pessoaRepository;
    protected $enderecoRepository;
    protected $servidorEfetivoRepository;

    public function __construct(
        PessoaRepository $pessoaRepository,
        EnderecoRepository $enderecoRepository,
        ServidorEfetivoRepository $servidorEfetivoRepository
    ) {
        $this->pessoaRepository = $pessoaRepository;
        $this->enderecoRepository = $enderecoRepository;
        $this->servidorEfetivoRepository = $servidorEfetivoRepository;
    }

    /**
     * Get all ServidorEfetivo records
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllServidoresEfetivos()
    {
        return DB::transaction(function () {
            return $this->servidorEfetivoRepository->all();
        });
    }

    /**
     * Get paginated ServidorEfetivo records
     * 
     * @param int $perPage Items per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate(int $perPage = 10)
    {
        return $this->servidorEfetivoRepository->paginate($perPage);
    }

    /**
     * Find ServidorEfetivo by ID
     * 
     * @param int $id
     * @return \App\Models\ServidorEfetivo
     * @throws ModelNotFoundException
     */
    public function getServidoresEfetivosById($id)
    {
        $servidorEfetivo = $this->servidorEfetivoRepository->findById($id);
        
        if (!$servidorEfetivo) {
            throw new ModelNotFoundException('Servidor Efetivo não encontrado!');
        }
        
        return $servidorEfetivo;
    }

    /**
     * Create a new ServidorEfetivo with related Pessoa and Endereco
     * 
     * @param array $validatedData
     * @return array
     * @throws \DomainException
     */
    public function createServidorEfetivo(array $validatedData)
    {  
        return DB::transaction(function () use ($validatedData) {
            $pessoa = $this->createPessoa($validatedData);
            $endereco = $this->createEndereco($validatedData);
            
            $this->pessoaRepository->attachEndereco($pessoa->pes_id, $endereco->end_id);
            $this->validateNoActiveTemporaryLink($pessoa->pes_id);

            $servidorEfetivo = $this->createServidorEfetivoRecord($pessoa->pes_id, $validatedData['se_matricula']);

            return [
                'pessoa' => $pessoa,
                'endereco' => $endereco,
                'servidorEfetivo' => $servidorEfetivo
            ];
        });
    }

    /**
     * Update ServidorEfetivo record
     * 
     * @param int $pesId
     * @param array $dados
     * @return \App\Models\ServidorEfetivo
     */
    public function updateServidorEfetivo($pesId, array $dados)
    {
        return DB::transaction(function () use ($pesId, $dados) {
            if(isset($dados['se_matricula'])) {
                $this->updateMatricula($pesId, $dados['se_matricula']);
            }

            return $this->servidorEfetivoRepository->findById($pesId);
        });
    }

    /**
     * Delete ServidorEfetivo record
     * 
     * @param int $id
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function deleteServidorEfetivo($id)
    {
        DB::beginTransaction();

        try {
            $servidorEfetivo = $this->servidorEfetivoRepository->findById($id);

            if(!$servidorEfetivo) {
                throw new ModelNotFoundException('Servidor efetivo não encontrado!');
            }

            $this->checkDependencies($servidorEfetivo);
            $this->servidorEfetivoRepository->delete($servidorEfetivo);
            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Falha ao excluir servidor efetivo: " . $e->getMessage());
        }
    }

    /**
     * Find ServidorEfetivo with related Pessoa
     * 
     * @param int $id
     * @return \App\Models\ServidorEfetivo
     * @throws ResourceNotFoundException
     */
    public function findServidorEfetivo($id)
    {
        $servidorEfetivo = $this->servidorEfetivoRepository->findByIdWithPessoa($id);

        if (!$servidorEfetivo) {
            throw new ResourceNotFoundException('Servidor efetivo não encontrado!');
        }

        return $servidorEfetivo;
    }

    /**
     * Create Pessoa record
     * 
     * @param array $data
     * @return \App\Models\Pessoa
     */
    private function createPessoa(array $data)
    {
        return $this->pessoaRepository->create([
            'pes_nome' => $data['pes_nome'],
            'pes_cpf' => $data['pes_cpf'],
            'pes_data_nascimento' => $data['pes_data_nascimento'],
            'pes_sexo' => $data['pes_sexo'],
            'pes_mae' => $data['pes_mae'],
            'pes_pai' => $data['pes_pai']
        ]);
    }

    /**
     * Create Endereco record
     * 
     * @param array $data
     * @return \App\Models\Endereco
     */
    private function createEndereco(array $data)
    {
        return $this->enderecoRepository->create([
            'cid_id' => $data['cid_id'],
            'end_tipo_logradouro' => $data['end_tipo_logradouro'],
            'end_logradouro' => $data['end_logradouro'],
            'end_numero' => $data['end_numero'],
            'end_complemento' => $data['end_complemento'],
            'end_bairro' => $data['end_bairro']
        ]);
    }

    /**
     * Create ServidorEfetivo record
     * 
     * @param int $pesId
     * @param string $matricula
     * @return \App\Models\ServidorEfetivo
     */
    private function createServidorEfetivoRecord($pesId, $matricula)
    {
        return $this->servidorEfetivoRepository->create([
            'pes_id' => $pesId,
            'se_matricula' => $matricula
        ]);
    }

    /**
     * Update matricula for ServidorEfetivo
     * 
     * @param int $pesId
     * @param string $matricula
     */
    private function updateMatricula($pesId, $matricula)
    {
        $this->servidorEfetivoRepository->updateMatricula($pesId, $matricula);
    }

    /**
     * Validate no active temporary link exists for the person
     * 
     * @param int $pesId
     * @throws \DomainException
     */
    private function validateNoActiveTemporaryLink(int $pesId): void
    {
        if ($this->servidorEfetivoRepository->hasActiveTemporaryLink($pesId)) {
            throw new \DomainException('Pessoa com vínculo temporário ativo não pode ser servidor efetivo');
        }
    }

    /**
     * Check if ServidorEfetivo has dependencies
     * 
     * @param ServidorEfetivo $servidorEfetivo
     * @throws Exception
     */
    private function checkDependencies(ServidorEfetivo $servidorEfetivo)
    {
        if ($this->servidorEfetivoRepository->hasPessoa($servidorEfetivo)) {
            throw new Exception('Não é possível excluir a servidor efetivo. Existe uma pessoa associado a ela.');
        }

        if ($this->servidorEfetivoRepository->hasLotacoes($servidorEfetivo)) {
            throw new Exception('Não é possível excluir a servidor efetivo. Existem lotações associadas a ela.');
        }
    }
}