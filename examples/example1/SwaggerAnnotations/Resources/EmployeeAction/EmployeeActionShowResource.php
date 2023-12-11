<?php

namespace App\SwaggerAnnotations\Resources\EmployeeAction;

/**
 * @OA\Schema(
 *     title="EmployeeActionShowResource",
 *     description="EmployeeActionShowResource",
 *     type="object",
 * )
 */
class EmployeeActionShowResource
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
     *      title="action_to_violator_id",
     *      description="id Меры, которые приняты к нарушителям",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    private $action_to_violator_id;

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
