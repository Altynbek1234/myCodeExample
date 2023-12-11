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
class SoatesResource
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
     *      title=" parent id",
     *      description="parent id",
     *      format="int64",
     *      example=6
     * )
     *
     * @var integer
     */

    public $parent_id;

    /**
     * @OA\Property(
     *      title="settlement type id",
     *      description="settlement type id",
     *      format="int64",
     *      example=6
     * )
     *
     * @var integer
     */
    public $settlement_type_id;

    /**
     * @OA\Property(
     *      title="name_ru",
     *      description="код",
     *      example="41702000000000"
     * )
     *
     * @var string
     */
    public $code;

    /**
     * @OA\Property(
     *      title="number positiond",
     *      description="number position",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public  $number_position;

    /**
     * @OA\Property(
     *      title="level",
     *      description="level",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public  $level;

    /**
     * @OA\Property(
     *      title="name",
     *      description="Название ",
     *      example="Иссык-Кульская область"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="name_kg",
     *      description="Название",
     *      example="Иссык-Кульская область"
     * )
     *
     * @var string
     */
    public $name_kg;


    /**
     * @OA\Property(
     *      title="name_en",
     *      description="Название",
     *      example="Иссык-Кульская область"
     * )
     *
     * @var string
     */
    public $name_en;


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
