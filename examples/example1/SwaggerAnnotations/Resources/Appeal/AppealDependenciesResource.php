<?php

namespace App\SwaggerAnnotations\Resources\Appeal;

/**
 * @OA\Schema(
 *     title="AppealDependenciesResource",
 *     description="Необходимые данные для создания обращения",
 *     type="object",
 * )
 */
class AppealDependenciesResource
{
    /**
     * @OA\Property(
     *     title="type_of_appeals",
     *     description="Виды обращений",
     *     example="1",
     *     ref="#/components/schemas/TypeOfAppealsResource"
     * )
     *
     * @var object
     */
    private $type_of_appeals;

    /**
     * @OA\Property(
     *     title="type_of_appeals_by_count",
     *     description="Виды обращений по количеству заявителей",
     *     example="1",
     *     ref="#/components/schemas/TypeOfAppealsByCountResource"
     * )
     *
     * @var object
     */
    private $type_of_appeals_by_count;

    /**
     * @OA\Property(
     *     title="appeal_languages",
     *     description="Языки обращений",
     *     example="1",
     *     ref="#/components/schemas/AppealLanguageResource"
     * )
     *
     * @var object
     */
    private $appeal_languages;

    /**
     * @OA\Property(
     *     title="receipt_channels",
     *     description="Каналы получения",
     *     example="1",
     *     ref="#/components/schemas/ReceiptChannelsResource"
     * )
     *
     * @var object
     */
    private $receipt_channels;

    /**
     * @OA\Property(
     *     title="organization_complaints",
     *     description="Организации, которым подана жалоба",
     *     example="1",
     *     ref="#/components/schemas/OrganizationComplaintResource"
     * )
     *
     * @var object
     */
    private $organization_complaints;

    /**
     * @OA\Property(
     *     title="representative_io",
     *     description="Представители ИО",
     *     example="1",
     *     ref="#/components/schemas/RepresentativeIOResource"
     * )
     *
     * @var object
     */
    private $representative_io;

    /**
     * @OA\Property(
     *     title="degree_of_kinships",
     *     description="Степени родства",
     *     example="1",
     *     ref="#/components/schemas/DegreeOfKinshipResource"
     * )
     *
     * @var object
     */
    private $degree_of_kinships;

    /**
     * @OA\Property(
     *     title="organization_positions",
     *     description="Должности в организации",
     *     example="1",
     *     ref="#/components/schemas/OrganizationPositionResource"
     * )
     *
     * @var object
     */
    private $organization_positions;

    /**
     * @OA\Property(
     *     title="character_of_questions",
     *     description="Характер вопроса",
     *     example="1",
     *     ref="#/components/schemas/CharacterOfQuestionResource"
     * )
     *
     * @var object
     */
    private $character_of_questions;

    /**
     * @OA\Property(
     *     title="frequency_of_treatment",
     *     description="Периодичность обращения",
     *     example="1",
     *     ref="#/components/schemas/FrequencyOfAppealResource"
     * )
     *
     * @var object
     */
    private $frequency_of_treatment;

    /**
     * @OA\Property(
     *     title="citizenships",
     *     description="Гражданства",
     *     example="1",
     *     ref="#/components/schemas/CitizenshipResource"
     * )
     *
     * @var object
     */
    private $citizenships;

    /**
     * @OA\Property(
     *     title="applicant_statuses",
     *     description="Статусы заявителя. Используется чтобы в базе контролировать заявителей на бэкенде.",
     *     example="1",
     *     ref="#/components/schemas/ApplicantStatusResource"
     * )
     *
     * @var object
     */
    private $applicant_statuses;

    /**
     * @OA\Property(
     *     title="genders",
     *     description="Справочник полов",
     *     example="1",
     *     ref="#/components/schemas/GenderResource"
     * )
     *
     * @var object
     */
    private $genders;

    /**
     * @OA\Property(
     *     title="branch_of_law",
     *     description="Справочник отраслей права",
     *     example="1",
     *     ref="#/components/schemas/BranchOfLawResource"
     * )
     *
     * @var object
     */
    private $branch_of_laws;

