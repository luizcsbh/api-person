<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Services\ServidorEfetivoService;
use App\Http\Requests\ServidorEfetivoRequest;
use App\Http\Requests\StoreServidorEfetivoRequest;
use App\Http\Resources\ServidorEfetivoResource;

class ServidorEfetivoController extends Controller
{
    protected $servidorEfetivoService;

    public function __construct(ServidorEfetivoService $servidorEfetivoService)
    {
        $this->servidorEfetivoService = $servidorEfetivoService;
    }

    public function index()
    {
        try {
            $servidoresEfetivos = $this->servidorEfetivoService->getAllServidoresEfetivos();

            if($servidoresEfetivos->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum servidor efetivo encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => $servidoresEfetivos
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar servidores efetivos',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $servidorEfetivo = $this->servidorEfetivoService->getServidoresEfetivosById($id);

            if(!$servidorEfetivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Servidor efetivo não encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => $servidorEfetivo
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar servidor efetivo',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreServidorEfetivoRequest $request)
    {
        try {

            $validateData = $request->validated();
            $servidorEfetivo = $this->servidorEfetivoService->createServidorEfetivo($validateData);

            return response()->json([
                'success' => true,
                'message' => 'Servidor efetivo criado com sucesso',
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
}
