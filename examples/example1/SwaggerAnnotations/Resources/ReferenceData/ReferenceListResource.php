<?php

namespace App\SwaggerAnnotations\Resources\ReferenceData;

/**
 * @OA\Schema(
 *     title="ReferenceListResource",
 *     description="ReferenceListResource",
 *     type="object",
 * )
 */
class ReferenceListResource
{
    /**
     * @OA\Property(
     *     title="id",
     *     description="ID референса",
     *     example="1"
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     title="name",
     *     description="Название референса",
     *     example="Национальности"
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     title="Can Edit",
     *     description="Indicates if the reference data can be edited",
     *     example=true,
     * )
     *
     * @var boolean
     */
    public $can_edit;
}
