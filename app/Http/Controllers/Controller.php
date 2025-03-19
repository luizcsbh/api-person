<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     title="API-PERSON ",
 *     version="1.0.0",
 *     description="Documentação da API-PERSON com Swagger Service.",
 *     @OA\Contact(
 *         email="luizcsdev@gmail.com",
 *         name="Luiz Santos Full Stack Developer "
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Servidor Local"
 * )
 * 
 * @OA\Tag(
 *     name="Pessoas",
 *     description="Gerenciamento de pessoas"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
