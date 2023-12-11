<?php

namespace App\SwaggerAnnotations\Requests\ApplicantIndividual;
/**
 * @OA\Schema(
 *      title="Данные заявителя физ лица.",
 *      description="Данные заявителя физ лица.",
 *      type="object",
 *      required={
 *          "name",
 *          "last_name",
 *          "status_id",
 *      }
 * )
 */
class ApplicantIndividualRequest
{
    /**
     * @OA\Property(
     *      title="Имя",
     *      example="Sultanbek"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Фамилия",
     *      example="Bakirov"
     * )
     *
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *      title="Отчество",
     *      example="Нурсултанович"
     * )
     *
     * @var string
     */
    public $patronymic;

    /**
     * @OA\Property(
     *      title="ИНН",
     *      example="12123485437234"
     * )
     *
     * @var string
     */
    public $inn;

    /**
     * @OA\Property(
     *      title="citizenship_id",
     *      description="id - типа гражданства",
     *      format="int64",
     *      example=3
     * )
     *
     * @var integer
     */
    public $citizenship_id;

    /**
     * @OA\Property(
     *      title="date_birth",
     *      description="дата рождентия",
     *      example="1998-10-23",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    public $date_birth;

    /**
     * @OA\Property(
     *      title="gender_id",
     *      description="id - тип гендера",
     *      format="int64",
     *      example=2
     * )
     *
     * @var integer
     */
    public $gender_id;

    /**
     * @OA\Property(
     *      title="ID soate для адреса регистрации",
     *      example="1121"
     * )
     *
     * @var string
     */
    public $soate_registration_address;

    /**
     * @OA\Property(
     *      title="ID soate для почтового адреса (фактического адреса)",
     *      example="2023"
     * )
     *
     * @var string
     */
    public $soate_postal_address;

    /**
     * @OA\Property(
     *      title="place_work_study",
     *      description="место работы или учебы",
     *      example="ООС РЕАЛ БРИК"
     * )
     *
     * @var string
     */
    public $place_work_study;

    /**
     * @OA\Property(
     *      title="position",
     *      description="позиция",
     *      example="менаджер"
     * )
     *
     * @var string
     */
    public $position;

    /**
     * @OA\Property(
     *      title="registration_address",
     *      description="Адрес регистрации",
     *      example=" г.Бишкек ул Садовая 5"
     * )
     *
     * @var string
     */
    public $registration_address;

    /**
     * @OA\Property(
     *      title="postal_address",
     *      description=" почтовый адрес ",
     *      example=" г.Бишкек ул Токтогула 41/3"
     * )
     *
     * @var string
     */
    public $postal_address;

    /**
     * @OA\Property(
     *      title="Примечание",
     *      description="Примечание",
     *      type="string",
     *      example=" text "
     * )
     *
     * @var string
     */
    public $note;

    /**
     * @OA\Property(
     *      title="status_id",
     *      description="Статус заявителя",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $status_id;
}
