<?php

namespace App\Services;

use App\Models\ServidorTemporario;
use App\Repositories\Eloquent\EnderecoRepository;
use App\Repositories\Eloquent\PessoaRepository;
use App\Repositories\Eloquent\ServidorTemporarioRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ServidorTemporarioService
{
    protected $pessoaRepository;
    protected $enderecoRepository;
    protected $servidorTemporarioRepository;

    public function __construct(
        PessoaRepository $pessoaRepository,
        EnderecoRepository $enderecoRepository,
        ServidorTemporarioRepository $servidorTemporarioRepository
    ) {
        $this->pessoaRepository = $pessoaRepository;
        $this->enderecoRepository = $enderecoRepository;
        $this->servidorTemporarioRepository = $servidorTemporarioRepository;
    }
    /**
     * Retorna todas as relações Servidor Temporario cadastradas.
     *
     * @return mixed Lista de relações Servidor Temporario.
     */
    public function getAllServidoresTemporarios()
    {
        return DB::transaction(function () {
            return $this->servidorTemporarioRepository->all();
        });
    }

        /**
     * Retorna todas as relações Pessoa cadastradas com paginacao.
     *
     * @return mixed Lista de relações Pessoa paginada.
     */
    public function paginate(int $perPage = 10)
    {
        return $this->servidorTemporarioRepository->paginate($perPage);
    }

    /**
     * Retorna uma relação Servidor Temporario pelo ID.
     *
     * @param int $id Identificador da relação Servidor Temporario.
     * @return mixed Dados da relação Servidor Temporario.
     * @throws Exception Se o ID não for encontrado.
     */
    public function getServidoresTemporariosById($id)
    {
        $servidorTemporario = $this->servidorTemporarioRepository->findById($id);
        if (!$servidorTemporario) {
            throw new ModelNotFoundException('Servidor Temporario não encontrado!');
        }
        return $servidorTemporario;
    }
    
    /**
     * Cria uma nova relação Servidor Temporario.
     *
     * @param array $data Dados da nova relação Servidor Temporario.
     * @return mixed Dados da relação criada.
     */
    public function createServidorTemporario(array $validatedData)
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

            // Criar Servidor Temporario
            $servidorTemporario = $this->servidorTemporarioRepository->create([
                'pes_id' => $pessoa->pes_id,
                'st_data_admissao' => $validatedData['st_data_admissao'],
                'st_data_demissao'=> $validatedData['st_data_demissao'],
            ]);

            return compact('pessoa', 'endereco', 'servidorTemporario');
        });
    }

    private function validarVinculoTemporario(int $pesId): void
    {
        if (ServidorTemporario::where('pes_id', $pesId)
            ->whereNull('st_data_demissao')
            ->exists()) {
            throw new \DomainException('Pessoa com vínculo temporário ativo não pode ser servidor Temporario');
        }
    }

    /**
     * Atualiza uma relação Servidor Temporario existente.
     *
     * @param int $id Identificador da relação Servidor Temporario a ser atualizada.
     * @param array $data Dados para atualização.
     * @return mixed Dados da relação atualizada.
     * @throws Exception Se o ID não for encontrado.
     */
    public function updateServidorTemporario($pesId, array $dados)
    {
        return DB::transaction(function () use ($pesId, $dados) {
            if(isset($dados['se_matricula'])) {
                $this->servidorTemporarioRepository->updateMatricula(
                    $pesId,
                    $dados['se_matricula']
                );
            }

            return $this->servidorTemporarioRepository->findById($pesId);
        });
    }

    /**
     * Exclui uma relação Servidor Temporario pelo ID.
     *
     * @param int $id Identificador da relação Servidor Temporario a ser excluída.
     * @return bool True se a exclusão for bem-sucedida, False caso contrário.
     */
    public function deleteServidorTemporario($id)
    {
         DB::beginTransaction();

        try {

            $servidorTemporario = $this->servidorTemporarioRepository->findById($id);

            if(!$servidorTemporario) {
                throw new ModelNotFoundException('Servidor Temporario não encontrado!');
            }

            $this->checkDependencies($servidorTemporario);
            $servidorTemporario->delete();
            DB::commit();

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            throw $e;

        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception("Falha ao excluir servidor Temporario: " . $e->getMessage());
        }
    }

    /**
     * Verifica se existem dependências associadas a uma servidor Temporario, como pessoas e lotações.
     *
     * @param \App\Models\ServidorTemporario $servidorTemporario A servidor Temporario que será verificada quanto a dependências.
     * 
     * @throws \Exception Se a servidor Temporario tiver pessoas ou lotações associadas, uma exceção será lançada 
     *                    com uma mensagem informando qual tipo de dependência está impedindo a exclusão.
     * 
     * @return void
     */
    public function checkDependencies(ServidorTemporario $servidorTemporario)
    {
        if ($servidorTemporario->pessoa()->exists()) {
            throw new Exception('Não é possível excluir a servidor Temporario. Existe uma pessoa associado a ela.');
        }

        if ($servidorTemporario->lotacoes()->exists()) {
            throw new Exception('Não é possível excluir a servidor Temporario. Existem lotações associadas a ela.');
        }
    }

    public function findServidorTemporario($id)
    {
        $servidorTemporario = $this->servidorTemporarioRepository->findByIdWithPessoa($id);

        if (!$servidorTemporario) {
            throw new ResourceNotFoundException('Servidor Temporario não encontrado!');
        }

        return $servidorTemporario;
    }

}