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
     * Retorna todas as relações Servidor Efetivo cadastradas.
     *
     * @return mixed Lista de relações Servidor Efetivo.
     */
    public function getAllServidoresEfetivos()
    {
        return DB::transaction(function () {
            return $this->servidorEfetivoRepository->all();
        });
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
            throw new ModelNotFoundException('Servidor Efetivo não encontrado!');
        }
        return $servidorEfetivo;
    }
    
    /**
     * Cria uma nova relação Servidor Efetivo.
     *
     * @param array $data Dados da nova relação Servidor Efetivo.
     * @return mixed Dados da relação criada.
     */
    public function createServidorEfetivo(array $validatedData)
    {  
        return DB::transaction(function () use ($validatedData) {
            // Criar Pessoa
            $pessoa = $this->pessoaRepository->create([
                'pes_nome' => $validatedData['pes_nome'],
                'pes_cpf' => $validatedData['pes_cpf'],
                'pes_data_nascimento' => $validatedData['pes_data_nascimento'],
                'pes_sexo' => $validatedData['pes_sexo'],
                'pes_mae' => $validatedData['pes_mae'],
                'pes_pai' => $validatedData['pes_pai']
            ]);

            // Criar Endereço
            $endereco = $this->enderecoRepository->create([
                'cid_id' => $validatedData['cid_id'],
                'end_tipo_logradouro' => $validatedData['end_tipo_logradouro'],
                'end_logradouro' => $validatedData['end_logradouro'],
                'end_numero' => $validatedData['end_numero'],
                'end_complemento' => $validatedData['end_complemento'],
                'end_bairro' => $validatedData['end_bairro']
            ]);

            // Vincular Endereço
            $this->pessoaRepository->attachEndereco($pessoa->pes_id, $endereco->end_id);

            // Validar regra de negócio
            $this->validarVinculoTemporario($pessoa->pes_id);

            // Criar Servidor Efetivo
            $servidorEfetivo = $this->servidorEfetivoRepository->create([
                'pes_id' => $pessoa->pes_id,
                'se_matricula' => $validatedData['se_matricula']
            ]);

            return compact('pessoa', 'endereco', 'servidorEfetivo');
        });
    }

    private function validarVinculoTemporario(int $pesId): void
    {
        if (ServidorTemporario::where('pes_id', $pesId)
            ->whereNull('st_data_demissao')
            ->exists()) {
            throw new \DomainException('Pessoa com vínculo temporário ativo não pode ser servidor efetivo');
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
    public function updateServidorEfetivo($pesId, array $dados)
    {
        return DB::transaction(function () use ($pesId, $dados) {
            if(isset($dados['se_matricula'])) {
                $this->servidorEfetivoRepository->updateMatricula(
                    $pesId,
                    $dados['se_matricula']
                );
            }

            return $this->servidorEfetivoRepository->findById($pesId);
        });
    }

    /**
     * Exclui uma relação Servidor Efetivo pelo ID.
     *
     * @param int $id Identificador da relação Servidor Efetivo a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
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
            $servidorEfetivo->delete();
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
     * Verifica se existem dependências associadas a uma servidor efetivo, como pessoas e lotações.
     *
     * @param \App\Models\ServidorEfetivo $servidorEfetivo A servidor efetivo que será verificada quanto a dependências.
     * 
     * @throws \Exception Se a servidor efetivo tiver pessoas ou lotações associadas, uma exceção será lançada 
     *                    com uma mensagem informando qual tipo de dependência está impedindo a exclusão.
     * 
     * @return void
     */
    public function checkDependencies(ServidorEfetivo $servidorEfetivo)
    {
        if ($servidorEfetivo->pessoa()->exists()) {
            throw new Exception('Não é possível excluir a servidor efetivo. Existe uma pessoa associado a ela.');
        }

        if ($servidorEfetivo->lotacoes()->exists()) {
            throw new Exception('Não é possível excluir a servidor efetivo. Existem lotações associadas a ela.');
        }
    }

    public function findServidorEfetivo($id)
    {
        $servidorEfetivo = $this->servidorEfetivoRepository->findByIdWithPessoa($id);

        if (!$servidorEfetivo) {
            throw new ResourceNotFoundException('Servidor efetivo não encontrado!');
        }

        return $servidorEfetivo;
    }

}