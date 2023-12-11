<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;


/**
 * @OA\Schema(
 *     title="OrganizationPositionResource",
 *     description="Должность",
 *     type="object",
 * )
 */
class OrganizationPositionResource
{
    //use name_ru, name_kg, status_references
    /**
     * @OA\Property(
     *     title="id",
     *     description="Идентификатор",
     *     example="1",
     *     type="integer"
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     title="name_ru",
     *     description="Официальное наименование",
     *     example="Директор",
     *     type="string"
     * )
     *
     * @var string
     */
    private $name_ru;

    /**
     * @OA\Property(
     *     title="name_kg",
     *     description="Государственное наименование",
     *     example="Директор",
     *     type="string"
     * )
     *
     * @var string
     */
    private $name_kg;
}
