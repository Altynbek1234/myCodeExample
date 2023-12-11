<?php

namespace App\SwaggerAnnotations\Requests\Defendent;

use App\SwaggerAnnotations\Requests\Appeal\date;

/**
 * @OA\Schema(
 *     title="DefendentStoreRequest",
 *     description="Defendant store request",
 *     type="object",
 * )
 */
class DefendentStoreRequest
{
    /**
     * @OA\Property(
     *     title="name",
     *     description="name",
     *     type="string",
     *     example="Иван",
     *     maxLength=255,
     *     minLength=2,
     * )
     */
    public $name;

    /**
     * @OA\Property(
     *     title="last_name",
     *     description="last_name",
     *     type="string",
     *     example="Иванов",
     *     maxLength=255,
     *     minLength=2,
     * )
     */
    public $last_name;

    /**
     * @OA\Property(
     *     title="patronymic",
     *     description="patronymic",
     *     type="string",
     *     example="Иванович",
     *     maxLength=255,
     *     minLength=2,
     * )
     */
    public $patronymic;

    /**
     * @OA\Property(
     *     title="born_date",
     *     description="born_date",
     *     type="date",
     *     example="2020-01-01",
     * )
     */
    public $born_date;

    /**
     * @OA\Property(
     *     title="inn",
     *     description="inn",
     *     type="string",
     *     example="1234567890",
     *     maxLength=255,
     *     minLength=2,
     * )
     */
    public $inn;

    /**
     * @OA\Property(
     *     title="gender_id",
     *     description="id из таблицы 'genders'",
     *     example="1"
     * )
     *
     * @var int
     */
    private $gender_id;

    /**
     * @OA\Property(
     *     title=" government_agency_id",
     *     description="id из таблицы government_agency id",
     *     example="1"
     * )
     *
     * @var int
     */
    private $government_agency_id;
}
