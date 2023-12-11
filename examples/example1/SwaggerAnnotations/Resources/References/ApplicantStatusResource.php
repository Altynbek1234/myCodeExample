<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="статус обращения",
 *     description="статус обращения",
 *     @OA\Xml(
 *         name="ApplicantStatusResource"
 *     )
 * )
 */
class ApplicantStatusResource
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
     *      description="статус",
     *      example="активнвый"
     * )
     *
     * @var string
     */
    public $name_ru;

    /**
     * @OA\Property(
     *      title="name",
     *      description="статус",
     *      example="активнвый"
     * )
     *
     * @var string
     */
    public $name_kg;

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
