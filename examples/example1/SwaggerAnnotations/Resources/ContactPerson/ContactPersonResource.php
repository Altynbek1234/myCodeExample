<?php

namespace App\SwaggerAnnotations\Resources\ContactPerson;
/**
 * @OA\Schema(
 *     title="Контактное лицо",
 *     description="Контактное лицо",
 *     @OA\Xml(
 *         name="ContactPersonResource"
 *     )
 * )
 */
class ContactPersonResource
{

    /**
     * @OA\Property(
     *      title="id",
     *      description="id контакта",
     *      format="int64",
     *      example=null
     * )
     *
     * @var integer
     */
    public $id;


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
     *      example="Bakirov"
     * )
     *
     * @var string
     */
    public $patronymic;

    /**
     * @OA\Property(
     *      title="Должность если юр лицо/степень родства если физ лицо",
     *      example="директор"
     * )
     *
     * @var string
     */
    public $position_dok;

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
     *      title="Массив контактов",
     *      description="Данные контакта надо смотреть в таблице контактов в документации.",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/ContactDetailsRequest")
     * )
     *
     * @var array
     */
    public $contacts_detalis;
}
