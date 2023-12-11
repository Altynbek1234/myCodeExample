<?php

namespace App\Http\Resources\Appeal;

use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Http\Resources\ApplicantLegalEntity\ItemALEResource;
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
use App\Models\Appeal;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AppealListResource extends JsonResource
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
            'description' => $this->description,
            'number' => $this->number,
            'type_of_appeal' => new TypeOfAppealResource($this->typeOfAppeal),
            'receipt_channel' => new ReceiptChannelResource($this->receiptChannel),
            'applicants' => $this->getApplicants(),
            'personList' => $this->personList(),
            'status' => new StageResource($this->stage),
            'executors' => $this->getDoers(),
            'structure_categories' => $this->structure_categories ? json_decode($this->structure_categories) : [],
            'read' => $this->isRead($this->read),
            'favorite' => $this->favorite ?: [],
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

    public function getDoers(){
        $doers = $this->appealDoers()->orderBy('id', 'desc')->first();
        if ($doers) {
            $doers = json_decode($doers->doers, true);
            $executors = [];
            foreach ($doers as $key => $doer) {
                $user = User::find($doer['user_id'])->load('organizationEmployee');
                $executors[] = $user;
            }
            return $executors;
        }
        return [];
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
