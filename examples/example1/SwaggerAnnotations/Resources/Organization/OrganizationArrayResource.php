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

class OrganizationArrayResource
{
/**
     * @OA\Property(
     *      title="Массив Организаций",
     *      description="Данные организации в документации.",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/OrganizationResource")
     * )
     *
     * @var array
     */
    public $data;

}
