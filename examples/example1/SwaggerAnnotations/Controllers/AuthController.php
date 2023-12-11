<?php

namespace App\SwaggerAnnotations\Controllers;

class AuthController
{
    /**
     * @OA\Post(
     *      path="/login",
     *      tags={"Аутентификация"},
     *      summary="Аутентификация пользователя",
     *      description="Аутентификация пользователя",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation"
     *          ),
     *          @OA\Response(
     *              response=419,
     *              description="CSRF token mismatch"
     *          ),
     *          @OA\Response(
     *              response=422,
     *              description="Validation errors"
     *          )
     *     )
     */
    public function login()
    {
    }

    /**
     * @OA\Post(
     *      path="/register",
     *      tags={"Аутентификация"},
     *      summary="Регистрация пользователя",
     *      description="Регистрация пользователя",
     *          @OA\RequestBody(
     *              required=true,
     *              @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Successful operation"
     *          ),
     *          @OA\Response(
     *              response=419,
     *              description="CSRF token mismatch"
     *          ),
     *          @OA\Response(
     *              response=422,
     *              description="Validation errors"
     *          )
     *     )
     */
    public function register()
    {
    }
}
