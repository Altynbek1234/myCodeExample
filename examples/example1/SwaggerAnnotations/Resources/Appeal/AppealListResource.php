<?php

namespace App\SwaggerAnnotations\Resources\Appeal;


/**
 * @OA\Schema(
 *     title="AppealListResource",
 *     description="Список обращений",
 *     type="object",
 * )
 */
class AppealListResource
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
     *     title="Дата обращения",
     *     example="2020-01-01"
     * )
     *
     * @var string
     */
    private $date_of_appeal;

    /**
     * @OA\Property(
     *     title="Номер обращения",
     *     example="1"
     * )
     *
     * @var int
     */
    private $number;

    /**
     * @OA\Property(
     *     title="Вид обращения",
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
     *     title="applicants",
     *     description="Заявители",
     *     type="array",
     *     @OA\Items(type="array", @OA\Items(type="string")),
     *     example="[[name='surname name patronic', type='individual'],['name_kg'=>'ЖЧК CBI', 'name_ru' = 'ОСОО CBI', 'type'=>'legal_entity'],]",
     * )
     *
     * @var object
     */
    private $applicants;

    /**
     * @OA\Property(
     *     title="personList",
     *     description="Список лиц",
     *     type="array",
     *     @OA\Items(type="array", @OA\Items(type="string")),
     *     example="[[name='surname name patronic', type='individual'],['name_kg'=>'ЖЧК CBI', 'name_ru' = 'ОСОО CBI', 'type'=>'legal_entity'],]",
     * )
     *
     * @var object
     */
    private $personList;

    /**
     * @OA\Property(
     *     title="status",
     *     description="Статус обращения",
     *     type="object",
     *     example="{name_ru: 'На рассмотрении', name_kg: 'Көрүүдө'}"
     * )
     *
     * @var object
     */
    private $status;

    /**
     * @OA\Property(
     *     title="executors",
     *     description="Исполнители",
     *     type="array",
     *     @OA\Items(type="array", @OA\Items(type="string")),
     * )
     *
     * @var object
     */
    private $executors;

}
