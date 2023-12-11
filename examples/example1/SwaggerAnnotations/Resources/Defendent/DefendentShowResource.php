<?php

namespace App\SwaggerAnnotations\Resources\Defendent;



/**
 * @OA\Schema(
 *     title="DefendentShowResource",
 *     description="DefendentShowResource",
 *     type="object",
 * )
 */
class DefendentShowResource
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
     *     title="name",
     *     description="Имя",
     *     example="Иван"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     title="last_name",
     *     description="Фамилия",
     *     example="Иванов"
     * )
     *
     * @var string
     */
    private $last_name;

    /**
     * @OA\Property(
     *     title="patronymic",
     *     description="Отчество",
     *     example="Иванович"
     * )
     *
     * @var string
     */
    private $patronymic;

    /**
     * @OA\Property(
     *     title="inn",
     *     description="ИНН",
     *     example="123456789012"
     * )
     *
     * @var string
     */
    private $inn;

    /**
     * @OA\Property(
     *     title="organization_complaint",
     *     description="organization_complaint",
     *     ref="#/components/schemas/OrganizationComplaintResource"
     * )
     *
     * @var object
     */
    private $organization_complaint;

    /**
     * @OA\Property(
     *     title="organization_complaint",
     *     description="organization_complaint",
     *     ref="#/components/schemas/GenderResource"
     * )
     *
     * @var object
     */
    private $gender;

}
