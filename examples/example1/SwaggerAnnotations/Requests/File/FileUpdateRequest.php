<?php

namespace App\SwaggerAnnotations\Requests\File;

/**
 * @OA\Schema(
 *      title="File store request",
 *      description="File store request",
 *      type="object",
 *      required={
 *          "type_of_document_id",
 *      }
 * )
 */

class FileUpdateRequest
{
    /**
     * @OA\Property(
     *      title="type_of_document_id",
     *      example="1"
     * )
     *
     * @var integer
     */
    public $type_of_document_id;

    /**
     * @OA\Property(
     *      title="comment",
     *      example="comment"
     * )
     *
     * @var string
     */
    public $comment;
}
