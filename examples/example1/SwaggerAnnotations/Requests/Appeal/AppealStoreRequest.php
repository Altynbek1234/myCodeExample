<?php

namespace App\SwaggerAnnotations\Requests\Appeal;

/**
 * @OA\Schema(
 *     title="Данные для создания/редактирования обращения",
 *     description="Данные для создания/редактирования обращения",
 *     type="object",
 * )
 */
class AppealStoreRequest
{
    /**
     * @OA\Property(
     *     title="id типов/видов обращений (жалоба, предложение, заявление и т.д.)",
     *     example="1"
     * )
     *
     * @var int
     */
    private $type_of_appeal_id;

    /**
     * @OA\Property(
     *     title="id обращений по количеству заявителей (индивидуальное, коллективное)",
     *     example="1"
     * )
     *
     * @var int
     */
    private $type_appeal_count_id;

    /**
     * @OA\Property(
     *     title="id из справочника 'языки обращений'",
     *     example="1"
     * )
     *
     * @var int
     */
    private $appeal_language_id;

    /**
     * @OA\Property(
     *     title="id из справочника 'частота обращений' (первый раз, повторное обращение)",
     *     example="1"
     * )
     *
     * @var int
     */
    private  $frequenciesy_of_appeal_id;

    /**
     * @OA\Property(
     *     title="id из справочника 'должность' (на имя кого обращение (акыйкатчы))",
     *     example="1"
     * )
     *
     * @var int
     */
    private $organization_position_id;

    /**
     * @OA\Property(
     *     title="id из справочника 'каналы поступления письменных обращений' (почта, общественная приемная и т.д.)",
     *     example="1"
     * )
     *
     * @var int
     */
    private $receipt_channel_id;

    /**
     * @OA\Property(
     *     title="id из справочника Представительства ИО КР (Центральный аппарат г. Бишкек, Баткенская область и т.д.)",
     *     example="1"
     * )
     *
     * @var int
     */
    private $representative_io_id;

    /**
     * @OA\Property(
     *     title="Суть/описание обращения",
     *     example="text"
     * )
     *
     * @var string
     */
    private $description;

    /**
     * @OA\Property(
     *     title="Количество страниц/листов обращения",
     *     example="1"
     * )
     * @var int
     */
    private $count_of_pages;

    /**
     * @OA\Property(
     *     title="Дата и время обращения",
     *     example="2020-01-01"
     * )
     *
     * @var string
     */
    private $date_of_appeal;

    /**
     * @OA\Property(
     *     title="Регистрационный номер обращения",
     *     description="",
     *     example="1"
     * )
     *
     * @var int
     */
    private $number;

    /**
     * @OA\Property(
     *     title="Исходящий номер обращения",
     *     example="1"
     * )
     *
     * @var string
     */
    private $ish_number;

    /**
     * @OA\Property(
     *     title="Дата исход. обращения",
     *     example="2020-01-01"
     * )
     *
     * @var date
     */
    private $ish_date;

    /**
     * @OA\Property(
     *     title="Комментарий к обращению",
     *     example="text"
     * )
     *
     * @var string
     */
    private $comment;

    /**
     * @OA\Property(
     *     title="Массив данных из выборки 'Характер вопроса'.",
     *     ref="#/components/schemas/PivoteTable"
     * )
     * @var array
     */
    private $character_of_questions;

    /**
     * @OA\Property(
     *     title="Массив id из справочника 'Категории обращений'. Обязательно должно быть заполнено хотя бы одно значение.",
     *     example="[1,2,3]",
     *     type="array",
     *     @OA\Items(type="integer")
     * )
     *
     * @var int
     */
    private $category_of_appeal_ids;

    /**
     * @OA\Property(
     *     title="Массив организаций как на примере.",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/OrganizationRequest")
     * )
     *
     * @var int
     */
    private $organizations;

    /**
     * @OA\Property(
     *     title="Массив заявителей как на примере.",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/ApplicantOnAppealStoreRequest")
     * )
     *
     * @var int
     */
    private $applicants;
}
