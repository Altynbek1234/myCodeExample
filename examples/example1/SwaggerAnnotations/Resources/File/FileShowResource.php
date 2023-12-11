<?php

namespace App\SwaggerAnnotations\Resources\File;

/**
 * @OA\Schema(
 *     title="Файл",
 *     description="Файл",
 *     @OA\Xml(
 *         name="FileShowResource"
 *     )
 * )
 */
class FileShowResource
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="Тип документа",
     *      description="Тип документа",
     *      ref="#/components/schemas/TypeOfDocumentsResource"
     * )
     *
     * @var object
     */
    public $type_of_document;

    /**
     * @OA\Property(
     *      title="Комментарий",
     *      description="Комментарий",
     *      example="Комментарий"
     * )
     *
     * @var object
     */
    public $comment;

    /**
     * @OA\Property(
     *      title="Ссылка",
     *      description="Ссылка",
     *      example="http://127.0.0.1:8080/s/pNpRwptSdc6c9tJ"
     * )
     *
     * @var string
     */
    public $file;
}
