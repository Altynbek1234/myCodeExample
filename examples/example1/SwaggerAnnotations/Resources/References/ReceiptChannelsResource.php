<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="Канал привличения",
 *     description="Канал привличения",
 *     @OA\Xml(
 *         name="ReceiptChannelResources"
 *     )
 * )
 */
class ReceiptChannelsResource
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
     *      example="whatsapp"
     * )
     *
     * @var string
     */
    public $name_ru;

    /**
     * @OA\Property(
     *      title="name_kg",
     *      description="Название на гос языке",
     *      example="whatsapp"
     * )
     *
     * @var string
     */
    public $name_kg;
}
