<?php

namespace App\SwaggerAnnotations\Resources\Stage;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="Статус обращения",
 *     description="Статус обращения",
 *     @OA\Xml(
 *         name="StageResource"
 *     )
 * )
 */
class StageResource
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
     *      example="Себя"
     * )
     *
     * @var string
     */
    public $name_ru;

    /**
     * @OA\Property(
     *      title="name_kg",
     *      description="Название на гос языке",
     *      example="Себя"
     * )
     *
     * @var string
     */
    public $name_kg;
}
