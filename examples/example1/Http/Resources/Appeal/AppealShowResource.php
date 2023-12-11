<?php

namespace App\Http\Resources\Appeal;

use App\Http\Resources\Case\CaseActionResource;
use App\Http\Resources\Document\DocumentShowResource;
use App\Http\Resources\EmployeeAction\EmployeeActionShowResource;
use App\Http\Resources\File\FileShowResource;
use App\Http\Resources\ProfilePersonData\ProfilePersonDataResource;
use App\Http\Resources\References\AppealInterestsOfResource;
use App\Http\Resources\References\AppealLanguageResource;
use App\Http\Resources\References\CategoryAppealResource;
use App\Http\Resources\References\DegreeOfKinshipResource;
use App\Http\Resources\References\FrequencyOfAppealResource;
use App\Http\Resources\References\OrganizationPositionResource;
use App\Http\Resources\References\ReceiptChannelResource;
use App\Http\Resources\References\RepresentativeIOResource;
use App\Http\Resources\References\TypeOfAppealByCountResource;
use App\Http\Resources\References\TypeOfAppealResource;
use App\Http\Resources\Stage\StageHistoryResource;
use App\Http\Resources\Stage\StageResource;
use App\Http\Resources\ViolatedRight\ViolatedRightShowResource;
use App\Models\Appeal;
use App\Models\AppealInterestsOf;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantLegalEntity;
use App\Models\Defendant;
use App\Models\DegreeOfKinship;
use App\Models\PositionGovernmental;
use App\Models\ProfilePersonData;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AppealShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_of_appeal' => new TypeOfAppealResource($this->typeOfAppeal),
            'type_of_appeal_count' => new TypeOfAppealByCountResource($this->typeOfAppealByCount),
            'appeal_language' => new AppealLanguageResource($this->typeOfAppealLanguage),
            'frequenciesy_of_appeal' => new FrequencyOfAppealResource($this->frequencyOfAppeal),
            'organization_position' => new OrganizationPositionResource($this->organizationPosition),
            'receipt_channel' => new ReceiptChannelResource($this->receiptChannel),
            'representative_io' => new RepresentativeIOResource($this->representativeIo),
            'stage' => new StageResource($this->stage),
            'description' => $this->description,
            'count_of_pages' => $this->count_of_pages,
            'date_of_appeal' => $this->date_of_appeal,
            'number' => $this->number,
            'ish_number' => $this->ish_number,
            'ish_date' => $this->ish_date,
            'comment' => $this->comment,
            'applicants' => $this->getApplicants(),
            'organizations' => $this->getOrganizations(),
            'character_of_questions' => CharacterOfQuestionResource::collection($this->appealCharacterOfQuestions),
            'category_appeals' => CategoryAppealResource::collection($this->categoriesOfAppeal),
            'available_actions' => $this->case ? $this->case->getAvailableActions() : $this->getAvailableActions(),
            'documents' => FileShowResource::collection($this->documents),
            'stage_histories' => StageHistoryResource::collection($this->stageHistories),
            'resultStage' => $this->resultStage,
            'lastAppealDoers' => $this->getDoers(),
            'lastAppealDate' => $this->appealDates()->orderBy('id', 'desc')->first() ?? [],
            'canAddDoers' => $this->canAddDoers(),
            'attached_appeals' => $this->getAttachedAppeals(),
            'structure_categories' => $this->structure_categories ? json_decode($this->structure_categories) : [],
            'employee_actions' => EmployeeActionShowResource::collection($this->employeeActions),
            'violated_rights' => ViolatedRightShowResource::collection($this->violatedRights),
            'case' =>  $this->case,
            'appeal_actions' => CaseActionResource::collection($this->appealActions),
            'in_control' => $this->in_control,
            'organization_structure_id' => $this->organization_structure_id,
            'read' => $this->isRead($this->read),
            'favorite' => $this->favorite ?: []
        ];
    }

    public function getAttachedAppeals()
    {
        if (empty($this->attached_appeals)) {
            return [];
        }
        $appealsIds = json_decode($this->attached_appeals);
        $appeals = Appeal::whereIn('id', $appealsIds)->get();

        return AppealListResource::collection($appeals);
    }

    public function getApplicants()
    {
        $applicants = [];
        $applicantsFromAppeal = json_decode($this->applicants, true);
        if ($applicantsFromAppeal) {
            foreach ($applicantsFromAppeal as $applicantFromAppeal) {
                if (empty($applicantFromAppeal['id'])) {
                    continue;
                }
                if ($applicantFromAppeal && $applicantFromAppeal['type'] === 'individual') {
                    $applicant = ApplicantIndividual::find($applicantFromAppeal['id']);
                    $applicant->load('personInfo');
                    $applicant->load('status');
                    $applicant->load('gender');
                    $applicant->load('citizenship');
                    $applicant->type = 'individual';
                } else {
                    $applicant = ApplicantLegalEntity::find($applicantFromAppeal['id']);
                    $applicant->type = 'legal_entity';
                }
                $applicant->number = $applicant->getNumber();
                $applicant->documents = DocumentShowResource::collection($applicant->documents);
                $applicant->appeal_interests_of = new AppealInterestsOfResource(AppealInterestsOf::find($applicantFromAppeal['appeal_interests_of']));
                $personList = [];
                foreach ($applicantFromAppeal['personList'] as $person) {
                    if ($person['type'] === 'individual') {
                        $profileId = null;
                        foreach ($this->personInterests as $personInterest) {
                            if ($personInterest->id == $person['id']) {
                                $profileId = $personInterest->pivot->profile_id;
                            }
                        }
                        $personFromDB = ApplicantIndividual::find($person['id']);
                        $personFromDB->degree_id = $person['degree_id'];
                        $personFromDB->load('personInfo');
                        $personFromDB->load('status');
                        $personFromDB->load('gender');
                        $personFromDB->load('citizenship');
                        $personFromDB->type = 'individual';
                        $personFromDB->documents = DocumentShowResource::collection($personFromDB->documents);
                        $personFromDB->degree = !empty($person['degree_id']) ? new DegreeOfKinshipResource(DegreeOfKinship::find($person['degree_id'])) : null;
                        $personFromDB->profile = $profileId ? new ProfilePersonDataResource(ProfilePersonData::find($profileId)) : null;
                        $personFromDB->attorney = $person['attorney'] ?? null;
                        $personFromDB->attorney_date = $person['attorney_date'] ?? null;
                        $personList[] = $personFromDB;
                    } else {
                        $personFromDB = ApplicantLegalEntity::find($person['id']);
                        $personFromDB->type = 'legal_entity';
                        $personFromDB->degree = !empty($person['degree_id']) ? new DegreeOfKinshipResource(DegreeOfKinship::find($person['degree_id'])) : null;
                        $personFromDB->documents = DocumentShowResource::collection($personFromDB->documents);
                        $personFromDB->attorney = $person['attorney'] ?? null;
                        $personFromDB->attorney_date = $person['attorney_date'] ?? null;
                        $personList[] = $personFromDB;
                    }
                }
                $applicant->personList = $personList;
                $applicants[] = $applicant;
            }
        }

        return $applicants;
    }

    public function getOrganizations()
    {
        $organizations = [];
        foreach($this->organizations as $organization)
        {
            $defendentList = [];
            $defendentIds = json_decode($organization->pivot->defendent_ids, true) ?? [];
            foreach ($defendentIds as $defendentId) {
                $defendent = Defendant::find($defendentId['id']);
                if (!$defendent) {
                    continue;
                }
                $defendent->load('gender');
                $defendent->position = !empty($defendentId['position']) ? $defendentId['position'] : null;
                $defendent->positionData = !empty($defendentId['position']) ? PositionGovernmental::find($defendentId['position']) : null;
                $defendentList[] = $defendent;
            }
            $organization->defendentList = $defendentList;
            $organizations[] = $organization;
        }

        return $organizations;
    }

    public function getDoers()
    {
        $doers = $this->appealDoers()->orderBy('id', 'desc')->first();
        if ($doers) {
            $doers = json_decode($doers->doers, true);
            foreach ($doers as $key => $doer) {
                $doers[$key]['user'] = User::find($doer['user_id'])->load('organizationEmployee');
            }
            return $doers;
        }

        return [];
    }

    public function canAddDoers()
    {
        if($this->stage->id >= 16) {
            return true;
        }

        return false;
    }

    public function isRead($read)
    {
        if (!empty($read)) {
            $userId = Auth::id();
            foreach ($read as $item) {
                if (in_array($userId, $item)) {
                    return true;
                }
            }
        }
        return false;
    }
}
