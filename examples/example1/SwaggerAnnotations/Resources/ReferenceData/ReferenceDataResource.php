<?php

namespace App\SwaggerAnnotations\Resources\ReferenceData;

/**
 * @OA\Schema(
 *     title="ReferenceDataResource",
 *     description="Данные могут метяся в зависимости от Модели ",
 *     type="object",
 * )
 */
class ReferenceDataResource
{
    /**
     * @OA\Property(
     *     title="id",
     *     description="Identifier",
     *     example="2"
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     title="name_ru",
     *     description="Official name in Latin",
     *     example="Женский"
     * )
     *
     * @var string
     */
    private $name_ru;

    /**
     * @OA\Property(
     *     title="name_kg",
     *     description="State name in Latin",
     *     example=""
     * )
     *
     * @var string
     */
    private $name_kg;

    /**
     * @OA\Property(
     *     title="code",
     *     description="Code",
     *     example="02"
     * )
     *
     * @var string
     */
    private $code;

    /**
     * @OA\Property(
     *     title="status_id",
     *     description="Status identifier",
     *     example="1"
     * )
     *
     * @var int
     */
    private $status_id;
}
