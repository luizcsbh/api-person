<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\{
    Request,
    Response
};
use App\Services\{
    PessoaService,
    EnderecoService,
    servidorTemporarioService
};
use App\Http\Resources\ServidorTemporarioResource;
use App\Http\Requests\Servidor\Temporario\{
    StoreservidorTemporarioRequest,
    UpdateServidorTemporarioRequest
};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class ServidorTemporarioController extends Controller
{
    protected $servidorTemporarioService;
    protected $pessoaService;
    protected $enderecoService;

    public function __construct(
        ServidorTemporarioService $servidorTemporarioService,
        PessoaService $pessoaService,
        EnderecoService $enderecoService
    )
    {
        $this->servidorTemporarioService = $servidorTemporarioService;
        $this->pessoaService = $pessoaService;
        $this->enderecoService = $enderecoService;
    }

    /**
     * @OA\Get(
     *     path="/servidores-temporarios",
     *     summary="Lista todos as servidores temporarios",
     *     description="Retorna uma lista de servidores temporarios armazenados no banco de dados.",
     *     tags={"Servidor Temporario"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de servidores temporarios retornada com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ServidorTemporario"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nenhum servidor temporario encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Não há servidores temporarios cadastrados!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os servidores temporarios",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os servidores temporarios."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $servidorestemporarios = $this->servidorTemporarioService->paginate($perPage);
    
            if ($servidorestemporarios->total() === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum servidor temporario encontrada',
                    'data' => []
                ], Response::HTTP_NOT_FOUND);
            }
    
            return ServidorTemporarioResource::collection($servidorestemporarios)
                ->additional([
                    'success' => true,
                    'message' => 'Lista de servidores temporarios recuperada com sucesso'
                ],Response::HTTP_OK);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar servidores temporarios',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/servidores-temporarios",
     *     summary="Cria um servidor temporario",
     *     description="Registra um servidor temporario no banco de dados.",
     *     tags={"Servidor Temporario"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados necessários para criar um servidor temporario",
     *         @OA\JsonContent(
     *             required={"pes_nome","pes_cpf","pes_data_nascimento","st_data_admissao","st_data_demissao","pes_sexo","pes_mae","pes_pai","cid_id","end_tipo_logradouro","end_logradouro","end_numero","end_bairro"},
     *             @OA\Property(property="pes_nome", type="string", example="João da Silva"),
     *             @OA\Property(property="pes_cpf", type="string", example="111.222.333-44"),
     *             @OA\Property(property="pes_data_nascimento", type="string", format="datetime", example="1978-08-23T12:00:00Z"),
     *             @OA\Property(property="st_data_admissao", type="string", format="datetime", example="2021-02-15T12:00:00Z"),
     *             @OA\Property(property="st_data_demissao", type="string", format="datetime", example="0000-00-00T12:00:00Z"),
     *             @OA\Property(property="pes_sexo", type="string", example="Masculino"),
     *             @OA\Property(property="pes_mae", type="string", example="Maria Aparecida da Silva"),
     *             @OA\Property(property="pes_pai", type="string", example="Cícero Joaquim da Silva"),
     *             @OA\Property(property="cid_id", type="integer", example="21"),
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
     *             @OA\Property(property="data", ref="#/components/schemas/Pessoa")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao criar o servidor temporario.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao criar o servidor temporario.")
     *         )
     *     )
     * )
     */
    public function store(StoreServidorTemporarioRequest $request)
    {
        try {
            $result = $this->servidorTemporarioService->createServidorTemporario($request->validated());

            return response()->json([
                'success'=> true,
                'data' => $result
            ], Response::HTTP_CREATED);

        } catch (\DomainException $e) {
            return response()->json([
                'success'=> false,
                'message'=>$e->getMessage()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=>  'Erro no servidor: '.$e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }   
    }

    /**
     * @OA\Get(
     *     path="/servidores-temporarios/{id}",
     *     summary="Obtém os detalhes de um servidor temporario",
     *     description="Retorna os detalhes de um servidor temporario em específico pelo ID.",
     *     tags={"Servidor Temporario"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da servidor temporario",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da servidor temporario retornados com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/ServidorTemporario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Servidor Temporario não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Servidor Temporario não encontrado!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar os servidores temporarios",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao buscar os servidores temporarios."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        
        try {

            $servidorTemporario = $this->servidorTemporarioService->getServidorestemporariosById($id);
            return new servidorTemporarioResource($servidorTemporario);

        } catch (ResourceNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        
        } catch (\Exception $e) {
            
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/servidores-temporarios/{id}",
     *     summary="Atualiza um servidor temporario existente",
     *     description="Atualiza os dados de uma servidor temporario com base no ID fornecido.",
     *     tags={"Servidor Temporario"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da servidor temporario a ser atualizado",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Dados para atualização da servidor temporario",
     *         @OA\JsonContent(
     *             required={"st_data_admissao", "st_data_demissao"},
     *             @OA\Property(property="st_data_admissao", type="string", format="datetime", example="2021-02-15T12:00:00Z"),
     *             @OA\Property(property="st_data_demissao", type="string", format="datetime", example="0000-00-00T12:00:00Z"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servidor Temporario atualizado com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Servidor Temporario atualizado com sucesso!"),
     *             @OA\Property(property="data", ref="#/components/schemas/ServidorTemporario")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Registro não encontrado!",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Registro não encontrado!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao processar a atualização da servidor temporario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Erro ao processar a solicitação.")
     *         )
     *     )
     * )
     */ 
    public function update(UpdateServidorTemporarioRequest $request, string $id)
    {
        try {
            
            $servidorTemporario = $this->servidorTemporarioService->updateservidorTemporario(
                $id, 
                $request->validated()
            );
            
            return response()->json([
                'success' => true,
                'data' => new servidorTemporarioResource($servidorTemporario)
            ],Response::HTTP_OK);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registro não encontrado!'
            ], Response::HTTP_NOT_FOUND);
            
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message' => 'Erro ao atualizar registro!',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/servidores-temporarios/{id}",
     *     summary="Exclui um servidor temporario",
     *     description="Exclui um servidor temporario do banco de dados com base no ID fornecido.",
     *     tags={"Servidor Temporario"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da servidor temporario a ser excluído",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Servidor temporario excluído com sucesso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Servidor temporario excluído com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Servidor temporario não encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Servidor temporario não encontrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao excluir o servidor temporario devido a dependências",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao deletar o servidor temporario. Possivelmente há dependências associadas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao excluir o servidor temporario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Erro ao excluir o servidor temporario."),
     *             @OA\Property(property="error", type="string", example="Detalhes do erro.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        try {
            $this->servidorTemporarioService->deleteservidorTemporario($id);

            return response()->json([
                'success' => true,
                'message' => 'Servidor temporario excluída com sucesso.'
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return ApiResponse::handleException($e);
        }
    }

}
