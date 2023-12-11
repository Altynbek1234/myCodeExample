<?php

namespace App\SwaggerAnnotations\Resources\ApplicantIndividual;
/**
 * @OA\Schema(
 *     title="Адреса и дополнительные данные физ лицо",
 *     @OA\Xml(
 *         name="AIlAddressResource"
 *     )
 * )
 */
class AIlAddressResource
{
    /**
     * @OA\Property(
     *      title="id",
     *      description="id физ лица",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */

    public $id;

    /**
     * @OA\Property(
     *      title="Место работы или учебы",
     *      example="ООС РЕАЛ БРИК"
     * )
     *
     * @var string
     */
    public $place_work_study;

    /**
     * @OA\Property(
     *      title="Позиция/должность",
     *      example="менеджер"
     * )
     *
     * @var string
     */
    public $position;

    /**
     * @OA\Property(
     *      title="Адрес регистрации",
     *      example="ул Садовая 5"
     * )
     *
     * @var string
     */
    public $registration_address;

    /**
     * @OA\Property(
     *      title="Почтовый адрес",
     *      example="ул Токтогула 41/3"
     * )
     *
     * @var string
     */
    public $postal_address;

    /**
     * @OA\Property(
     *      title="ID soate для адреса регистрации",
     *      example="2016"
     * )
     *
     * @var string
     */
    public $soate_registration_address;

    /**
     * @OA\Property(
     *      title="ID soate для почтового адреса (фактического адреса)",
     *      example="2341"
     * )
     *
     * @var string
     */
    public $soate_postal_address;
}
