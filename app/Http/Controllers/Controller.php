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
 *     name="Pessoa",
 *     description="Gerenciamento das pessoas"
 * ),
 * @OA\Tag(
 *     name="Servidor Efetivo",
 *     description="Gerenciamento dos servidores efetivos"
 * ),
 * @OA\Tag(
 *     name="Servidor Temporario",
 *     description="Gerenciamento dos servidores temporarios"
 * ),
 * @OA\Tag(
 *     name="Unidade",
 *     description="Gerenciamento das unidades"
 * ),
 * @OA\Tag(
 *     name="Lotação",
 *     description="Gerenciamento das lotações"
 * ),
 * @OA\Tag(
 *     name="Endereço",
 *     description="Gerenciamento dos endereços"
 * ),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
