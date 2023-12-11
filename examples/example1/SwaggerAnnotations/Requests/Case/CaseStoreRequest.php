<?php

namespace App\SwaggerAnnotations\Requests\Case;

/**
 * @OA\Schema(
 *     title="Данные для создания/редактирования Дела",
 *     description="Данные для создания/редактирования Дела",
 *     type="object",
 * )
 */
class CaseStoreRequest
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
     *     title="id из справочника 'Тип дела'",
     *     example="1"
     * )
     *
     * @var int
     */
    private $kind_of_case_id;

    /**
     * @OA\Property(
     *     title="Дата начала",
     *     example="2020-01-01"
     * )
     *
     * @var date
     */
    private $start_date;

    /**
     * @OA\Property(
     *     title="Краткая суть",
     *     example="text"
     * )
     *
     * @var string
     */
    private $summary;

    /**
     * @OA\Property(
     *     title="id из справочника 'Статус изложенных фактов'",
     *     format="int64",
     *     example="1"
     * )
     * @var string
     */
    private $status_stated_fact_id;

    /**
     * @OA\Property(
     *     title="Результат рассмотрения обращения",
     *      example="text",
     * )
     *
     * @var string
     */
    private $outcome_result;

    /**
     * @OA\Property(
     *     title="id из справочника 'Результат решения'",
     *     format="int64",
     *     example="1"
     * )
     * @var string
     */
    private $types_of_solution_id;

    /**
     * @OA\Property(
     *      title="Рекомендовано включить в ежегодный доклад",
     *      description="Рекомендовано включить в ежегодный доклад",
     *      example="false"
     * )
     *
     * @var bool
     */
    public bool $include_in_report;

    /**
     * @OA\Property(
     *     title="Текст для включения в Ежегодный доклад",
     *      example="text",
     * )
     *
     * @var string
     */
    private $report_text;

}
