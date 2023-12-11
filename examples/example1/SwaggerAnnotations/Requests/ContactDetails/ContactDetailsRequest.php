<?php

namespace App\SwaggerAnnotations\Requests\ContactDetails;


/**
 * @OA\Schema(
 *      title="Контактые данные .",
 *      description="Контактые данные",
 *      type="object",
 *      required={
 *          "contact_person_id",
 *      }
 * )
 */
class ContactDetailsRequest
{
    /**
     * @OA\Property(
     *      title="id",
     *      description="id данных",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="contact_person_id",
     *      description="Контактное лицо id",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    public $contact_person_id;

    /**
     * @OA\Property(
     *      title="type_id",
     *      description="Тип контакта (email, phone) id беруться с правочника тип контакта",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    public $type_id;

    /**
     * @OA\Property(
     *      title="$value",
     *      description="Значение контакта (зависит от типа)",
     *      example="+996778855444"
     * )
     *
     * @var string
     */
    public $value;

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
    public $preferred;

}
