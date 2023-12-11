<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="По виду обращений",
 *     description="По виду обращений",
 *     @OA\Xml(
 *         name="TypeOfAppealsResource"
 *     )
 * )
 */
class TypeOfAppealsResource
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
     *      example="просьба на участие в суде"
     * )
     *
     * @var string
     */
    public $name_ru;

    /**
     * @OA\Property(
     *      title="name_kg",
     *      description="Название на гос языке",
     *      example="просьба на участие в суде"
     * )
     *
     * @var string
     */
    public $name_kg;
}
