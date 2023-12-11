<?php

namespace App\SwaggerAnnotations\Requests\User;

/**
 * @OA\Schema(
 *      title="Получение токена.",
 *      description="Для работы с Postman, разработчикам необходимо такой токен.",
 *      type="object",
 *      required={
 *          "email",
 *          "password"
 *      }
 * )
 */
class GetTokenRequest
{
    /**
     * @OA\Property(
     *      title="Почта",
     *      description="Почта пользователя",
     *      example="sbakirov@gmail.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Пароль",
     *      description="Пароль пользователя",
     *      example="password"
     * )
     *
     * @var string
     */
    public $password;
}
