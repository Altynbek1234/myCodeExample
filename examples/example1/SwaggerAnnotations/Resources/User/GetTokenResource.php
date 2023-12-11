<?php

namespace App\SwaggerAnnotations\Resources\User;

/**
 * @OA\Schema(
 *     title="Токен пользователя",
 *     description="Токен пользователя",
 *     @OA\Xml(
 *         name="GetTokenResource"
 *     )
 * )
 */
class GetTokenResource
{
    /**
     * @OA\Property(
     *      title="Токен пользователя",
     *      description="Токен пользователя",
     *      example="2|MORvQ5aAuApNn1UxuMvYa9j4IgmEGGP6CYodlmjp"
     * )
     *
     * @var string
     */
    public  $token;

    /**
     * @OA\Property(
     *      title="Имя",
     *      description="Имя пользователя",
     *      example="Bakirov Sultanbek"
     * )
     *
     * @var string
     */
    public $name;

}
