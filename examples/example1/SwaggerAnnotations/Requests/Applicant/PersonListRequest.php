<?php

namespace App\SwaggerAnnotations\Requests\Applicant;
use App\SwaggerAnnotations\Requests\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *      title="Данные лиц в отношении которых подается жалоба.",
 *      description="Данные заявителей обращения.",
 *      type="object",
 *      required={
 *          "name",
 *          "last_name",
 *          "status_id",
 *      }
 * )
 */
class PersonListRequest
{
    /**
     * @OA\Property(
     *      title="ID лица, в отношении которых подется жалоба, независимо организация или физ лицо",
     *      example="1",
     *      type="integer"
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="individual - если физ лицо, legal - если организация.",
     *      example="individual",
     *      type="string"
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *      title="ID из справочника Степень родства(degree_of_kinships)",
     *      example="1",
     *      type="integer"
     * )
     *
     * @var integer
     */
    public $degree_id;

    /**
     * @OA\Property(
     *      title="Массив данных профили",
     *      ref="#/components/schemas/ProfilePersonDataReqauest"
     * )
     *
     * @var int
     */
    public $personList;
}
