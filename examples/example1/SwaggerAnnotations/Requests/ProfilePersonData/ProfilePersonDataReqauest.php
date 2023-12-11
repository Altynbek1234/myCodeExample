<?php

namespace App\SwaggerAnnotations\Requests\ProfilePersonData;
/**
 * @OA\Schema(
 *      title="Данные  профиля ",
 *      description="Данные  профиля ",
 *      type="object",
 *      required={
 *          "a_individual_id",
 *      }
 * )
 */
class ProfilePersonDataReqauest
{
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
     *      title="age",
     *      description="Возраст ",
     *      format="int64",
     *      example=23
     * )
     *
     * @var integer
     */
    public $age;

    /**
     * @OA\Property(
     *      title="nationality",
     *      description="Национальнось из справочника",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $nationality;



    /**
     * @OA\Property(
     *      title="social_status",
     *      description="Социалный статус из справочника",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $social_status;



    /**
     * @OA\Property(
     *      title="social_status",
     *      description="Cемейное положение из справочника",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $family_status;


    /**
     * @OA\Property(
     *      title="level_of_education",
     *      description="Уровень образования из справочника",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $level_of_education;



    /**
     * @OA\Property(
     *      title="income_levels",
     *      description="Уровень дохода из справочника",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $income_levels;



    /**
     * @OA\Property(
     *      title="migration_status",
     *      description="Миграционный статус",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $migration_status;



    /**
     * @OA\Property(
     *      title="purpose_of_migrant",
     *      description="Цель выезда",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $purpose_of_migrant;



    /**
     * @OA\Property(
     *      title="getting_disabilities",
     *      description="Сведения об инволидности",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $getting_disabilities;


    /**
     * @OA\Property(
     *      title="limited_health",
     *      description="Вид инволидности",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $limited_health;



    /**
     * @OA\Property(
     *      title="sickness",
     *      description="Заболевания",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $sickness;

    /**
     * @OA\Property(
     *      title="registered_psychiatric",
     *      description="Состоит на учите в псих диспансире",
     *      type="string",
     *      example=80001
     * )
     *
     * @var string
     */
    public $registered_psychiatric;


    /**
     * @OA\Property(
     *      title="criminal_record",
     *      description="Наличие судимости",
     *      type="string",
     *      example=80000
     * )
     *
     * @var string
     */
    public $criminal_record;


    /**
     * @OA\Property(
     *      title="vulnerable_group",
     *      description="Принадлежность к укг",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $vulnerable_group;


    /**
     * @OA\Property(
     *      title="group_membership",
     *      description="Принадлежность к группам",
     *      format="int64",
     *      example=12
     * )
     *
     * @var integer
     */
    public $group_membership;


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
