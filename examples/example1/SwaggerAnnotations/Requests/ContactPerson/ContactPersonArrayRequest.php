<?php

namespace App\SwaggerAnnotations\Requests\ContactPerson;


/**
 * @OA\Schema(
 *      title="Данные контактного лица. Сюда нужно передать массив контактов.",
 *      description="Данные контактного лица. Нужно передать как массив. Если это контактное лицо юр лица, то передать applicant_le_id, если физ лица, то applicant_indiv_id. Если этот контакт существовал, то надо передать id. Если нет, то не передавать.",
 *      type="object",
 *      required={
 *          "name",
 *          "last_name",
 *      }
 * )
 */
class ContactPersonArrayRequest
{
/**
     * @OA\Property(
     *      title="Массив контактов",
     *      description="Данные контакта надо смотреть в таблице контактов в документации.",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/ContactPersonRequest")
     * )
     *
     * @var array
     */
    public $contacts;
}
