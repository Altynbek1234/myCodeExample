<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="Уязвимые группы",
 *     description="Уязвимые группы",
 *     @OA\Xml(
 *         name="VulnerableGroupsResource"
 *     )
 * )
 */
class VulnerableGroupsResource
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
     *      title="name_ru",
     *      description="Название на официалном языке",
     *      example="вич"
     * )
     *
     * @var string
     */
    public $name_ru;

    /**
     * @OA\Property(
     *      title="name_kg",
     *      description="Название на гос языке",
     *      example="вич"
     * )
     *
     * @var string
     */
    public $name_kg;

    /**
     * @OA\Property(
     *      title="code",
     *      description="Код",
     *      example="01"
     * )
     *
     * @var string
     */
    public $code;

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
