<?php

namespace App\SwaggerAnnotations\Requests\Organization;


/**
 * @OA\Schema(
 *      title="организаци в отношении которой жалоба",
 *      description="организаци в отношении которой жалоба",
 *      type="object",
 *      required={
 *          "id"
 *      }
 * )
 */
class OrganizationArrayRequest
{
/**
     * @OA\Property(
     *      title="Массив Организаций",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/OrganizationRequest")
     * )
     *
     * @var array
     */
    public $organizations;
}
