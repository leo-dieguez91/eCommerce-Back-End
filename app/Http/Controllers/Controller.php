<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de eCommerce",
 *     description="Documentación de la API del eCommerce",
 *     @OA\Contact(
 *         email="tu@email.com",
 *         name="Nombre del Desarrollador"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="http://localhost",
 *     description="Servidor Local"
 * )
 * 
 * @OA\SecurityScheme(
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
