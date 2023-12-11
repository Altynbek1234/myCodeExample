<?php

namespace App\SwaggerAnnotations\Resources\Document;

/**
 * @OA\Schema(
 *     title="Документ",
 *     description="Документ",
 *     @OA\Xml(
 *         name="FileShowResource"
 *     )
 * )
 */
class DocumentShowResource
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    public $id;

    /**
     * @OA\Property(
     *      title="Номер",
     *      description="Номер",
     *      type="integer",
     *      example="1234"
     * )
     *
     * @var integer
     */
    public $number;

    /**
     * @OA\Property(
     *      title="Кем выдан",
     *      description="Кем выдан",
     *      example="Кем выдан"
     * )
     *
     * @var string
     */
    public $issued_by;

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
     *      title="Дата выдачи",
     *      description="Дата выдачи",
     *      example="2020-01-01"
     * )
     *
     * @var string
     */
    public $date_of_issued;

    /**
     * @OA\Property(
     *      title="Срок действия",
     *      description="Срок действия",
     *      example="2020-01-01"
     * )
     *
     * @var string
     */
    public $expiry_date;

    /**
     * @OA\Property(
     *      title="Тип документа",
     *      description="Тип документа",
     *      ref="#/components/schemas/TypeOfDocumentsResource"
     * )
     *
     * @var object
     */
    public $type_of_document;

    /**
     * @OA\Property(
     *      title="Комментарий",
     *      description="Комментарий",
     *      example="Комментарий"
     * )
     *
     * @var object
     */
    public $comment;

    /**
     * @OA\Property(
     *      title="Физическое лицо",
     *      description="Физическое лицо",
     *      ref="#/components/schemas/AIlResource"
     * )
     *
     * @var object
     */
    public $applicant_individual;

    /**
     * @OA\Property(
     *      title="Юридическое лицо",
     *      description="Юридическое лицо",
     *      ref="#/components/schemas/ALEResource"
     * )
     *
     * @var object
     */
    public $applicant_legal_entity;

    /**
     * @OA\Property(
     *      title="Создано",
     *      description="Создано",
     *      example="2020-01-01 00:00:00"
     * )
     *
     * @var string
     */
    public $created_at;

    /**
     * @OA\Property(
     *      title="Обновлено",
     *      description="Обновлено",
     *      example="2020-01-01 00:00:00"
     * )
     *
     * @var string
     */
    public $updated_at;

    /**
     * @OA\Property(
     *      title="Ссылка на публичный доступ. Это поле необходимо для отображения ссылки на документ в публичной части сайта.",
     *      description="Ссылка",
     *      example="http://127.0.0.1:8080/s/pNpRwptSdc6c9tJ"
     * )
     *
     * @var string
     */
    public $file;
}
