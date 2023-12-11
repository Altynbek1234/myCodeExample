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
class OrganizationRequest
{
    /**
     * @OA\Property(
     *      title="id",
     *      description="id организации",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="defendents",
     *      description="Массив из id гос.служащих в отношении которых жалоба.",
     *      example="{id: 1, position: 1}",
     *      type="array",
     *      @OA\Items(type="object")
     * )
     *
     * @var array
     */
    public $defendents;

}
