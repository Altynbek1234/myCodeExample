<?php

namespace App\SwaggerAnnotations\Resources\User;

use DateTime;

/**
 * @OA\Schema(
 *     title="Пользователь",
 *     description="Авторизованный пользователь",
 *     @OA\Xml(
 *         name="GetUserResource"
 *     )
 * )
 */
class GetUserResource
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    public $id;

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

    /**
     * @OA\Property(
     *      title="Почта",
     *      description="Почта пользователя",
     *      example="bakirov2009@gmail.com",
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *     title="Дата создания",
     *     description="Дата создания пользователя",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    public $created_at;

    /**
     * @OA\Property(
     *     title="Дата изменения",
     *     description="Дата последнего изменения пользователя",
     *     example="2020-01-27 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    public $updated_at;

}
