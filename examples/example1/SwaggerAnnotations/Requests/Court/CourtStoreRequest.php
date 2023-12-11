<?php

namespace App\SwaggerAnnotations\Requests\Court;

use App\SwaggerAnnotations\Requests\Appeal\date;

/**
 * @OA\Schema(
 *     title="Параметры для создания данных о судебном заседании",
 *     type="object",
 *     required={"organization_complaint_id", "date",}
 * )
 */
class CourtStoreRequest
{
    /**
     * @OA\Property(
     *     title="id судебного процесса, если данные судебного процесса уже есть в базе",
     *     example="1"
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     title="ID Суда из справочника 'government_agency'. Но в данном случае выбирается из судебных органов. (Ленинский районный суд и т.д.)",
     *     type="integer",
     *     example="1"
     * )
     */
    private $government_agency_id;

    /**
     * @OA\Property(
     *     title="Дата судебного заседания",
     *     type="date",
     *     example="2021-01-01"
     * )
     */
    private $date;

    /**
     * @OA\Property(
     *     title="Номер зала",
     *     type="string",
     *     example="1"
     * )
     */
    private $zal_number;

    /**
     * @OA\Property(
     *     title="Ответчик",
     *     type="string",
     *     example="Бакытова Айгуль"
     * )
     */
    private $respondent;

    /**
     * @OA\Property(
     *     title="Истец",
     *     type="string",
     *     example="Каримова Айгуль"
     * )
     */
    private $plaintiff;

    /**
     * @OA\Property(
     *     title="stage",
     *     description="Стадия",
     *     type="string",
     *     example="Первая инстанция"
     * )
     */
    private $stage;

    /**
     * @OA\Property(
     *     title="ID Судьи из справочника 'Гос. служащие на действия которых поступают жалобы'. Но в данном случае выбирается из числа судьей",
     *     type="integer",
     *     example="1"
     * )
     */
    private $defendent_id;

    /**
     * @OA\Property(
     *     title="ID статуса заявителя в судебном процессе. Справочник 'Статусы заявителей на мониторинг судебных процессов'",
     *     type="integer",
     *     example="1"
     * )
     */
    private $applicant_stat_monitoring_id;
}
