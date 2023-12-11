<?php

namespace App\SwaggerAnnotations\Requests\Document;

/**
 * @OA\Schema(
 *      title="Добавление документа",
 *      type="object",
 *      required={
 *          "file",
 *          "type_of_document_id",
 *      }
 * )
 */

class DocumentStoreRequest
{
    /**
     * @OA\Property(
     *      title="Файл документа",
     *      type="file",
     *     format="pdf",
     * )
     *
     * @var file
     */
    public $file;

    /**
     * @OA\Property(
     *      title="Номер документа",
     *      example="1234567890"
     * )
     *
     * @var string
     */
    public $number;


    /**
     * @OA\Property(
     *      title="Имя документа",
     *      example="test"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Кем выдан",
     *      example="ГРС"
     * )
     *
     * @var date
     */
    public $issued_by;

    /**
     * @OA\Property(
     *      title="Когда выдан",
     *      example="2023-01-01"
     * )
     *
     * @var date
     */
    public $date_of_issued;

    /**
     * @OA\Property(
     *      title="Дата окончания действия документа",
     *      example="2023-01-01"
     * )
     *
     * @var date
     */
    public $expiry_date;

    /**
     * @OA\Property(
     *      title="id типа документа",
     *      example="1"
     * )
     *
     * @var integer
     */
    public $type_of_document_id;

    /**
     * @OA\Property(
     *      title="id обращения если этот документ принадлежит обращению.",
     *      example="1"
     * )
     *
     * @var integer
     */
    public $appeal_id;

    /**
     * @OA\Property(
     *      title="id физического лица, если документ относится к физическому лицу, если нет, то null",
     *      example="1"
     * )
     *
     * @var integer
     */
    public $applicant_individual_id;

    /**
     * @OA\Property(
     *      title="id юридического лица, если документ относится к юридическому лицу, если нет, то null",
     *      example="1"
     * )
     *
     * @var integer
     */
    public $applicant_legal_entity_id;

    /**
     * @OA\Property(
     *      title="Комментарий, примечание к документу",
     *      example="comment"
     * )
     *
     * @var string
     */
    public $comment;
}
