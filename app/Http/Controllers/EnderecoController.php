<?php

namespace App\Http\Controllers;

use App\Http\Requests\Endereco\EnderecoRequest;
use App\Http\Resources\EnderecoResource;
use App\Services\EnderecoService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class EnderecoController extends Controller
{
    protected $enderecoService;

    public function __construct(EnderecoService $enderecoService)
    {
        $this->enderecoService = $enderecoService;
    }

    /**
     * @OA\Get(
     *     path="/enderecos",
     *     summary="Lista todos os endereços",
     *     description="Retorna uma lista dos endereços armazenados no banco de dados.",
     *     tags={"Endereço"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de endereços retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Endereco"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum endereços encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Não há endereços cadastrados!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os endereços",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os endereços."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $enderecos = $this->enderecoService->paginate($perPage);
    
            if ($enderecos->total() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma endereço encontrado',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }
    
            return EnderecoResource::collection($enderecos)
                ->additional([
                    'success' => true,
                    'message' => 'Lista de endereços recuperada com sucesso'
                ],Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar endereços',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/enderecos",
     *     summary="Cria um novo Endereço",
     *     description="Registra um novo Endereço no banco de dados.",
     *     tags={"Endereço"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados necessários para criar um novo Endereço",
     *         @OA\JsonContent(
     *             required={"cid_id","end_tipo_logradouro","end_logradouro","end_numero","end_bairro"},
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
     *         description="Endereço criado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Endereco criado com sucesso."),
     *             @OA\Property(property="data", ref="#/components/schemas/Endereco")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar o Endereço",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao criar o Endereço.")
     *         )
     *     )
     * )
     */
    public function store(EnderecoRequest $request)
    {
        try{

            $validateData = $request->validated();
            $Endereco = $this->enderecoService->createEndereco($validateData);

            return response()->json([
                'success' => true,
                'message' => 'Endereço criada com sucesso',
                'data' => $Endereco
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar Endereço',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/enderecos/{id}",
     *     summary="Obtém os detalhes de uma Endereço",
     *     description="Retorna os detalhes de uma Endereço em específico pelo ID.",
     *     tags={"Endereço"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da Endereco",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da Endereco retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/Endereco")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Endereco não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Endereco não encontrado!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os enderecos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os enderecos."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        try {

            $Endereco = $this->enderecoService->getEnderecoById($id);

            if(!$Endereco) {
                return response()->json([
                    'success' => false,
                    'message' => 'Endereco não encontrado!'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => new EnderecoResource($Endereco)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar Endereço.',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Put(
     *     path="/enderecos/{id}",
     *     summary="Atualiza uma Endereço existente",
     *     description="Atualiza os dados de uma Endereço com base no ID fornecido.",
     *     tags={"Endereço"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da Endereço a ser atualizado",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Dados para atualização da Endereco",
     *         @OA\JsonContent(
     *             required={"cid_id","end_tipo_logradouro","end_logradouro","end_numero","end_complemento","end_bairro"},
     *             @OA\Property(property="cid_id", type="integer", example="1"),
     *             @OA\Property(property="end_tipo_logradouro", type="string", example="Avenida"),
     *             @OA\Property(property="end_logradouro", type="string", example="Gustavo da Silveira"),
     *             @OA\Property(property="end_numero", type="integer", example="1000"),
     *             @OA\Property(property="end_complemento", type="string", example="Bloco E, 50 apartamento 303"),
     *             @OA\Property(property="end_bairro", type="string", example="Horto Florestal")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Endereco atualizado com sucesso!"),
     *             @OA\Property(property="data", ref="#/components/schemas/Endereco")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao processar a atualização da Endereco",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao processar a solicitação.")
     *         )
     *     )
     * )
     */ 
    public function update(EnderecoRequest $request, string $id)
    {
        try {

            $validateData = $request->validated();
            $Endereco = $this->enderecoService->updateEndereco($validateData, $id);

            return response()->json([
                'success' => true,
                'message' => 'Endereco atualizada com sucesso',
                'data' => new EnderecoResource($Endereco)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar Endereco',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/enderecos/{id}",
     *     summary="Exclui um Endereco",
     *     description="Exclui um Endereço do banco de dados com base no ID fornecido.",
     *     tags={"Endereço"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da Endereço a ser excluído",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Endereço excluído com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Endereço excluído com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao excluir o Endereço",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao excluir o Endereço."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try{
            
            $this->enderecoService->deleteEndereco($id);

            return response()->json([
                'success' => true,
                'message' => 'Endereço excluída com sucesso'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar Endereço',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
