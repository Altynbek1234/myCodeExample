<?php

namespace App\SwaggerAnnotations\Requests\AppealAnswer;

/**
 * @OA\Schema(
 *     title="Данные для создания/редактирования обращения",
 *     description="Данные для создания/редактирования обращения",
 *     type="object",
 * )
 */
class AppealAnswerStoreRequest
{
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
     *      title="applicant",
     *      description="заявитель",
     *      format="array",
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
