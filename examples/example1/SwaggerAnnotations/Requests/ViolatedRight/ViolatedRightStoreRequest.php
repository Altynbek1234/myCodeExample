<?php

namespace App\SwaggerAnnotations\Requests\ViolatedRight;

/**
 * @OA\Schema(
 *     title="Данные для создания/редактирования Нарушенныx прав",
 *     description="Данные для создания/редактирования Нарушенныx прав",
 *     type="object",
 * )
 */
class ViolatedRightStoreRequest
{
    /**
     * @OA\Property(
     *      title="case_id",
     *      description="id дела",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $case_id;

    /**
     * @OA\Property(
     *      title="violations_classifier_id",
     *      description="id Нарушенныx прав",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $violations_classifier_id;

    /**
     * @OA\Property(
     *      title="government_agency_id",
     *      description="id Госорган",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $government_agency_id;

    /**
     * @OA\Property(
     *      title="defendant_id",
     *      description="id Должностное лицо",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $defendant_id;

    /**
     * @OA\Property(
     *     title="Примечание",
     *      example="text",
     * )
     *
     * @var string
     */

    private $note;

}
