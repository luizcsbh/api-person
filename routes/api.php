<?php

use App\Http\Controllers\LotacaoController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ServidorEfetivoController;
use App\Http\Controllers\ServidorTemporarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/pessoas', PessoaController::class);
Route::resource('/unidades', UnidadeController::class);
Route::resource('/lotacoes', LotacaoController::class);
Route::resource('/enderecos', EnderecoController::class);
Route::resource('/servidores-efetivos', ServidorEfetivoController::class);
Route::resource('servidores-temporarios', ServidorTemporarioController::class);