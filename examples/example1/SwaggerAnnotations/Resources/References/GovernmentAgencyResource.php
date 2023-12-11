<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="Справочник государственных органов",
 *     description="Справочник государственных органов",
 *     @OA\Xml(
 *         name="GovernmentAgencyResource"
 *     )
 * )
 */
class GovernmentAgencyResource
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
     *      example="Кабинет министров Кыргызской Республики"
     * )
     *
     * @var string
     */
    public $name_ru;

    /**
     * @OA\Property(
     *      title="name_kg",
     *      description="Название на гос языке",
     *      example="Кабинет министров Кыргызской Республики"
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
     *      title="parent_id",
     *      description="parent id ",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */

    public $parent_id;

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
     *      title="status_id",
     *      description="id статуса ",
     *      format="int64",
     *      example=2
     * )
     *
     * @var integer
     */

    public $status_id;

    /**
     * @OA\Property(
     *      title="Дочерние подразделения",
     *      description="Дочерние подразделения",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/GovAgenChildrenResource")
     * )
     *
     * @var array|null
     */
    public $children;
}
