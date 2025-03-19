<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pessoa\StorePessoaRequest;
use App\Http\Requests\Pessoa\UpdatePessoaRequest;
use App\Http\Resources\PessoaResource;
use App\Services\PessoaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PessoaController extends Controller
{
    protected $pessoaService;

    public function __construct(PessoaService $pessoaService)
    {
        $this->pessoaService = $pessoaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $pessoas = $this->pessoaService->getAllPessoas();

            if($pessoas->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma pessoa encontrada'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => PessoaResource::collection($pessoas)
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar pessoas',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePessoaRequest $request)
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(UpdatePessoaRequest $request, string $id)
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            
            $this->pessoaService->deletePessoa($id);

            return response()->json([
                'success' => true,
                'message' => 'Pessoa excluída com sucesso'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao deletar pessoa',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
