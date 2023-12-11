<?php
namespace App\SwaggerAnnotations\Resources\Soate;;

/**
 * @OA\Schema(
 *     title="Населенный пункт",
 *     description="Населенный пункт",
 *     @OA\Xml(
 *         name="SoateResource"
 *     )
 * )
 */
class SoateResource
{
    /**
     * @OA\Property(
     *      title="id",
     *      description="Идентификатор населенного пункта",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="Название",
     *      description="Название населенного пункта",
     *      example="Нарын"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Идентификатор родительского населенного пункта",
     *      description="Идентификатор родительского населенного пункта",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer|null
     */
    public $parent_id;

    /**
     * @OA\Property(
     *      title="Дочерние населенные пункты",
     *      description="Дочерние населенные пункты",
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/SoateChildrenResource")
     * )
     *
     * @var array|null
     */
    public $children;
}
