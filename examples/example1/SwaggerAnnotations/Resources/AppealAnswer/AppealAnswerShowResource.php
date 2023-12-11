<?php

namespace App\SwaggerAnnotations\Resources\AppealAnswer;

/**
 * @OA\Schema(
 *     title="AppealAnswerShowResource",
 *     description="AppealAnswerShowResource",
 *     type="object",
 * )
 */
class AppealAnswerShowResource
{
    /**
     * @OA\Property(
     *     title="id",
     *     description="id",
     *     example="1"
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *      title="appeal_id",
     *      description="id заявления",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $appeal_id;

    /**
     * @OA\Property(
     *      title="applicant_le_id",
     *      description="id заявителя",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $applicant;

    /**
     * @OA\Property(
     *     title="id из справочника 'языки обращений'",
     *     example="1"
     * )
     *
     * @var int
     */
    private $issued_date;

    /**
     * @OA\Property(
     *     title="Исход.дата",
     *     example="2020-01-01"
     * )
     *
     * @var date
     */


    /**
     * @OA\Property(
     *     title="Исход.рег. номер",
     *     example="text"
     * )
     *
     * @var string
     */
    private $issued_number;

    /**
     * @OA\Property(
     *     title="Краткое содержание",
     *     example="text"
     * )
     * @var string
     */
    private $summary;

    /**
     * @OA\Property(
     *     title="Дата отправки",
     *     example="2020-01-01"
     * )
     *
     * @var date
     */
    private $sent_date;

    /**
     * @OA\Property(
     *      title="chenel_id",
     *      description="id отправики",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $receipt_channel_id;
}
