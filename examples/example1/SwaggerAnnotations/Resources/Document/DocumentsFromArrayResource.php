<?php

namespace App\SwaggerAnnotations\Resources\Document;
/**
 * @OA\Schema(
 *     title="Документы в массиве",
 *     description="Документы в массиве",
 *     @OA\Xml(
 *         name="DocumentShowFromArrayResource"
 *     )
 * )
 */

class DocumentsFromArrayResource
{
/**
     * @OA\Property(
     *      title="Массив контактов",
     *      description="Данные контакта надо смотреть в таблице контактов в документации.",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/DocumentShowResource")
     * )
     *
     * @var array
     */
    public $documents;
}
