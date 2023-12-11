<?php

namespace App\SwaggerAnnotations\Requests\CharacterOfQuestions;

use App\SwaggerAnnotations\Requests\Appeal\date;

/**
 * @OA\Schema(
 *     title="Данные для связывания характер вопроса с обращением",
 *     description="Необходимо передать id характера вопроса, is_main, id судебного процесса
 *     или если нет данных судебного процесса, то передать данные судебного процесса.
 *     id или данные судебного процесса необходимы только если выбран характер вопроса предпологающий участие в судебном процессе",
 *     type="object",
 * )
 */
class PivoteTable
{
    /**
     * @OA\Property(
     *     title="id характера вопроса",
     *     example="1"
     * )
     *
     * @var int
     */
    private $character_of_question_id;

    /**
     * @OA\Property(
     *     title="true - если характер вопроса является основным, false - если характер вопроса не является основным",
     *     description="Характер вопроса может быть только один основной",
     *     example="true"
     * )
     *
     * @var bool
     */
    private $is_main;

    /**
     * @OA\Property(
     *     title="Данные судебного процесса",
     *     description="Если данных судебного процесса нет в базе чтобы передать id, то необходимо передать данные судебного процесса",
     *     ref="#/components/schemas/CourtStoreRequest"
     * )
     *
     * @var int
     */
    private $court;
}
