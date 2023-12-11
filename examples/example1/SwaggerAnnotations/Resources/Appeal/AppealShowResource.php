<?php

namespace App\SwaggerAnnotations\Resources\Appeal;


/**
 * @OA\Schema(
 *     title="AppealShowResource",
 *     description="AppealShowResource",
 *     type="object",
 * )
 */
class AppealShowResource
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
     *     title="type_of_appeal",
     *     description="Вид обращения",
     *     example="1",
     *     ref="#/components/schemas/TypeOfAppealsResource"
     * )
     *
     * @var object
     */
    private $type_of_appeal;

    /**
     * @OA\Property(
     *     title="type_of_appeal_by_count_id",
     *     description="Вид обращения по количеству заявителей",
     *     example="1",
     *     ref="#/components/schemas/TypeOfAppealsByCountResource"
     * )
     *
     * @var object
     */
    private $type_of_appeal_by_count;

    /**
     * @OA\Property(
     *     title="type_of_appeal_language_id",
     *     description="Язык обращения",
     *     ref="#/components/schemas/AppealLanguageResource"
     * )
     *
     * @var int
     */
    private $type_of_appeal_language;

    /**
     * @OA\Property(
     *     title="receipt_channel_id",
     *     description="Канал поступления обращения",
     *     ref="#/components/schemas/ReceiptChannelsResource"
     * )
     *
     * @var object
     */
    private $receipt_channel;

    /**
     * @OA\Property(
     *     title="representative_io_id",
     *     description="representative_io_id",
     *     example="1"
     * )
     *
     * @var int
     */
    private $representative_io_id;

    /**
     * @OA\Property(
     *     title="stage_id",
     *     description="Этап",
     *     ref="#/components/schemas/StageResource"
     * )
     *
     * @var object
     */
    private $stage;

    /**
     * @OA\Property(
     *     title="description",
     *     description="text",
     *     example="text"
     * )
     * @var string
     */
    private $description;

    /**
     * @OA\Property(
     *     title="date_of_appeal",
     *     description="date_of_appeal",
     *     example="2020-01-01"
     * )
     *
     * @var string
     */
    private $date_of_appeal;

    /**
     * @OA\Property(
     *     title="number",
     *     description="number",
     *     example="1"
     * )
     *
     * @var int
     */
    private $number;

    /**
     * @OA\Property(
     *     title="ish_number",
     *     description="ish_number",
     *     example="1"
     * )
     *
     * @var int
     */
    private $ish_number;

    /**
     * @OA\Property(
     *     title="ish_date",
     *     description="ish_date",
     *     example="2020-01-01"
     * )
     *
     * @var string
     */
    private $ish_date;

    /**
     * @OA\Property(
     *     title="comment",
     *     description="comment",
     *     example="comment"
     * )
     *
     * @var string
     */
    private $comment;
}
