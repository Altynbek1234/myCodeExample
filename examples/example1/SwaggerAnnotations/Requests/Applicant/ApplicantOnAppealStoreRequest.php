<?php

namespace App\SwaggerAnnotations\Requests\Applicant;
use App\SwaggerAnnotations\Requests\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *      title="Данные заявителей обращения.",
 *      description="Данные заявителей обращения.",
 *      type="object",
 *      required={
 *          "id",
 *          "appeal_interests_of_id",
 *          "personList",
 *      }
 * )
 */
class ApplicantOnAppealStoreRequest
{
    /**
     * @OA\Property(
     *      title="ID заявителья независимо организация или физ лицо",
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
     *      title="ID справочника 'Обращение в интересах (appeal_interests_of)'",
     *      example="1",
     *      type="integer"
     * )
     *
     * @var int
     */
    public $appeal_interests_of_id;

    /**
     * @OA\Property(
     *      title="Массив лиц в интересах которых поступило обращение",
     *      ref="#/components/schemas/PersonListRequest"
     * )
     *
     * @var int
     */
    public $personList;
}
