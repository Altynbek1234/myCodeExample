<?php

namespace App\SwaggerAnnotations\Requests\Reference;


/**
 * @OA\Schema(
 *      title="Данные для справочника",
 *      description="Данные могут меняться в зависимосити от модели",
 *      type="object",
 *      required={
 *          "id"
 *      }
 * )
 */
class ReferenceRequest
{
    /**
     * @OA\Property(
     *     title="name_ru",
     *     description="Official name",
     *     example="Женский"
     * )
     *
     * @var string
     */
    private $name_ru;

    /**
     * @OA\Property(
     *     title="name_kg",
     *     description="State name ",
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
