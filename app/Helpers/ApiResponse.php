<?php
namespace App\Helpers;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;

class ApiResponse
{
    /**
     * Retorna a resposta JSON baseada na exceção gerada.
     *
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    public static function handleException(\Exception $e)
    {
        $message = $e->getMessage();
        $statusCode = self::getHttpStatusCode($e);

        return response()->json([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    /**
     * Retorna o código HTTP apropriado com base no tipo de exceção.
     *
     * @param \Exception $e
     * @return int
     */
    private static function getHttpStatusCode(\Exception $e): int
    {
        if ($e instanceof ModelNotFoundException) {
            return Response::HTTP_NOT_FOUND;
        } elseif ($e instanceof QueryException) {
            return Response::HTTP_BAD_REQUEST;
        } else {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }
    }
}
