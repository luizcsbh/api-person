<?php

namespace App\Http\Controllers;

use App\Http\Requests\Unidade\StoreUnidadeRequest;
use App\Http\Requests\Unidade\UpdateUnidadeRequest;
use App\Http\Resources\UnidadeResource;
use App\Services\UnidadeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    public function index()
    {
        try {
            $unidades = $this->unidadeService->getAllUnidades();

            if($unidades->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma unidade encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => UnidadeResource::collection($unidades)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar unidades',
                'error' => $e->getMessage()
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
     *             required={"pes_nome","pes_data_nascimento","pes_sexo","pes_mae","pes_pai"},
     *             @OA\Property(property="pes_nome", type="string", example="João da Silva"),
     *             @OA\Property(property="pes_data_nascimento", type="datetime", example="1978-08-23"),
     *             @OA\Property(property="pes_sexo", type="string", example="Masculino"),
     *             @OA\Property(property="pes_mae", type="string", example="Maria Aparecida da Silva"),
     *             @OA\Property(property="pes_pai", type="string", example="Cícero Joaquim da Silva")
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
    public function store(StoreUnidadeRequest $request)
    {
        try{

            $validateData = $request->validated();
            $unidade = $this->unidadeService->createUnidade($validateData);

            return response()->json([
                'success' => true,
                'message' => 'Unidade criada com sucesso',
                'data' => $unidade
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar unidade',
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
                    'message' => 'Unidade não encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => new UnidadeResource($unidade)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar unidade',
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
     *             required={"pes_nome","pes_data_nascimento","pes_sexo","pes_mae","pes_pai"},
     *             @OA\Property(property="pes_nome", type="string", example="João da Silva"),
     *             @OA\Property(property="pes_data_nascimento", type="date", example="1978-08-23"),
     *             @OA\Property(property="pes_sexo", type="string", example="Masculino"),
     *             @OA\Property(property="pes_mae", type="string", example="Maria Aparecida da Silva"),
     *             @OA\Property(property="pes_pai", type="string", example="Cícero Joaquim da Silva")
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
    public function update(UpdateUnidadeRequest $request, string $id)
    {
        try {

            $validateData = $request->validated();
            $unidade = $this->unidadeService->updateUnidade($validateData, $id);

            return response()->json([
                'success' => true,
                'message' => 'Unidade atualizada com sucesso',
                'data' => new UnidadeResource($unidade)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar unidade',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/unidades/{id}",
     *     summary="Exclui um unidade",
     *     description="Exclui um unidade do banco de dados com base no ID fornecido.",
     *     tags={"Unidade"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da unidade a ser excluído",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Unidade excluído com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unidade excluído com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao excluir o unidade",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao excluir o unidade."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try{
            
            $this->unidadeService->deleteUnidade($id);

            return response()->json([
                'success' => true,
                'message' => 'Unidade excluída com sucesso'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar unidade',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
