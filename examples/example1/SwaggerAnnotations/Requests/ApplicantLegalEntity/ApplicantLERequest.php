<?php

namespace App\SwaggerAnnotations\Requests\ApplicantLegalEntity;

/**
 * @OA\Schema(
 *      title="Данные заявителя юр лица.",
 *      description="Данные заявителя юр лица.",
 *      type="object",
 *      required={
 *          "olf_id",
 *          "name",
 *          "status_id",
 *      }
 * )
 */
class ApplicantLERequest
{


    /**
     * @OA\Property(
     *      title="olf_id",
     *      description="Государственный классификатор КР Организационно-правовые формы хозяйствующих субъектов КР",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $olf_id;

    /**
     * @OA\Property(
     *      title="Наименование",
     *      description="Наименование",
     *      example="OAO Тест"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Наименование на гос.языке",
     *      description="Наименование на гос.языке",
     *      type="string",
     *      example="Bakirov"
     * )
     *
     * @var string
     */
    public $name_kg;

    /**
     * @OA\Property(
     *      title="Фамилия руководителя",
     *      description="Фамилия руководителя",
     *      type="string",
     *      example="Bakirov Sultanbek"
     * )
     *
     * @var string
     */
    public $last_name_manager;

    /**
     * @OA\Property(
     *      title="Имя руководителя",
     *      description="Имя руководителя",
     *      type="string",
     *      example="Sultanbek"
     * )
     *
     * @var string
     */

    public $name_manager;

    /**
     * @OA\Property(
     *      title="Отчество руководителя",
     *      description="Отчество руководителя",
     *      type="string",
     *      example="Sultanbekovich"
     * )
     *
     * @var string
     */

    public $patronymic_manager;

    /**
     * @OA\Property(
     *      title="Должность руководителя",
     *      description="Должность руководителя",
     *      type="string",
     *      example="Sultanbekovich"
     * )
     *
     * @var string
     */

    public $position_manager;

    /**
     * @OA\Property(
     *      title="ИНН",
     *      description="ИНН",
     *      type="string",
     *      example="12345678765432"
     * )
     *
     * @var string
     */

    public $inn;

    /**
     * @OA\Property(
     *      title="ОКПО",
     *      description="ОКПО",
     *      type="string",
     *      example=" "
     * )
     *
     * @var string
     */

    public $okpo;

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
     *     title="Дата создания",
     *     description="Дата создания пользователя",
     *     example="Дата регистрации 17:50:45",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    public $date_registration;

    /**
     * @OA\Property(
     *      title="Юридический адрес",
     *      description="Юридический адрес",
     *      type="string",
     *      example=" adress"
     * )
     *
     * @var string
     */
    public $registration_address;

    /**
     * @OA\Property(
     *      title="Почтовый адрес (фактический)",
     *      description="Почтовый адрес (фактический)",
     *      type="string",
     *      example=" adress"
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
