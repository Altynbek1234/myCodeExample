<?php

namespace App\SwaggerAnnotations\Resources\ApplicantLegalEntity;

/**
 * @OA\Schema(
 *     title="Адреса юридического лица",
 *     @OA\Xml(
 *         name="ALEAddressResource"
 *     )
 * )
 */
class ALEAddressResource
{
    /**
     * @OA\Property(
     *     title="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="Адрес регистрации",
     *      example="ул Садовая 5"
     * )
     *
     * @var string
     */
    public $registration_address;

    /**
     * @OA\Property(
     *      title="Почтовый адрес",
     *      example="ул Токтогула 41/3"
     * )
     *
     * @var string
     */
    public $postal_address;

    /**
     * @OA\Property(
     *      title="ID soate для адреса регистрации",
     *      example="2016"
     * )
     *
     * @var string
     */
    public $soate_registration_address;

    /**
     * @OA\Property(
     *      title="ID soate для почтового адреса (фактического адреса)",
     *      example="2341"
     * )
     *
     * @var string
     */
    public $soate_postal_address;

    /**
     * @OA\Property(
     *      title="Фамилия руководителя",
     *      example="Иванов"
     * )
     *
     * @var string
     */
    public $last_name_manager;

    /**
     * @OA\Property(
     *      title="Имя руководителя",
     *      example="Иван"
     * )
     *
     * @var string
     */
    public $name_manager;

    /**
     * @OA\Property(
     *      title="Отчество руководителя",
     *      example="Иванович"
     * )
     *
     * @var string
     */
    public $patronymic_manager;

    /**
     * @OA\Property(
     *      title="Должность руководителя",
     *      example="Директор"
     * )
     *
     * @var string
     */
    public $position_manager;
}
