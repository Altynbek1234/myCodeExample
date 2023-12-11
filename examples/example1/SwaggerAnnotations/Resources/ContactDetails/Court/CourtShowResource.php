<?php

namespace App\SwaggerAnnotations\Resources\ContactDetails\Court;



/**
 * @OA\Schema(
 *     title="CourtShowResource",
 *     description="Данные судебного заседания",
 *     @OA\Xml(
 *         name="CourtShowResource"
 *     )
 * )
 */
class CourtShowResource
{
    /**
     * @OA\Property(
     *     title="id",
     *     description="Идентификатор",
     *     example="1"
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     title="organization_complaint",
     *     description="организация на которую жалоба",
     *     ref="#/components/schemas/GovernmentAgencyResource"
     * )
     *
     * @var \App\SwaggerAnnotations\Resources\References\GovernmentAgencyResource
     */
    private $government_agency;

    /**
     * @OA\Property(
     *     title="date",
     *     description="Дата судебного заседания",
     *     example="2021-01-01"
     * )
     *
     * @var string
     */
    private $date;

    /**
     * @OA\Property(
     *     title="zal_number",
     *     description="Номер зала",
     *     example="1"
     * )
     *
     * @var string
     */
    private $zal_number;

    /**
     * @OA\Property(
     *     title="respondent",
     *     description="Ответчик",
     *     example="Ответчик"
     * )
     *
     * @var string
     */
    private $respondent;

    /**
     * @OA\Property(
     *     title="plaintiff",
     *     description="Истец",
     *     example="Истец"
     * )
     *
     * @var string
     */
    private $plaintiff;

    /**
     * @OA\Property(
     *     title="stage",
     *     description="Стадия",
     *     example="Стадия"
     * )
     *
     * @var string
     */
    private $stage;

    /**
     * @OA\Property(
     *     title="defendent",
     *     description="Судья",
     *     ref="#/components/schemas/DefendentShowResource"
     * )
     *
     * @var \App\SwaggerAnnotations\Resources\References\DefendentShowResource
     */
    private $defendent;

    /**
     * @OA\Property(
     *     title="applicant_stat_monitoring",
     *     description="Судья",
     *     ref="#/components/schemas/ApplicantStatMonitoringResource"
     * )
     *
     * @var \App\SwaggerAnnotations\Resources\References\ApplicantStatMonitoringResource
     */
    private $applicant_stat_monitoring;
}
