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

class ContactPersonArrayResource
{
/**
     * @OA\Property(
     *      title="Массив контактов",
     *      description="Данные контакта надо смотреть в таблице контактов в документации.",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/ContactPersonResource")
     * )
     *
     * @var array
     */
    public $contacts;
}
