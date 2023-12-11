<?php

namespace App\SwaggerAnnotations\Requests\ApplicantIndividual;
/**
 * @OA\Schema(
 *      title="Адрес и дополнительные данные физ лица.",
 *      description="Адрес и дополнительные данные физ лица.",
 *      type="object",
 * )
 */
class ApplicantIndividualAddressRequest
{
    /**
     * @OA\Property(
     *      title="место работы или учебы",
     *      example="ООС РЕАЛ БРИК"
     * )
     *
     * @var string
     */
    public $place_work_study;

    /**
     * @OA\Property(
     *      title="позиция/должность",
     *      example="менаджер"
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
     *      title="почтовый адрес (фактический адрес)",
     *      example="ул Токтогула 41/3"
     * )
     *
     * @var string
     */
    public $postal_address;

    /**
     * @OA\Property(
     *      title="ID soate для адреса регистрации",
     *      example="1121"
     * )
     *
     * @var string
     */
    public $soate_registration_address;

    /**
     * @OA\Property(
     *      title="ID soate для почтового адреса (фактического адреса)",
     *      example="2023"
     * )
     *
     * @var string
     */
    public $soate_postal_address;
}
