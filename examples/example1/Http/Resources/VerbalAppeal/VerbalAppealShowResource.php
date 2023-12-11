<?php

namespace App\Http\Resources\VerbalAppeal;

use App\Http\Resources\Appeal\CharacterOfQuestionResource;
use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Http\Resources\ApplicantLegalEntity\ItemALEResource;
use App\Http\Resources\Document\DocumentShowResource;
use App\Http\Resources\File\FileShowResource;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Http\Resources\PersonsInterest\PersonsInterestResource;
use App\Http\Resources\ProfilePersonData\ProfilePersonDataResource;
use App\Http\Resources\References\AppealInterestsOfResource;
use App\Http\Resources\References\AppealLanguageResource;
use App\Http\Resources\References\CategoryAppealResource;
use App\Http\Resources\References\DegreeOfKinshipResource;
use App\Http\Resources\References\FrequencyOfAppealResource;
use App\Http\Resources\References\OrganizationComplaintResource;
use App\Http\Resources\References\OrganizationPositionResource;
use App\Http\Resources\References\ReceiptChannelResource;
use App\Http\Resources\References\RepresentativeIOResource;
use App\Http\Resources\References\TypeOfAppealByCountResource;
use App\Http\Resources\References\TypeOfAppealResource;
use App\Http\Resources\Stage\StageHistoryResource;
use App\Http\Resources\Stage\StageResource;
use App\Models\Appeal;
use App\Models\AppealInterestsOf;
use App\Models\ApplicantIndividual;
use App\Models\ApplicantLegalEntity;
use App\Models\DegreeOfKinship;
use App\Models\ProfilePersonData;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class VerbalAppealShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'appeal_language' => new AppealLanguageResource($this->typeOfAppealLanguage),
            'description' => $this->description,
            'count_of_pages' => $this->count_of_pages,
            'date_of_appeal' => $this->date_of_appeal,
            'number' => $this->number,
            'ish_number' => $this->ish_number,
            'ish_date' => $this->ish_date,
            'applicant_individuals' => ItemAIResource::collection($this->applicantIndividuals),
            'applicant_legal_entities' => ItemALEResource::collection($this->applicantLegalEntities),
            'person_interests' => PersonsInterestResource::collection($this->personInterests),
            'character_of_questions' => CharacterOfQuestionResource::collection($this->appealCharacterOfQuestions),
            'available_actions' => $this->getAvailableActions(),
            'stage_histories' => StageHistoryResource::collection($this->stageHistories),
            'consultation_has' => $this->consultation_has,
            'employee' => new OrganizationEmployeeResource($this->employee),
            'written_appeal_has' => $this->written_appeal_has,
            'appeal_id' => $this->appeal_id,
            'appeal' => Appeal::find($this->appeal_id),
            'applicants' => $this->getApplicants(),
            'stage' => new StageResource($this->stage),
            'type_of_appeal_count' => new TypeOfAppealByCountResource($this->typeOfAppealByCount),
        ];
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
                        $personFromDB->degree = !empty($person['degree_id']) ? new DegreeOfKinshipResource(DegreeOfKinship::find($person['degree_id'])) : null;
                        $personFromDB->profile = new ProfilePersonDataResource(ProfilePersonData::find($profileId));
                        $personList[] = $personFromDB;
                    } else {
                        $personFromDB = ApplicantLegalEntity::find($person['id']);
                        $personFromDB->type = 'legal_entity';
                        $personList[] = $personFromDB;
                    }
                }
                $applicant->personList = $personList;
                $applicants[] = $applicant;
            }
        }

        return $applicants;
    }
}
