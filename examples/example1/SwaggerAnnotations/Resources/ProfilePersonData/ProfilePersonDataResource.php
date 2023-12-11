<?php

namespace App\SwaggerAnnotations\Resources\ProfilePersonData;
use App\SwaggerAnnotations\Resources\ApplicantIndividual\DateTime;

/**
 * @OA\Schema(
 *     title="Данные  профиля ",
 *     description="Данные  профиля ",
 *     @OA\Xml(
 *         name="ProfilePersonDataResource"
 *     )
 * )
 */
class ProfilePersonDataResource
{
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
     *      title="appeal_id",
     *      description="id заявления",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    public $appeal_id;

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
     *      title="other_nationality",
     *      example="другая национальность"
     * )
     *
     * @var string
     */
    public $other_nationality;

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
     *      title="other_social_status",
     *      example="другой социалный статус"
     * )
     *
     * @var string
     */
    public $other_social_status;

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
     *      title="other_family_status",
     *      example="другой социалный статус"
     * )
     *
     * @var string
     */
    public $other_family_status;

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
     *      title="other_family_status",
     *      example="другой уровень образования"
     * )
     *
     * @var string
     */
    public $other_level_of_education;


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
     *      title="other_income_level",
     *      example="другой уровень дохода"
     * )
     *
     * @var string
     */
    public $other_income_level;

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
     *      title="other_migration_status",
     *      example="другой миграционный статус"
     * )
     *
     * @var string
     */
    public $other_migration_status;

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
     *      title="other_purpose_of_migrant",
     *      example="другая цель выезда"
     * )
     *
     * @var string
     */
    public $other_purpose_of_migrant;

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
     *      title="other_getting_disabilities",
     *      example="другие сведения об инволидности"
     * )
     *
     * @var string
     */
    public $other_getting_disabilities;

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
     *      title="other_getting_disabilities",
     *      example="другие виды инволидности"
      * )
     *
     * @var string
     */
    public $other_limited_healths;

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
     *      title="other_sickness",
     *      example="другие заболевания"
     * )
     *
     * @var string
     */
    public $other_sickness;

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
     *      title="date_registered_psychiatric",
     *      description="дата регистрации в псих диспансире ",
     *      example="1998-10-23",
     *     format="datetime",
     *     type="string"
     * )
     *
     * @var DateTime
     */
    public $date_registered_psychiatric;

    /**
     * @OA\Property(
     *      title="other_registered_psychiatric",
     *      example="другие данные "
     * )
     *
     * @var string
     */
    public $other_registered_psychiatric;

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
     *      title="$other_criminal_record",
     *      example="другие данные  "
     * )
     *
     * @var string
     */
    public $other_criminal_record;

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
     *      title="other_vulnerable_group",
     *      example="другие данные принадлежность к укг"
     * )
     *
     * @var string
     */
    public $other_vulnerable_group;

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
     *      title="other_group_memberships",
     *      example="другие данные принадлежность  к группам"
     * )
     *
     * @var string
     */
    public $other_group_memberships;
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
