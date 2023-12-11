<?php

namespace App\SwaggerAnnotations\Requests\Audit;

/**
 * @OA\Schema(
 *     title="Данные для создания/редактирования аудита",
 *     description="Данные для создания/редактирования аудита",
 *     type="object",
 * )
 */
class AuditRequest
{
    /**
     * @OA\Property(
     *     title="ID типа проверки",
     *     example="1"
     * )
     *
     * @var int
     */
    private $types_of_inspection_id;

    /**
     * @OA\Property(
     *     title="Дата проверки",
     *     example="2023-05-30"
     * )
     *
     * @var string
     */
    private $date_check;

    /**
     * @OA\Property(
     *     title="Дата начала",
     *     example="2023-05-30"
     * )
     *
     * @var string
     */
    private $start_date;

    /**
     * @OA\Property(
     *     title="Дата окончания",
     *     example="2023-05-30"
     * )
     *
     * @var string
     */
    private $end_date;

    /**
     * @OA\Property(
     *     title="ID учреждения для мониторинга",
     *     example="1"
     * )
     *
     * @var int
     */
    private $institutions_for_monitoring_id;

    /**
     * @OA\Property(
     *     title="Основание проверки",
     *     example="Some basis of verification"
     * )
     *
     * @var string
     */
    private $basis_of_verification;

    /**
     * @OA\Property(
     *     title="ID обращения",
     *     example="23"
     * )
     *
     * @var int
     */
    private $appeal_id;

    /**
     * @OA\Property(
     *     title="Фамилия ответственного",
     *     example="Doe"
     * )
     *
     * @var string
     */
    private $surname_of_responsible;

    /**
     * @OA\Property(
     *     title="Имя ответственного",
     *     example="John"
     * )
     *
     * @var string
     */
    private $name_of_responsible;

    /**
     * @OA\Property(
     *     title="Отчество ответственного",
     *     example="Smith"
     * )
     *
     * @var string
     */
    private $middle_name;

    /**
     * @OA\Property(
     *     title="Должность ответственного",
     *     example="Manager"
     * )
     *
     * @var string
     */
    private $position;

    /**
     * @OA\Property(
     *     title="Контактный номер",
     *     example="123456789"
     * )
     *
     * @var string
     */
    private $contact_number;

    /**
     * @OA\Property(
     *     title="Документ",
     *     example="Some document"
     * )
     *
     * @var string
     */
    private $document;

    /**
     * @OA\Property(
     *     title="ID результата проверки",
     *     example="1"
     * )
     *
     * @var int
     */
    private $inspection_result_id;

    /**
     * @OA\Property(
     *     title="Заключение",
     *     example="Some conclusions"
     * )
     *
     * @var string
     */
    private $conclusions;

    /**
     * @OA\Property(
     *     title="Массив ID сотрудников организации",
     *     type="array",
     *     @OA\Items(type="integer")
     * )
     *
     * @var array
     */
    private $organization_employees;

    /**
     * @OA\Property(
     *     title="Массив ID нарушений из справочника",
     *     type="array",
     *     @OA\Items(type="integer")
     * )
     *
     * @var array
     */
    private $detected_violations;

    /**
     * @OA\Property(
     *     title="Массив ID дел по делам",
     *     type="array",
     *     @OA\Items(type="integer")
     * )
     *
     * @var array
     */
    private $case_deals;

    /**
     * @OA\Property(
     *     title="Массив ID принятых мер воздействия",
     *     type="array",
     *     @OA\Items(type="integer")
     * )
     *
     * @var array
     */
    private $impact_measures_taken;

    /**
     * @OA\Property(
     *     title="Массив ID ответчиков",
     *     type="array",
     *     @OA\Items(type="integer")
     * )
     *
     * @var array
     */
    private $defendants;

    /**
     * @OA\Property(
     *     title="ID должности в организации",
     *     example="1"
     * )
     *
     * @var int
     */
    private $organization_position_id;
}
