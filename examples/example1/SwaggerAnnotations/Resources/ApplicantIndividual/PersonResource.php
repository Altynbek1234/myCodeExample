<?php

namespace App\SwaggerAnnotations\Resources\ApplicantIndividual;
/**
 * @OA\Schema(
 *     title="Данные персоны",
 *     description="Пользователь",
 *     @OA\Xml(
 *         name="PersonResource"
 *     )
 * )
 */
class PersonResource
{
    /**
     * @OA\Property(
     *      title="id",
     *      description="id ползователя",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="Имя",
     *      example="Sultanbek"
     * )
     *
     * @var string
     */
    public $name;


    /**
     * @OA\Property(
     *      title="Фамилия",
     *      example="Bakirov"
     * )
     *
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *      title="Отчество",
     *      example="Нурсултанович"
     * )
     *
     * @var string
     */
    public $patronymic;


    /**
     * @OA\Property(
     *      title="ИНН",
     *      example="12123485437234"
     * )
     *
     * @var string
     */
    public $inn;
}
