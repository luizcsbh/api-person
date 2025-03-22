<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lotacao\LotacaoRequest;
use App\Http\Resources\LotacaoResource;
use App\Services\LotacaoService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class LotacaoController extends Controller
{
    protected $lotacaoService;

    public function __construct(LotacaoService $lotacaoService)
    {
        $this->lotacaoService = $lotacaoService;
    }

    /**
     * @OA\Get(
     *     path="/lotacoes",
     *     summary="Lista todos as lotações",
     *     description="Retorna uma lista de lotações armazenados no banco de dados.",
     *     tags={"Lotação"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de lotações retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Lotação"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum lotações encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Não há lotações cadastrados!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os lotações",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os lotações."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $lotacoes = $this->lotacaoService->paginate($perPage);
    
            if ($lotacoes->total() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma lotação encontrada',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }
    
            return LotacaoResource::collection($lotacoes)
                ->additional([
                    'success' => true,
                    'message' => 'Lista de lotações recuperada com sucesso'
                ],Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar lotações',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/lotacoes",
     *     summary="Cria uma nova Lotação",
     *     description="Registra uma nova Lotação no banco de dados.",
     *     tags={"Lotação"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados necessários para criar uma nova Lotação",
     *         @OA\JsonContent(
     *             required={"pes_id","unid_id","lot_data_lotacao","lot_data_remocao","lot_portaria"},
     *             @OA\Property(property="pes_id", type="integer", example="1"),
     *             @OA\Property(property="unid_id", type="integer", example="1"),
     *             @OA\Property(property="lot_data_lotacao", type="date", example="2021-08-23"),
     *             @OA\Property(property="lot_data_remocao", type="date", example="2024-11-01"),
     *             @OA\Property(property="lot_portaria", type="string", example="Portaria 123.023/2024")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lotação criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lotação criado com sucesso."),
     *             @OA\Property(property="data", ref="#/components/schemas/Lotação")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar o Lotação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao criar o Lotação.")
     *         )
     *     )
     * )
     */
    public function store(LotacaoRequest $request)
    {
        try{

            $validateData = $request->validated();
            $lotacao = $this->lotacaoService->createLotacao($validateData);

            return response()->json([
                'success' => true,
                'message' => 'Lotação criada com sucesso',
                'data' => $lotacao
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar Lotação',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/lotacoes/{id}",
     *     summary="Obtém os detalhes de uma Lotação",
     *     description="Retorna os detalhes de uma Lotação em específico pelo ID.",
     *     tags={"Lotação"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da Lotação",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da Lotação retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Lotação")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lotação não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Lotação não encontrado!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os lotações",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os lotações."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {

            $lotacao = $this->lotacaoService->getLotacaoById($id);

            if(!$lotacao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lotação não encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => new LotacaoResource($lotacao)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar lotação',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/lotacoes/{id}",
     *     summary="Atualiza uma Lotação existente",
     *     description="Atualiza os dados de uma Lotação com base no ID fornecido.",
     *     tags={"Lotação"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da Lotação a ser atualizado",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Dados para atualização da Lotação",
     *         @OA\JsonContent(
      *            required={"pes_id","unid_id","lot_data_lotacao","lot_data_remocao","lot_portaria"},
     *             @OA\Property(property="pes_id", type="integer", example="1"),
     *             @OA\Property(property="unid_id", type="integer", example="1"),
     *             @OA\Property(property="lot_data_lotacao", type="date", example="2021-08-23"),
     *             @OA\Property(property="lot_data_remocao", type="date", example="2024-11-01"),
     *             @OA\Property(property="lot_portaria", type="string", example="Portaria 123.023/2024")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lotação atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lotação atualizado com sucesso!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Lotação")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao processar a atualização da Lotação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao processar a solicitação.")
     *         )
     *     )
     * )
     */ 
    public function update(LotacaoRequest $request, string $id)
    {
        try {
         
            $validatedData = $request->validated();
    
            $lotacao= $this->lotacaoService->updateLotacao($validatedData, $id);
    
            if (!$lotacao) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao atualizar Lotação: Lotação não encontrada!'
                ], Response::HTTP_NOT_FOUND);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Lotação atualizada com sucesso.',
                'data' => new LotacaoResource($lotacao)
            ], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar Lotação.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/lotacoes/{id}",
     *     summary="Exclui um Lotação",
     *     description="Exclui um Lotação do banco de dados com base no ID fornecido.",
     *     tags={"Lotação"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da Lotação a ser excluído",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lotação excluído com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lotação excluído com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lotação não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Lotação não encontrada.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao excluir a lotação devido a dependências",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao deletar a Lotação. Possivelmente há dependências associadas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao excluir o Lotação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao excluir o Lotação."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $this->lotacaoService->deleteLotacao($id);

            return response()->json([
                'success' => true,
                'message' => 'Lotação excluída com sucesso.'
            ], Response::HTTP_OK);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);

        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar a lotação. Possivelmente há dependências associadas.'
            ], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro inesperado ao deletar a Lotação.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
