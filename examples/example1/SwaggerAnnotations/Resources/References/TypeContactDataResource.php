<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="Тип контактных данных",
 *     description="Тип контактных данных",
 *     @OA\Xml(
 *         name="TypeContactDataResource"
 *     )
 * )
 */
class TypeContactDataResource
{

    /**
     * @OA\Property(
     *      title="id",
     *      description="id ",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */

    public $id;


    /**
     * @OA\Property(
     *      title="name",
     *      description="Название ",
     *      example="рабочий телефон"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="created_at",
     *      description="дата создания",
     *      example="1998-10-23",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    public $created_at;

    /**
     * @OA\Property(
     *      title="status_id",
     *      description="id статуса ",
     *      format="int64",
     *      example=2
     * )
     *
     * @var integer
     */

    public $status_id;
}
