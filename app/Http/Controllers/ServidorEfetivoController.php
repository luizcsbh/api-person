<?php

namespace App\Http\Controllers;

use App\Http\Requests\Servidor\Efetivo\ServidorEfetivoRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\ServidorEfetivoService;

use App\Http\Resources\ServidorEfetivoResource;

class ServidorEfetivoController extends Controller
{
    protected $servidorEfetivoService;

    public function __construct(ServidorEfetivoService $servidorEfetivoService)
    {
        $this->servidorEfetivoService = $servidorEfetivoService;
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
     *     summary="Cria uma nova servidor efetivo",
     *     description="Registra um novo servidor efetivo no banco de dados.",
     *     tags={"Servidor Efetivo"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados necessários para criar um novo servidor efetivo",
     *         @OA\JsonContent(
     *             required={"pes_id","se_matricula"},
     *             @OA\Property(property="pes_id", type="integer", example="2"),
     *             @OA\Property(property="se_matricula", type="string", example="2003456788467")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Servidor Efetivo criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Servidor Efetivo criado com sucesso."),
     *             @OA\Property(property="data", ref="#/components/schemas/ServidorEfetivo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar o servidor efetivo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao criar o servidor efetivo.")
     *         )
     *     )
     * )
     */
    public function store(ServidorEfetivoRequest $request)
    {
        try{
            
            $validateData = $request->validated();
            $servidorEfetivo = $this->servidorEfetivoService->createServidorEfetivo($validateData);

            return response()->json([
                'success' => true,
                'message' => 'Pessoa criada com sucesso',
                'data' => $servidorEfetivo
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar servidor efetivo',
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

            $validateData = $request->validated();
            $servidorEfetivo = $this->servidorEfetivoService->updateServidorEfetivo($validateData, $id);

            return response()->json([
                'success' => true,
                'message' => 'Servidor Efetivo atualizada com sucesso',
                'data' => new ServidorEfetivoResource($servidorEfetivo)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar servidor efetivo',
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
