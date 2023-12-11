<?php

namespace App\SwaggerAnnotations\Resources\Organization;
/**
 * @OA\Schema(
 *     title="Контактное лицо",
 *     description="Контактное лицо",
 *     @OA\Xml(
 *         name="ContactPersonResource"
 *     )
 * )
 */
class OrganizationResource
{

    /**
     * @OA\Property(
     *      title="id",
     *      description="id организации",
     *      format="int64",
     *      example=2
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *     title="$name_ru",
     *     description="$name_ru",
     *     type="string",
     *     example="Администрация",
     *     maxLength=255,
     *     minLength=2,
     * )
     */
    public $name_ru;

    /**
     * @OA\Property(
     *      title="Массив данные гос служащихв",
     *      description="Данные гос служащих.",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/DefendentShowResource")
     * )
     *
     * @var array
     */
    public $defendants;
}
