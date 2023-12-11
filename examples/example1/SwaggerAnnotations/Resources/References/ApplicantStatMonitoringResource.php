<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="статусы заявителя",
 *     description="статусы заявителя",
 *     @OA\Xml(
 *         name="ApplicantStatMonitoringResource"
 *     )
 * )
 */
class ApplicantStatMonitoringResource
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
     *      example="Истец"
     * )
     *
     * @var string
     */
    public $name_ru;

    /**
     * @OA\Property(
     *      title="name_kg",
     *      description="Название на гос языке",
     *      example="Доогер"
     * )
     *
     * @var string
     */
    public $name_kg;

    /**
     * @OA\Property(
     *      title="end_status",
     *      description="Конечный ли статус",
     *      example="1998-10-23"
     * )
     *
     * @var DateTime
     */

    public $created_at;

}
