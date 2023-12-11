<?php

namespace App\SwaggerAnnotations\Requests\MeasuresToViolator;

/**
 * @OA\Schema(
 *     title="Данные для создания/редактирования Предпринятыx мер к нарушителям",
 *     description="Данные для создания/редактирования Предпринятыx мер к нарушителям",
 *     type="object",
 * )
 */
class MeasuresToViolatorStoreRequest
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
     *      title="reference_employee_action_id",
     *      description="id Меры, которые приняты к нарушителям",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $reference_employee_action_id;

    /**
     * @OA\Property(
     *      title="document_id",
     *      description="id документа",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $document_id;

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
