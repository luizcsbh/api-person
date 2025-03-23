<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\Pessoa\PessoaRequest;
use App\Http\Resources\PessoaResource;
use App\Services\PessoaService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    protected $pessoaService;

    public function __construct(PessoaService $pessoaService)
    {
        $this->pessoaService = $pessoaService;
    }

    /**
     * @OA\Get(
     *     path="/pessoas",
     *     summary="Lista todos as pessoas",
     *     description="Retorna uma lista de pessoas armazenados no banco de dados.",
     *     tags={"Pessoa"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de pessoas retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Pessoa"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum pessoa encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Não há pessoas cadastrados!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os pessoas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os pessoas."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $pessoas = $this->pessoaService->paginate($perPage);
    
            if ($pessoas->total() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma pessoa encontrada',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }
    
            return PessoaResource::collection($pessoas)
                ->additional([
                    'success' => true,
                    'message' => 'Lista de pessoas recuperada com sucesso'
                ],Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar pessoas',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/pessoas",
     *     summary="Cria uma nova pessoa",
     *     description="Registra uma nova pessoa no banco de dados.",
     *     tags={"Pessoa"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados necessários para criar uma nova pessoa",
     *         @OA\JsonContent(
     *             required={"pes_nome","pes_cpf","pes_data_nascimento","pes_sexo","pes_mae","pes_pai"},
     *             @OA\Property(property="pes_nome", type="string", example="João da Silva"),
     *             @OA\Property(property="pes_cpf", type="string", example="111.222.333-44"),
     *             @OA\Property(property="pes_data_nascimento", type="datetime", example="1978-08-23"),
     *             @OA\Property(property="pes_sexo", type="string", example="Masculino"),
     *             @OA\Property(property="pes_mae", type="string", example="Maria Aparecida da Silva"),
     *             @OA\Property(property="pes_pai", type="string", example="Cícero Joaquim da Silva")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pessoa criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pessoa criado com sucesso."),
     *             @OA\Property(property="data", ref="#/components/schemas/Pessoa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar o pessoa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao criar o pessoa.")
     *         )
     *     )
     * )
     */
    public function store(PessoaRequest $request)
    {
        try{

            $validateData = $request->validated();
            $pessoa = $this->pessoaService->createPessoa($validateData);

            return response()->json([
                'success' => true,
                'message' => 'Pessoa criada com sucesso',
                'data' => $pessoa
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar pessoa',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/pessoas/{id}",
     *     summary="Obtém os detalhes de uma pessoa",
     *     description="Retorna os detalhes de uma pessoa em específico pelo ID.",
     *     tags={"Pessoa"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da pessoa retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Pessoa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Pessoa não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pessoa não encontrado!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os pessoas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os pessoas."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {

            $pessoa = $this->pessoaService->getPessoaById($id);

            if(!$pessoa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pessoa não encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => new PessoaResource($pessoa)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar pessoa',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/pessoas/{id}",
     *     summary="Atualiza uma pessoa existente",
     *     description="Atualiza os dados de uma pessoa com base no ID fornecido.",
     *     tags={"Pessoa"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa a ser atualizado",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Dados para atualização da pessoa",
     *         @OA\JsonContent(
     *             required={"pes_nome","pes_cpf","pes_data_nascimento","pes_sexo","pes_mae","pes_pai"},
     *             @OA\Property(property="pes_nome", type="string", example="João da Silva"),
      *            @OA\Property(property="pes_cpf", type="string", example="111.222.333-44"),
     *             @OA\Property(property="pes_data_nascimento", type="date", example="1978-08-23"),
     *             @OA\Property(property="pes_sexo", type="string", example="Masculino"),
     *             @OA\Property(property="pes_mae", type="string", example="Maria Aparecida da Silva"),
     *             @OA\Property(property="pes_pai", type="string", example="Cícero Joaquim da Silva")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pessoa atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pessoa atualizado com sucesso!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Pessoa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao processar a atualização da pessoa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao processar a solicitação.")
     *         )
     *     )
     * )
     */ 
    public function update(PessoaRequest $request, string $id)
    {
        try {

            $validateData = $request->validated();
            $pessoa = $this->pessoaService->updatePessoa($validateData, $id);

            return response()->json([
                'success' => true,
                'message' => 'Pessoa atualizada com sucesso',
                'data' => new PessoaResource($pessoa)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar pessoa',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/pessoas/{id}",
     *     summary="Exclui um pessoa",
     *     description="Exclui um pessoa do banco de dados com base no ID fornecido.",
     *     tags={"Pessoa"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da pessoa a ser excluído",
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
     *         description="Erro ao excluir o pessoa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao excluir o pessoa."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try{
            
            $this->pessoaService->deletePessoa($id);

            return response()->json([
                'success' => true,
                'message' => 'Pessoa excluída com sucesso'
            ], Response::HTTP_OK);
       
        }  catch (\Exception $e) {
            return ApiResponse::handleException($e);
        }
    }
}
