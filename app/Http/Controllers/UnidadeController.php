<?php

namespace App\Http\Controllers;

use App\Http\Requests\Unidade\UnidadeRequest;
use App\Http\Resources\UnidadeResource;
use App\Services\UnidadeService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
class UnidadeController extends Controller
{
    protected $unidadeService;

    public function __construct(UnidadeService $unidadeService)
    {
        $this->unidadeService = $unidadeService;
    }

    /**
     * @OA\Get(
     *     path="/unidades",
     *     summary="Lista todos as unidades",
     *     description="Retorna uma lista de unidades armazenados no banco de dados.",
     *     tags={"Unidade"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de unidades retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Unidade"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum unidades encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Não há unidades cadastrados!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os unidades",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os unidades."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $unidades = $this->unidadeService->paginate($perPage);
    
            if ($unidades->total() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma unidade encontrada!',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }

            return UnidadeResource::collection($unidades)
                ->additional([
                    'success' => true,
                    'message' => 'Lista de unidades recuperada com sucesso.'
                ],Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar unidades',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/unidades",
     *     summary="Cria uma nova unidade",
     *     description="Registra uma nova unidade no banco de dados.",
     *     tags={"Unidade"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados necessários para criar uma nova unidade",
     *         @OA\JsonContent(
     *             required={"unid_nome","unid_sigla"},
     *             @OA\Property(property="unid_nome", type="string", example="Secretaria de Planejamento e Gestão"),
     *             @OA\Property(property="unid_sigla", type="string", example="SEPLAG"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Unidade criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unidade criado com sucesso."),
     *             @OA\Property(property="data", ref="#/components/schemas/Unidade")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar o unidade",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao criar o unidade.")
     *         )
     *     )
     * )
     */
    public function store(UnidadeRequest $request)
    {
        try{

            $validateData = $request->validated();
            $unidade = $this->unidadeService->createUnidade($validateData);

            return response()->json([
                'success' => true,
                'message' => 'Unidade criada com sucesso.',
                'data' => $unidade
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar unidade!',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/unidades/{id}",
     *     summary="Obtém os detalhes de uma unidade",
     *     description="Retorna os detalhes de uma unidade em específico pelo ID.",
     *     tags={"Unidade"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da unidade",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da unidade retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Unidade")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Unidade não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unidade não encontrado!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os unidades",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os unidades."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {

            $unidade = $this->unidadeService->getUnidadeById($id);

            if(!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unidade não encontrada!'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => new UnidadeResource($unidade)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar unidade!',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/unidades/{id}",
     *     summary="Atualiza uma unidade existente",
     *     description="Atualiza os dados de uma unidade com base no ID fornecido.",
     *     tags={"Unidade"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da unidade a ser atualizado",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Dados para atualização da unidade",
     *         @OA\JsonContent(
     *             required={"unid_nome","unid_sigla"},
     *             @OA\Property(property="unid_nome", type="string", example="Secretaria de Planejamento e Gestão"),
     *             @OA\Property(property="unid_sigla", type="string", example="SEPLAG"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unidade atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unidade atualizado com sucesso!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Unidade")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao processar a atualização da unidade",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao processar a solicitação.")
     *         )
     *     )
     * )
     */ 
    public function update(UnidadeRequest $request, int $id)
    {
        try {
         
            $validatedData = $request->validated();
    
            $unidade = $this->unidadeService->updateUnidade($validatedData, $id);
    
            if (!$unidade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar unidade: Unidade não encontrada!'
                ], Response::HTTP_NOT_FOUND);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Unidade atualizada com sucesso.',
                'data' => new UnidadeResource($unidade)
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar unidade.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/unidades/{id}",
     *     summary="Exclui uma unidade",
     *     description="Exclui uma unidade do banco de dados com base no ID fornecido.",
     *     tags={"Unidade"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da unidade a ser excluída",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unidade excluída com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unidade excluída com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Unidade não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unidade não encontrada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao excluir a unidade devido a dependências",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao deletar a unidade. Possivelmente há dependências associadas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao excluir a unidade",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro inesperado ao deletar a unidade.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $this->unidadeService->deleteUnidade($id);

            return response()->json([
                'success' => true,
                'message' => 'Unidade excluída com sucesso.'
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);

        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar a unidade. Possivelmente há dependências associadas.'
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro inesperado ao deletar a unidade.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
