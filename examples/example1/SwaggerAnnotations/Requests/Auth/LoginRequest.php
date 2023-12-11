<?php

namespace App\SwaggerAnnotations\Requests\Auth;

/**
 * @OA\Schema(
 *      title="Аутентификация пользователя",
 *      description="Аутентификация пользователя",
 *      type="object",
 *      required={
 *          "email",
 *          "password",
 *      }
 * )
 */
class LoginRequest
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
    public string $email;

    /**
     * @OA\Property(
     *      title="Пароль",
     *      description="Пароль пользователя",
     *      example="password"
     * )
     *
     * @var string
     */
    public string $password;

    /**
     * @OA\Property(
     *      title="Запомнить пользователя",
     *      description="Запомнить пользователя",
     *      example="true"
     * )
     *
     * @var bool
     */
    public bool $remember;

}
