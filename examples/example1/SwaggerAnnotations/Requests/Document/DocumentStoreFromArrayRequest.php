<?php

namespace App\SwaggerAnnotations\Requests\Document;

/**
 * @OA\Schema(
 *      title="Добавление документа массивом",
 *      type="object",
 *      required={
 *          "documents"
 *      }
 * )
 */

class DocumentStoreFromArrayRequest
{
    /**
     * @OA\Property(
     *      title="Массив документов",
     *      description="Данные необходимые для добавления документа надо смотреть в таблице документов в документации.",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/DocumentStoreRequest")
     * )
     *
     * @var array
     */
    public $documents;
}
