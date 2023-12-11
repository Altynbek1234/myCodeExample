<?php

namespace App\SwaggerAnnotations\Resources\PersonsInterest;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="Данные лица  в интересах которых поступило обращение",
 *     description="Данные лица",
 *     @OA\Xml(
 *         name="PersonsInterestResource"
 *     )
 * )
 */
class PersonsInterestResource
{
    /**
     * @OA\Property(property="person_info", type="object", ref="#/components/schemas/PersonResource"),
     *
     * @var array
     */
    public $person_info;

    /**
     * @OA\Property(
     *      title="id",
     *      description="id заявителя",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */

    public $id;

    /**
     * @OA\Property(
     *      title="a_individual_id",
     *      description="id заявителя физ лицо",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    public $a_individual_id;

    /**
     * @OA\Property(
     *      title="a_legal_ent_id",
     *      description="id заявителя юр лицо",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    public $a_legal_ent_id;

    /**
     * @OA\Property(
     *      title="dok_id",
     *      description="id степень родства",
     *      format="int64",
     *      example=3
     * )
     *
     * @var integer
     */
    public $dok_id;

    /**
     * @OA\Property(
     *      title="date_birth",
     *      description="дата рождентия",
     *      example="1998-10-23",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    public $date_birth;

    /**
     * @OA\Property(
     *      title="gender_id",
     *      description="id - тип гендера",
     *      format="int64",
     *      example=2
     * )
     *
     * @var integer
     */
    public $gender_id;

    /**
     * @OA\Property(
     *      title="place_work_study",
     *      description="место работы или учебы",
     *      example="ООС РЕАЛ БРИК"
     * )
     *
     * @var string
     */
    public $place_work_study;

    /**
     * @OA\Property(
     *      title="position",
     *      description="позиция",
     *      example="менаджер"
     * )
     *
     * @var string
     */
    public $position;

    /**
     * @OA\Property(
     *      title="registration_address",
     *      description="Адрес регистрации",
     *      example=" г.Бишкек ул Садовая 5"
     * )
     *
     * @var string
     */
    public $registration_address;

    /**
     * @OA\Property(
     *      title="postal_address",
     *      description=" почтовый адрес ",
     *      example=" г.Бишкек ул Токтогула 41/3"
     * )
     *
     * @var string
     */
    public $postal_address;

    /**
     * @OA\Property(
     *      title="Примечание",
     *      description="Примечание",
     *      type="string",
     *      example=" text "
     * )
     *
     * @var string
     */
    public $note;

}
