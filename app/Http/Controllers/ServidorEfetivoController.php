<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\PessoaService;
use App\Services\EnderecoService;
use App\Services\ServidorEfetivoService;
use App\Http\Resources\ServidorEfetivoResource;
use App\Http\Requests\Servidor\Efetivo\ServidorEfetivoRequest;
use Illuminate\Support\Facades\DB;

class ServidorEfetivoController extends Controller
{
    protected $servidorEfetivoService;
    protected $pessoaService;
    protected $enderecoService;

    public function __construct(
        ServidorEfetivoService $servidorEfetivoService,
        PessoaService $pessoaService,
        EnderecoService $enderecoService
    )
    {
        $this->servidorEfetivoService = $servidorEfetivoService;
        $this->pessoaService = $pessoaService;
        $this->enderecoService = $enderecoService;
    }

    /**
     * @OA\Get(
     *     path="/servidores-efetivos",
     *     summary="Lista todos as servidores efetivos",
     *     description="Retorna uma lista de servidores efetivos armazenados no banco de dados.",
     *     tags={"Servidor Efetivo"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de servidores efetivos retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ServidorEfetivo"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum servidor efetivo encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Não há servidores efetivos cadastrados!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os servidores efetivos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os servidores efetivos."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $servidoresEfetivos = $this->servidorEfetivoService->paginate($perPage);
    
            if ($servidoresEfetivos->total() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum servidor efetivo encontrada',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }
    
            return ServidorEfetivoResource::collection($servidoresEfetivos)
                ->additional([
                    'success' => true,
                    'message' => 'Lista de servidores efetivos recuperada com sucesso'
                ],Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar servidores efetivos',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/servidores-efetivos",
     *     summary="Cria um servidor efetivo",
     *     description="Registra um servidor efetivo no banco de dados.",
     *     tags={"Servidor Efetivo"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados necessários para criar um servidor efetivo",
     *         @OA\JsonContent(
     *             required={"pes_nome","pes_cpf","se_matricula","pes_data_nascimento","pes_sexo","pes_mae","pes_pai","cid_id","end_tipo_logradouro","end_logradouro","end_numero","end_bairro"},
     *             @OA\Property(property="pes_nome", type="string", example="João da Silva"),
     *             @OA\Property(property="pes_cpf", type="string", example="111.222.333-44"),
     *             @OA\Property(property="se_matricula", type="string", example="2003456788467"),
     *             @OA\Property(property="pes_data_nascimento", type="datetime", example="1978-08-23"),
     *             @OA\Property(property="pes_sexo", type="string", example="Masculino"),
     *             @OA\Property(property="pes_mae", type="string", example="Maria Aparecida da Silva"),
     *             @OA\Property(property="pes_pai", type="string", example="Cícero Joaquim da Silva"),
     *             @OA\Property(property="cid_id", type="integer", example="1"),
     *             @OA\Property(property="end_tipo_logradouro", type="string", example="Avenida"),
     *             @OA\Property(property="end_logradouro", type="string", example="Silviano Brandão"),
     *             @OA\Property(property="end_numero", type="integer", example="1000"),
     *             @OA\Property(property="end_complemento", type="string", example="Bloco E, 50 apartamento 303"),
     *             @OA\Property(property="end_bairro", type="string", example="Horto Florestal")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pessoa criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pessoa criado com sucesso."),
     *             @OA\Property(property="data", ref="#/components/schemas/Pessoas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar o pessoa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao criar o ")
     *         )
     *     )
     * )
     */
    public function store(ServidorEfetivoRequest $request)
    {
        try {
            // Criar Pessoa
            $pessoa = $this->pessoaService->createPessoa(
                $request->only([
                    'pes_nome',
                    'pes_cpf',
                    'pes_data_nascimento',
                    'pes_sexo',
                    'pes_mae',
                    'pes_pai'
                ])
            );
    
            // Criar Endereço
            $endereco = $this->enderecoService->createEndereco(
                $request->only([
                    'end_tipo_logradouro',
                    'end_logradouro',
                    'end_numero',
                    'end_complemento',
                    'end_bairro',
                    'end_cep',
                    'cid_id'
                ])
            );
    
            // Vincular endereço à pessoa
            $pessoa->enderecos()->attach($endereco->end_id);
    
            // Criar Servidor Efetivo
            $servidorEfetivo = $this->servidorEfetivoService->createServidorEfetivo([
                'pes_id' => $pessoa->pes_id,
                'se_matricula' => $request->se_matricula
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Pessoa, endereço e servidor efetivo criados com sucesso.',
                'data' => [
                    'pessoa' => $pessoa,
                    'endereco' => $endereco,
                    'servidor_efetivo' => $servidorEfetivo
                ]
            ], Response::HTTP_CREATED);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar registros.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/servidores efetivos/{id}",
     *     summary="Obtém os detalhes de um servidor efetivo",
     *     description="Retorna os detalhes de um servidor efetivo em específico pelo ID.",
     *     tags={"Servidor Efetivo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da servidor efetivo",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da servidor efetivo retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/ServidorEfetivo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Servidor Efetivo não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Servidor Efetivo não encontrado!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os servidores efetivos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os servidores efetivos."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {

            $servidorEfetivo = $this->servidorEfetivoService->getServidoresEfetivosById($id);

            if(!$servidorEfetivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Servidor Efetivo não encontrado!'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => new ServidorEfetivoResource($servidorEfetivo)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar servidor efetivo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/servidores-efetivos/{id}",
     *     summary="Atualiza um servidor efetivo existente",
     *     description="Atualiza os dados de uma servidor efetivo com base no ID fornecido.",
     *     tags={"Servidor Efetivo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da servidor efetivo a ser atualizado",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Dados para atualização da servidor efetivo",
     *         @OA\JsonContent(
     *             required={"pes_id","se_matricula"},
     *             @OA\Property(property="pes_id", type="integer", example="2"),
     *             @OA\Property(property="se_matricula", type="string", example="2003456788467")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servidor Efetivo atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Servidor Efetivo atualizado com sucesso!"),
     *             @OA\Property(property="data", ref="#/components/schemas/ServidorEfetivo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao processar a atualização da servidor efetivo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao processar a solicitação.")
     *         )
     *     )
     * )
     */ 
    public function update(ServidorEfetivoRequest $request, string $id)
    {
        try {

            $servidorEfetivo = $this->servidorEfetivoService->getServidoresEfetivosById($id);

            $pessoa = $this->pessoaService->updatePessoa(
                $request->only([
                    'pes_nome', 'pes_cpf', 'pes_data_nascimento', 'pes_sexo', 'pes_mae', 'pes_pai'
                ]),
                $servidorEfetivo->pes_id
            );

            $endereco = $pessoa->endereco()->firstOrFail();
            $enderecoAtualizado = $this->enderecoService->updateEndereco(
                $request->only([
                    'end_tipo_logradouro', 'end_logradouro', 'end_numero', 'end_complemento', 'end_bairro','cid_id'
                ]),
                $endereco->end_id
            );

            $servidorAtualizado = $this->servidorEfetivoService->updateServidorEfetivo(
                ['se_matricula' => $request->se_matricula],
                $id
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Registros atualizados com sucesso.',
                'data' => [
                    'pessoa' => $pessoa,
                    'endereco' => $enderecoAtualizado,
                    'servidores_efetivos' => $servidorAtualizado
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar registros:',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/servidores-efetivos/{id}",
     *     summary="Exclui um servidor efetivo",
     *     description="Exclui um servidor efetivo do banco de dados com base no ID fornecido.",
     *     tags={"Servidor Efetivo"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da servidor efetivo a ser excluído",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pessoa excluído com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pessoa excluído com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao excluir o servidor efetivo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao excluir o servidor efetivo."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try{
            
            $this->servidorEfetivoService->deleteServidorEfetivo($id);

            return response()->json([
                'success' => true,
                'message' => 'Pessoa excluída com sucesso'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar servidor efetivo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
