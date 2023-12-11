<?php

namespace App\SwaggerAnnotations\Resources\ContactDetails;
/**
 * @OA\Schema(
 *     title="Контактые данные",
 *     description="Контактые данные",
 *     @OA\Xml(
 *         name="ContactDetailsResource"
 *     )
 * )
 */
class ContactDetailsResource
{
    /**
     * @OA\Property(
     *      title="id",
     *      description="id данных",
     *      format="int64",
     *      example=3
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="applicant_le_id",
     *      description="Заявитель юр лицо",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $applicant_le_id;

    /**
     * @OA\Property(
     *      title="applicant_indiv_id",
     *      description="Заявитель физ лицо",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    public $applicant_indiv_id;

    /**
     * @OA\Property(
     *      title="Имя",
     *      example="Sultanbek"
     * )
     *
     * @var string
     */
    public $first_name;

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
     *      title="Предпочитаемый способ связи",
     *      description="Предпочитаемый способ связи",
     *      example="true"
     * )
     *
     * @var bool
     */
    public $preferred_method;


    /**
     * @OA\Property(
     *      title="Сотовый телефон",
     *      description="Сотовый телефон",
     *      type="string",
     *      example=" +998554333221"
     * )
     *
     * @var string
     */
    public $mobile;

    /**
     * @OA\Property(
     *      title="Рабочий телефон",
     *      description="Рабочий телефон телефон",
     *      type="string",
     *      example=" +998554333221"
     * )
     *
     * @var string
     */
    public $phone;


    /**
     * @OA\Property(
     *      title="email",
     *      description="email",
     *      type="string",
     *      example="test@gmail.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="whatsapp",
     *      description="Whatsapp",
     *      type="string",
     *      example="test@gmail.com"
     * )
     *
     * @var string
     */
    public $whatsapp;
}
