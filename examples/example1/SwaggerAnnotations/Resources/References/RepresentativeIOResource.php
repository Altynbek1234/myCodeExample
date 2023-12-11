<?php

namespace App\SwaggerAnnotations\Resources\References;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

// use App\Http\Resources\References\RepresentativeIOResource;

/**
 * @OA\Schema(
 *     title="RepresentativeIOResource",
 *     description="RepresentativeIOResource",
 *     @OA\Xml(
 *         name="RepresentativeIOResource"
 *     )
 * )
 */
class RepresentativeIOResource
{
    /**
     * @OA\Property(
     *     title="name_ru",
     *     description="name_ru",
     *     format="string",
     *     example="name_ru"
     * )
     *
     * @var string
     */
    private $name_ru;

    /**
     * @OA\Property(
     *     title="name_kg",
     *     description="name_kg",
     *     format="string",
     *     example="name_kg"
     * )
     *
     * @var string
     */
    private $name_kg;

    /**
     * @OA\Property(
     *     title="adress",
     *     description="adress",
     *     format="string",
     *     example="adress"
     * )
     *
     * @var string
     */
    private $adress;

    /**
     * @OA\Property(
     *     title="phone",
     *     description="phone",
     *     format="string",
     *     example="phone"
     * )
     *
     * @var string
     */
    private $phone;

    /**
     * @OA\Property(
     *     title="fax",
     *     description="fax",
     *     format="string",
     *     example="fax"
     * )
     *
     * @var string
     */
    private $fax;

    /**
     * @OA\Property(
     *     title="email",
     *     description="email",
     *     format="string",
     *     example="email"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="code",
     *     description="code",
     *     format="string",
     *     example="code"
     * )
     *
     * @var string
     */
    private $code;

    /**
     * @OA\Property(
     *     title="status_id",
     *     description="status_id",
     *     format="integer",
     *     example="status_id"
     * )
     *
     * @var integer
     */
    private $status_id;

}
