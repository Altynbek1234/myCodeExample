<?php

namespace App\Http\Resources\Case;

use App\Http\Resources\Appeal\AppealShowResource;
use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Http\Resources\ApplicantLegalEntity\ItemALEResource;
use App\Http\Resources\OrganizationEmployeeResource;
use App\Http\Resources\References\StatusStatedFactResource;
use App\Http\Resources\Stage\StageResource;
use App\Models\CategoriesOfDepartmentRequests;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $stageHistory = $this->stageHistories()
            ->where('stage_id', 26)
            ->orderBy('id', 'DESC')
            ->first();
        return [
            'id' => $this->id,
            'appeal' => new AppealShowResource($this->appeal),
            'kind_of_case' => $this->kindOfCase,
            'applicant_legal_entity' => $this->applicantLegalEntity,
            'applicant_individual' => $this->applicantIndividual,
            'start_date' => $this->start_date,
            'status_stated_fact' => new StatusStatedFactResource($this->statusStatedFact),
            'outcome_result' => $this->outcome_result,
            'types_of_solution' => $this->typesOfSolution,
            'include_in_report' => $this->include_in_report,
            'report_text' => $this->report_text,
            'summary' => $this->summary,
            'number' => $this->number,
            'registration_date' => $stageHistory ? $stageHistory->created_at->format('Y-m-d') : null,
            'doers' => $this->getDoers(),
            'stage' => new StageResource($this->stage),
            'persons' => ItemAIResource::collection($this->persons),
            'structure_categories' => json_decode($this->structure_categories),
        ];
    }


    public function getDoers()
    {
        $doers = $this->caseDoers()->orderBy('id', 'desc')->first();
        if ($doers) {
            $doers = json_decode($doers->doers, true);
            foreach ($doers as $key => $doer) {
                $doers[$key]['user'] = User::find($doer['user_id'])->load('organizationEmployee');
            }
            return $doers;
        }

        return [];
    }
}
