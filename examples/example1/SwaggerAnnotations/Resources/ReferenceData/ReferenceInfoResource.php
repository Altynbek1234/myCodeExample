<?php

namespace App\SwaggerAnnotations\Resources\ReferenceData;

/**
 * @OA\Schema(
 *     title="ReferenceInfoResource",
 *     description="Reference InfoResource",
 *     type="object",
 * )
 */
class ReferenceInfoResource
{
    /**
     * @OA\Property(
     *     title="Name",
     *     description="Reference name",
     *     example="Defendent",
     * )
     *
     * @var string
     */
    public $name;

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

    /**
     * @OA\Property(
     *     title="Data",
     *     description="Reference data",
     *     type="array",
     *     @OA\Items(
     *         type="object",
     *         ref="#/components/schemas/ReferenceDataResource"
     *     ),
     * )
     *
     * @var array
     */
    public $data;

    /**
     * @OA\Property(
     *     title="Fields",
     *     description="List of fields with information about each field",
     *     type="array",
     *     @OA\Items(
     *         type="object",
     *         @OA\Property(
     *             property="name",
     *             type="string",
     *             description="Field name",
     *         ),
     *         @OA\Property(
     *             property="type",
     *             type="string",
     *             description="Field type",
     *         ),
     *         @OA\Property(
     *             property="nullable",
     *             type="boolean",
     *             description="Indicates if the field can be null",
     *         ),
     *         @OA\Property(
     *             property="length",
     *             type="integer",
     *             description="Field length",
     *         ),
     *     ),
     * )
     *
     * @var array
     */
    public $fields;
}