    /**
     * @OA\Property(
     *     title="type_of_documents",
     *     description="Справочник типов документов",
     *     example="1",
     *     ref="#/components/schemas/TypeOfDocumentsResource"
     * )
     *
     * @var object
     */
    private $type_of_documents;

    /**
     * @OA\Property(
     *     title="reference_citizenship",
     *     description="Справочник гражданств",
     *     ref="#/components/schemas/CitizenshipResource"
     * )
     *
     * @var object
     */
    private $reference_citizenship;

    /**
     * @OA\Property(
     *     title="nationalities",
     *     description="Справочник Национальности",
     *     ref="#/components/schemas/NationalityResource"
     * )
     *
     * @var object
     */
    private $nationalities;

    /**
     * @OA\Property(
     *     title="family_statuses",
     *     description="Справочник семейное положение",
     *     ref="#/components/schemas/FamilyStatusResource"
     * )
     *
     * @var object
     */

    private $family_statuses;

    /**
     * @OA\Property(
     *     title="levels_of_education",
     *     description="Уровень образования",
     *     ref="#/components/schemas/LevelOfEducationResource"
     * )
     *
     * @var object
     */
    private $levels_of_education;

    /**
     * @OA\Property(
     *     title="income_levels",
     *     description="Уровень дохода",
     *     ref="#/components/schemas/IncomeLevelResource"
     * )
     *
     * @var object
     */
    private $income_levels;

    /**
     * @OA\Property(
     *     title="migration_statuses",
     *     description="Миграционный статус",
     *     ref="#/components/schemas/MigrationStatusResource"
     * )
     *
     * @var object
     */
    private $migration_statuses;

    /**
     * @OA\Property(
     *     title="purpose_of_migrant",
     *     description="Цели выезда мигранта",
     *     ref="#/components/schemas/MigrationStatusResource"
     * )
     *
     * @var object
     */
    private $purpose_of_migrant;

    /**
     * @OA\Property(
     *     title="limited_health",
     *     description="Сведения о лицах с инвалидностью",
     *     ref="#/components/schemas/LimitedHealthResource"
     * )
     *
     * @var object
     */
    private $limited_health;

    /**
     * @OA\Property(
     *     title="getting_disability",
     *     description="Получение инвалидности",
     *     ref="#/components/schemas/LimitedHealthResource"
     * )
     *
     * @var object
     */
    private $getting_disability;


    /**
     * @OA\Property(
     *     title="getting_disability",
     *     description="Заболевания",
     *     ref="#/components/schemas/SicnessResource"
     * )
     *
     * @var object
     */
    private $sickness;


    /**
     * @OA\Property(
     *     title="vulnerable_groups;",
     *     description="Уязвимые группы",
     *     ref="#/components/schemas/VulnerableGroupsResource"
     * )
     *
     * @var object
     */
    private $vulnerable_groups;

    /**
     * @OA\Property(
     *     title="groups_membership;",
     *     description="Принадлежность к группам",
     *     ref="#/components/schemas/GroupsMembersipResource"
     * )
     *
     * @var object
     */
    private $groups_membership;

    /**
     * @OA\Property(
     *     title="social_statuses;",
     *     description="Социальный статус",
     *     ref="#/components/schemas/SocialStatusResource"
     * )
     *
     * @var object
     */
    public $social_statuses;

    /**
     * @OA\Property(
     *     title="social_statuses;",
     *     description="Должности",
     *     ref="#/components/schemas/OrganizationPositionResource"
     * )
     *
     * @var object
     */
    public $organization_position;


    /**
     * @OA\Property(
     *     title="government_agency;",
     *     description="Справочник государственных органов",
     *     ref="#/components/schemas/GovernmentAgencyResource"
     * )
     *
     * @var object
     */
    public $government_agency;

    /**
     * @OA\Property(
     *     title="appeal_interests_of",
     *     description="Обращение в интересах ",
     *     ref="#/components/schemas/AppealInterestsOfResource"
     * )
     *
     * @var object
     */
    public $appeal_interests_of;


    /**
     * @OA\Property(
     *     title="soate",
     *     description="Населеные пункты ",
     *     ref="#/components/schemas/SoateResource"
     * )
     *
     * @var object
     */
    public $soate;


}
