<?php

namespace App\Http\Resources\VerbalAppeal;

use App\Http\Resources\Appeal\CharacterOfQuestionResource;
use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Http\Resources\ApplicantLegalEntity\ItemALEResource;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Http\Resources\PersonsInterest\PersonsInterestResource;
use App\Http\Resources\References\AppealLanguageResource;
use App\Http\Resources\References\CategoryAppealResource;
use App\Http\Resources\References\FrequencyOfAppealResource;
use App\Http\Resources\References\OrganizationComplaintResource;
use App\Http\Resources\References\OrganizationPositionResource;
use App\Http\Resources\References\ReceiptChannelResource;
use App\Http\Resources\References\RepresentativeIOResource;
use App\Http\Resources\References\TypeOfAppealByCountResource;
use App\Http\Resources\References\TypeOfAppealResource;
use App\Http\Resources\Stage\StageResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VerbalAppealListResource extends JsonResource
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
            'date_of_appeal' => $this->date_of_appeal,
            'number' => $this->number,
            'applicants' => $this->getApplicants(),
            'personList' => $this->personList(),
            'character_of_questions' => CharacterOfQuestionResource::collection($this->appealCharacterOfQuestions),
            'available_actions' => $this->getAvailableActions(),
            'consultation_has' => $this->consultation_has,
            'employee' => new OrganizationEmployeeResource($this->employee),
            'written_appeal_has' => $this->written_appeal_has,
            'executor' => [],
        ];
    }

    public function getApplicants()
    {
        $applicants = [];
        foreach ($this->applicantLegalEntities as $key => $applicant) {
            $applicants[$key]['id'] = $applicant->id;
            $applicants[$key]['name_ru'] = $applicant->name;
            $applicants[$key]['name_kg'] = $applicant->name_kg;
            $applicants[$key]['type'] = $applicant->type;
        }
        foreach ($this->applicantIndividuals as $key => $applicant) {
            $applicants[$key]['id'] = $applicant->id;
            $applicants[$key]['name'] = $applicant->personInfo->last_name . ' ' . $applicant->personInfo->name . ' ' . $applicant->personInfo->patronymic;
            $applicants[$key]['type'] = $applicant->type;
        }

        return $applicants;
    }

    public function personList()
    {
        $personList = [];
        foreach ($this->personInterests as $key => $personInterest) {
            $personList[$key]['id'] = $personInterest->personInfo->id;
            $personList[$key]['name'] = $personInterest->personInfo->last_name . ' ' . $personInterest->personInfo->name . ' ' . $personInterest->personInfo->patronymic;
            $personList[$key]['type'] = $personInterest->type;
        }
        foreach ($this->personInterestsLegal as $key => $personInterest) {
            $personList[$key]['id'] = $personInterest->id;
            $personList[$key]['name'] = $personInterest->name;
            $personList[$key]['name_kg'] = $personInterest->name_kg;
            $personList[$key]['type'] = $personInterest->type;
        }

        return $personList;
    }
}
