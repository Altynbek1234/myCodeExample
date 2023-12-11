<?php

namespace App\Http\Resources\Appeal;

use App\Http\Resources\AppealAnswer\AppealAnswerListResource;
use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Http\Resources\Notification\NotificationTypeResource;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Http\Resources\References\ActionsOfAkyykatchyResource;
use App\Http\Resources\References\ActionToViolatorResource;
use App\Http\Resources\References\AppealInterestsOfResource;
use App\Http\Resources\References\AppealLanguageResource;
use App\Http\Resources\References\ApplicantStatMonitoringResource;
use App\Http\Resources\References\ApplicantStatusResource;
use App\Http\Resources\References\BranchOfLawResource;
use App\Http\Resources\References\CategoriesOfDepartmentRequestsResource;
use App\Http\Resources\References\CharacterOfQuestionResource;
use App\Http\Resources\References\CitizenshipResource;
use App\Http\Resources\References\DefendantResource;
use App\Http\Resources\References\DegreeOfKinshipResource;
use App\Http\Resources\References\DetectedViolationResource;
use App\Http\Resources\References\FamilyStatusResource;
use App\Http\Resources\References\FrequencyOfAppealResource;
use App\Http\Resources\References\GenderResource;
use App\Http\Resources\References\GettingDisabilityResource;
use App\Http\Resources\References\GovernmentAgencyResource;
use App\Http\Resources\References\GroupMembershipResource;
use App\Http\Resources\References\ImpactMeasuresTakenResource;
use App\Http\Resources\References\IncomeLevelResource;
use App\Http\Resources\References\InspectionResultResource;
use App\Http\Resources\References\InstitutionsForMonitoringResource;
use App\Http\Resources\References\KindOfCaseResource;
use App\Http\Resources\References\LevelOfEducationResource;
use App\Http\Resources\References\LimitedHealthResource;
use App\Http\Resources\References\MigrationStatusResource;
use App\Http\Resources\References\NationalityResource;
use App\Http\Resources\References\OrganizationalLegalFormResource;
use App\Http\Resources\References\OrganizationComplaintResource;
use App\Http\Resources\References\OrganizationPositionResource;
use App\Http\Resources\References\OutgoinglResource;
use App\Http\Resources\References\OutgoingSendingChannelResource;
use App\Http\Resources\References\PositionGovernmentalResource;
use App\Http\Resources\References\PurposeOfMigrantResource;
use App\Http\Resources\References\ReasonForRejectingResource;
use App\Http\Resources\References\ReceiptChannelResource;
use App\Http\Resources\References\RepresentativeIOResource;
use App\Http\Resources\References\SicknessResource;
use App\Http\Resources\References\SocialStatusResource;
use App\Http\Resources\References\StatusStatedFactResource;
use App\Http\Resources\References\StatusTrials;
use App\Http\Resources\References\StatusTrialsResource;
use App\Http\Resources\References\TypeContactDataResource;
use App\Http\Resources\References\TypeOfAppealByCountResource;
use App\Http\Resources\References\TypeOfAppealResource;
use App\Http\Resources\References\TypeOfCaseResource;
use App\Http\Resources\References\TypeOfDocumentResource;
use App\Http\Resources\References\TypeProceedingResource;
use App\Http\Resources\References\TypesOfInspectionResource;
use App\Http\Resources\References\TypesOfSolutionResource;
use App\Http\Resources\References\TypeSpecializedInstitutionResource;
use App\Http\Resources\References\ViolationsClassifierResource;
use App\Http\Resources\References\VulnerableGroupsResource;
use App\Models\ActionsOfAkyykatchy;
use App\Models\ActionToViolator;
use App\Models\AppealAnswer;
use App\Models\AppealInterestsOf;
use App\Models\AppealLanguage;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantStatMonitoring;
use App\Models\ApplicantStatus;
use App\Models\BranchOfLaw;
use App\Models\CategoriesOfDepartmentRequests;
use App\Models\CharacterOfQuestion;
use App\Models\Citizenship;
use App\Models\CloseReason;
use App\Models\Defendant;
use App\Models\DegreeOfKinship;
use App\Models\DetectedViolation;
use App\Models\FamilyStatus;
use App\Models\FrequencyOfAppeal;
use App\Models\Gender;
use App\Models\GettingDisability;
use App\Models\GovernmentAgency;
use App\Models\GroupMembership;
use App\Models\ImpactMeasuresTaken;
use App\Models\IncomeLevel;
use App\Models\InspectionResult;
use App\Models\InstitutionsForMonitoring;
use App\Models\KindOfCase;
use App\Models\LevelOfEducation;
use App\Models\LimitedHealth;
use App\Models\MigrationStatus;
use App\Models\Nationality;
use App\Models\NotificationType;
use App\Models\OrganizationalLegalForm;
use App\Models\OrganizationComplaint;
use App\Models\OrganizationPosition;
use App\Models\OrganizationStructure;
use App\Models\Outgoingl;
use App\Models\OutgoingSendingChannel;
use App\Models\PositionGovernmental;
use App\Models\PurposeOfMigrant;
use App\Models\ReasonForRejecting;
use App\Models\ReceiptChannel;
use App\Models\Reference;
use App\Models\RepresentativeIO;
use App\Models\Sickness;
use App\Models\Soate;
use App\Models\SocialStatus;
use App\Models\OrganizationEmployee;
use App\Models\Stage;
use App\Models\StatusStatedFact;
use App\Models\TemplatesResolutions;
use App\Models\TypeContactData;
use App\Models\TypeOfAppeal;
use App\Models\TypeOfAppealByCount;
use App\Models\TypeOfCase;
use App\Models\TypeOfDocument;
use App\Models\TypeProceeding;
use App\Models\TypesOfInspection;
use App\Models\TypesOfSolution;
use App\Models\TypeSpecializedInstitution;
use App\Models\User;
use App\Models\ViolationsClassifier;
use App\Models\VulnerableGroups;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Redis;

class AppealDependenciesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $referencesData = $this->getRefrencesData();

        return [
            'types_of_appeal' => [
                'values' => TypeOfAppealResource::collection(TypeOfAppeal::query()->where('status_id', 1)->get()),
            ],
            'types_of_appeal_by_count' => [
                'values' => TypeOfAppealByCountResource::collection(TypeOfAppealByCount::query()->where('status_id', 1)->get()),
            ],
            'receipt_channels' => [
                'values' => ReceiptChannelResource::collection(ReceiptChannel::query()->where('status_id', 1)->get()),
            ],
            'organizations_complaint' => [
                'values' => OrganizationComplaintResource::collection(OrganizationComplaint::query()->where('status_id', 1)->get()),
            ],
            'representatives_ios' => [
                'values' => RepresentativeIOResource::collection(RepresentativeIO::query()->where('status_id', 1)->get()),
            ],
            'appeal_languages' => [
                'values' => AppealLanguageResource::collection(AppealLanguage::query()->where('status_id', 1)->get()),
            ],
            'degree_of_kinships' => [
                'values' => DegreeOfKinshipResource::collection(DegreeOfKinship::query()->where('status_id', 1)->get()),
            ],
            'organization_positions' => [
                'values' => OrganizationPositionResource::collection(OrganizationPosition::query()->where('status_id', 1)->get()),
            ],
            'character_of_questions' => [
                'values' => CharacterOfQuestionResource::collection(CharacterOfQuestion::query()->where('status_id', 1)->get()),
            ],
            'frequency_of_treatment' => [
                'values' => FrequencyOfAppealResource::collection(FrequencyOfAppeal::all()),
            ],
            'citizenships' => [
                'values' => CitizenshipResource::collection(Citizenship::query()->where('status_id', 1)->get()),
            ],
            'applicant_statuses' => [
                'values' => ApplicantStatusResource::collection(ApplicantStatus::all()),
            ],
            'genders' => [
                'values' => GenderResource::collection(Gender::query()->where('status_id', 1)->get()),
            ],
            'branch_of_law' => [
                'values' => BranchOfLawResource::collection(BranchOfLaw::query()->where('status_id', 1)->get()),
            ],
            'type_of_document' => [
                'values' => TypeOfDocumentResource::collection(TypeOfDocument::query()->where('status_id', 1)->get()),
                'edit_data' => [
                    'editable' => isset($referencesData['TypeOfDocument']) ? $referencesData['TypeOfDocument']->editable : false,
                    'model' => 'TypeOfDocument',
                    'fields' => isset($referencesData['TypeOfDocument']) ? $referencesData['TypeOfDocument']->fields : [],
                ]
            ],
            'type_of_document_individual' => [
                'values' => TypeOfDocumentResource::collection(TypeOfDocument::where('type_applicant', 'LIKE' ,'%ф%')->get()),
                'edit_data' => [
                    'editable' => isset($referencesData['TypeOfDocument']) ? $referencesData['TypeOfDocument']->editable : false,
                    'model' => 'TypeOfDocument',
                    'fields' => isset($referencesData['TypeOfDocument']) ? $referencesData['TypeOfDocument']->fields : [],
                ]
            ],
            'type_of_document_legal_entity' => [
                'values' => TypeOfDocumentResource::collection(TypeOfDocument::where('type_applicant', 'LIKE' ,'%ю%')->get()),
                'edit_data' => [
                    'editable' => isset($referencesData['TypeOfDocument']) ? $referencesData['TypeOfDocument']->editable : false,
                    'model' => 'TypeOfDocument',
                    'fields' => isset($referencesData['TypeOfDocument']) ? $referencesData['TypeOfDocument']->fields : [],
                ]
            ],
            'type_contact_data' => [
                'values' => TypeContactDataResource::collection(TypeContactData::query()->where('status_id', 1)->get()),
            ],
            'nationalities' => [
                'values' => NationalityResource::collection(Nationality::query()->where('status_id', 1)->get()),
            ],
            'family_statuses' => [
                'values' => FamilyStatusResource::collection(FamilyStatus::query()->where('status_id', 1)->get()),
            ],
            'levels_of_education' => [
                'values' => LevelOfEducationResource::collection(LevelOfEducation::query()->where('status_id', 1)->get()),
            ],
            'income_levels' => [
                'values' => IncomeLevelResource::collection(IncomeLevel::query()->where('status_id', 1)->get()),
            ],
            'migration_statuses' => [
                'values' => MigrationStatusResource::collection(MigrationStatus::query()->where('status_id', 1)->get()),
            ],
            'purpose_of_migrant' => [
                'values' => PurposeOfMigrantResource::collection(PurposeOfMigrant::query()->where('status_id', 1)->get()),
            ],
            'limited_health' => [
                'values' => LimitedHealthResource::collection(LimitedHealth::query()->where('status_id', 1)->get()),
            ],
            'getting_disability' => [
                'values' => GettingDisabilityResource::collection(GettingDisability::query()->where('status_id', 1)->get()),
            ],
            'sickness' => [
                'values' => $this->getSickness(),
                'edit_data' => [
                    'editable' => isset($referencesData['Sickness']) ? $referencesData['Sickness']->editable : false,
                    'model' => 'Sickness',
                    'fields' => isset($referencesData['Sickness']) ? $referencesData['Sickness']->fields : [],
                ]
            ],
            'vulnerable_groupsResource' => [
                'values' => VulnerableGroupsResource::collection(VulnerableGroups::query()->where('status_id', 1)->get()),
            ],
            'groups_membership' => [
                'values' => GroupMembershipResource::collection(GroupMembership::query()->where('status_id', 1)->get()),
            ],
            'social_statuses' => [
                'values' => SocialStatusResource::collection(SocialStatus::query()->where('status_id', 1)->get()),
            ],
            'organization_position' => [
                'values' => OrganizationPositionResource::collection(OrganizationPosition::query()->where('status_id', 1)->get()),
            ],
            'government_agency' => [
                'values' => $this->getGovernmentAgencies(),
                'edit_data' => [
                    'editable' => isset($referencesData['GovernmentAgency']) ? $referencesData['GovernmentAgency']->editable : false,
                    'model' => 'GovernmentAgency',
                    'fields' => isset($referencesData['GovernmentAgency']) ? $referencesData['GovernmentAgency']->fields : [],
                ]
            ],
            'appeal_interests_of' => [
                'values' => AppealInterestsOfResource::collection(AppealInterestsOf::all()),
            ],
            'soate' => [
                'values' => $this->getSoates(),
            ],
            'reason_for_rejectings' => [
                'values' => ReasonForRejectingResource::collection(ReasonForRejecting::query()->where('status_id', 1)->get()),
            ],
            'employees' => [
                'values' => OrganizationEmployeeResource::collection(OrganizationEmployee::query()->where('status_id', 1)->get()),
            ],
            'templates_resolutions' => [
                'values' => TemplatesResolutions::query()->where('status_id', 1)->get(),
            ],
            'defendents' => [
                'values' => DefendantResource::collection(Defendant::all()),
            ],
            'courts' => [
                'values' => GovernmentAgencyResource::collection(GovernmentAgency::where('parent_id', 54)->get()),
            ],
            'type_proceedings' => [
                'values' => TypeProceedingResource::collection(TypeProceeding::query()->where('status_id', 1)->get()),
            ],
            'status_trials' => [
                'values' => StatusTrialsResource::collection(\App\Models\StatusTrials::query()->where('status_id', 1)->get()),
            ],
            'organization_structure' => [
                'values' => OrganizationStructure::query()->where('status_id', 1)->get(),
            ],
            'organization_complaints' => [
                'values' => OrganizationComplaint::query()->where('status_id', 1)->get(),
            ],
            'position_governmental' => [
                'values' =>  PositionGovernmentalResource::collection(PositionGovernmental::query()->where('status_id', 1)->get()),
                'edit_data' => [
                    'editable' => isset($referencesData['PositionGovernmental']) ? $referencesData['PositionGovernmental']->editable : false,
                    'model' => 'PositionGovernmental',
                    'fields' => isset($referencesData['PositionGovernmental']) ? $referencesData['PositionGovernmental']->fields : [],
                ]
            ],
            'defendent_courts' => [
                'values' => DefendantResource::collection($this->getDefendentCourts()),
            ],
            'organizational_legal_forms' => [
                'values' => OrganizationalLegalFormResource::collection(OrganizationalLegalForm::query()->where('status_id', 1)->get()),
            ],
            'organization_leaders' => [
                'values' => User::whereHas('roles', function ($q) {
                    $q->where('slug', '8');
                })->get(),
            ],
            'users' => [
                'values' => User::with('organizationEmployee')->get(),
            ],
            'applicant_stat_monitoring' => [
                'values' => ApplicantStatMonitoringResource::collection(ApplicantStatMonitoring::all()),
            ],
            'stages' => [
                'values' => Stage::query()->where('end_stage', false)->get(),
            ],
            'end_stages' => [
                'values' => Stage::query()->where('end_stage', true)->get(),
            ],
            'verbal_stages' => [
                'values' => Stage::query()->where('appeal_type_id', 2)->get(),
            ],
            'outgoing_doc_type' => [
                'values' => OutgoinglResource::collection(Outgoingl::query()->where('status_id', 1)->get()),
            ],
            'outgoing_sending_channels' => [
                'values' => OutgoingSendingChannelResource::collection(OutgoingSendingChannel::query()->where('status_id', 1)->get()),
            ],
            'categories_of_department_requests' => [
                'values' => CategoriesOfDepartmentRequestsResource::collection(CategoriesOfDepartmentRequests::where('status_id', 1)->get()),
                'edit_data' => [
                    'editable' => isset($referencesData['CategoriesOfDepartmentRequests']) ? $referencesData['CategoriesOfDepartmentRequests']->editable : false,
                    'model' => 'CategoriesOfDepartmentRequests',
                    'fields' => isset($referencesData['CategoriesOfDepartmentRequests']) ? $referencesData['CategoriesOfDepartmentRequests']->fields : [],
                ]
            ],
            'type_specialized_institutions' => [
                'values' => TypeSpecializedInstitutionResource::collection(TypeSpecializedInstitution::query()->where('status_id', 1)->get()),
                'edit_data' => [
                    'editable' => isset($referencesData['TypeSpecializedInstitution']) ? $referencesData['TypeSpecializedInstitution']->editable : false,
                    'model' => 'TypeSpecializedInstitution',
                    'fields' => isset($referencesData['TypeSpecializedInstitution']) ? $referencesData['TypeSpecializedInstitution']->fields : [],
                ]
            ],
            'type_of_case' => [
                'values' => TypeOfCaseResource::collection(TypeOfCase::query()->where('status_id', 1)->get()),
            ],
            'kind_of_case' => [
                'values' => KindOfCaseResource::collection(KindOfCase::query()->where('status_id', 1)->get()),
            ],
            'status_stated_facts' => [
                'values' => StatusStatedFactResource::collection(StatusStatedFact::query()->where('status_id', 1)->get()),
            ],
            'types_of_solutions' => [
                'values' => TypesOfSolutionResource::collection(TypesOfSolution::query()->where('status_id', 1)->get()),
            ],
            'ombudsman_actions' => [
                'values' => ActionsOfAkyykatchyResource::collection(ActionsOfAkyykatchy::query()->where('status_id', 1)->get()),
            ],
            'violations_classifier' => [
                'values' => $this->getViolationsClassifier(),
            ],
            'actions_to_violator' => [
                'values' => ActionToViolatorResource::collection(ActionToViolator::query()->where('status_id', 1)->get()),
            ],
            'appeal_answers' => [
                'values' => AppealAnswerListResource::collection(AppealAnswer::all()),
            ],
            'types_of_inspections' => [
                'values' => TypesOfInspectionResource::collection(TypesOfInspection::query()->where('status_id', 1)->get()),
            ],
            'institutions_for_monitorings' => [
                'values' => InstitutionsForMonitoringResource::collection(InstitutionsForMonitoring::query()->where('status_id', 1)->get()),
            ],
            'inspection_results' => [
                'values' => InspectionResultResource::collection(InspectionResult::query()->where('status_id', 1)->get()),
            ],
            'detected_violations' => [
                'values' => DetectedViolationResource::collection(DetectedViolation::query()->where('status_id', 1)->get()),
                'edit_data' => [
                    'editable' => isset($referencesData['DetectedViolation']) ? $referencesData['DetectedViolation']->editable : false,
                    'model' => 'DetectedViolation',
                    'fields' => isset($referencesData['DetectedViolation']) ? $referencesData['DetectedViolation']->fields : [],
                ]
            ],
            'close_reasons' => [
                'values' => CloseReason::all(),
            ],
            'impact_measures_taken' => [
                'values' => ImpactMeasuresTakenResource::collection(ImpactMeasuresTaken::query()->where('status_id', 1)->get()),
            ],
            'notification_types' => [
                'values' => NotificationTypeResource::collection(NotificationType::all()),
            ],
        ];
    }

    public function getSoates()
    {
        if(Redis::exists('soates')) {
            $soates = json_decode(Redis::get('soates'), true);
        } else {
            $soates = Soate::getSoates();
            Redis::set('soates', json_encode($soates));
        }

        return $soates;
    }

    public function getGovernmentAgencies()
    {
        $governmentAgencies = GovernmentAgency::with('children')->whereNull('parent_id')->get();
        return GovernmentAgencyResource::collection($governmentAgencies);
    }

    public function getViolationsClassifier()
    {
        if (Redis::exists('violations_classifier')) {
            $violationsClassifier = json_decode(Redis::get('violations_classifier'), true);
        } else {
            $violationsClassifier = ViolationsClassifier::with('children')->whereNull('parent_id')->get();
            $violationsClassifier = ViolationsClassifierResource::collection($violationsClassifier);
            Redis::set('violations_classifier', json_encode($violationsClassifier));
        }

        return $violationsClassifier;
    }

    public function getDefendentCourts()
    {
        $courtIds = GovernmentAgency::where('parent_id', 54)->get()->pluck('id');
        $defendents = Defendant::whereIn('government_agency_id', $courtIds)->get();

        return $defendents;
    }

    public function getRefrencesData()
    {
        $referencesData = Reference::all();
        $data = [];
        foreach ($referencesData as $reference) {
            $data[$reference->model] = $reference;
        }

        return $data;
    }

    public function getDepartmentCategories()
    {
        $categories = CategoriesOfDepartmentRequests::where('status_id', 1)->get();
        $result = [];
        foreach ($categories as $category) {
            if($category->parent_id) {
                foreach($result as $key => $value) {
                    if($key === $category->parent_id) {
                        $result[$key]['children'][$category->id] = $category->toArray();
                        break;
                    }
                    if (!isset($value['children'])) {
                        continue;
                    }
                    foreach($value['children'] as $key2 => $subvalue) {
                        if($key2 === $category->parent_id) {
                            $result[$key]['children'][$key2]['children'][$category->id] = $category->toArray();
                            break 2;
                        }
                    }
                }
            } else {
                $result[$category->id] = $category->toArray();
            }
        }

        return $result;
    }

    public function getSickness()
    {
        $categories = Sickness::where('status_id', 1)->get();
        $result = [];
        foreach ($categories as $category) {
            if($category->parent_id) {
                foreach($result as $key => $value) {
                    if($key === $category->parent_id) {
                        $data = $category->toArray();
                        if ($category->code) {
                            $data['name_ru'] = $category->name_ru . ' (' . $category->code . ')';
                            $data['name_kg'] = $category->name_ru . ' (' . $category->code . ')';
                        }
                        $result[$key]['children'][$category->id] = $data;
                        break;
                    }
                    if (!isset($value['children'])) {
                        continue;
                    }
                    foreach($value['children'] as $key2 => $subvalue) {
                        if($key2 === $category->parent_id) {
                            $data = $category->toArray();
                            if ($category->code) {
                                $data['name_ru'] = $category->name_ru . ' (' . $category->code . ')';
                                $data['name_kg'] = $category->name_ru . ' (' . $category->code . ')';
                            }
                            $result[$key]['children'][$key2]['children'][$category->id] = $data;
                            break 2;
                        }
                    }
                }
            } else {
                $result[$category->id] = $category->toArray();
            }
        }

        return $result;
    }
}
