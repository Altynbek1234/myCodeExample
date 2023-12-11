<?php

namespace App\SwaggerAnnotations\Requests\Auth;

/**
 * @OA\Schema(
 *      title="Регистрация пользователя",
 *      description="Регистрация пользователя",
 *      type="object",
 *      required={
 *          "name",
 *          "email",
 *          "password",
 *          "password_confirmation"
 *      }
 * )
 */
class RegisterRequest
{
    /**
     * @OA\Property(
     *      title="Имя пользователя",
     *      description="Имя пользователя",
     *      example="Sultan"
     * )
     *
     * @var string
     */
    public string $name;

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
     *      title="Пароль",
     *      description="Пароль пользователя",
     *      example="password"
     * )
     *
     * @var string
     */
    public string $password_confirmation;

}
