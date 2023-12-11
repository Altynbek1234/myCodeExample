<?php
namespace App\Http\Resources\Case;

use App\Http\Resources\Appeal\AppealListResource;
use App\Http\Resources\ApplicantIndividual\ItemAIResource;
use App\Http\Resources\ApplicantLegalEntity\ItemALEResource;
use App\Http\Resources\EmployeeAction\EmployeeActionShowResource;
use App\Http\Resources\File\FileShowResource;
use App\Http\Resources\ProfilePersonData\ProfilePersonDataResource;
use App\Http\Resources\Stage\StageHistoryResource;
use App\Http\Resources\Stage\StageResource;
use App\Http\Resources\ViolatedRight\ViolatedRightShowResource;
use App\Models\Appeal;
use App\Models\Defendant;
use App\Models\PositionGovernmental;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class CaseShowResource extends JsonResource
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
            'appeal' => $this->appeal,
            'number' => $this->number,
            'kind_of_case' => $this->kindOfCase,
            'representative_io' => $this->representativeIo,
            'start_date' => $this->start_date,
            'summary' => $this->summary,
            'status_stated_fact' => $this->statusStatedFact,
            'outcome_result' => $this->outcome_result,
            'types_of_solution' => $this->typesOfSolution,
            'include_in_report' => $this->include_in_report,
            'report_text' => $this->report_text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'available_actions' => $this->getAvailableActions(),
            'stage_histories' => StageHistoryResource::collection($this->stageHistories),
            'stage' => new StageResource($this->stage),
            'persons' => ItemAIResource::collection($this->persons),
            'organizations' => $this->getOrganizations(),
            'documents' => FileShowResource::collection($this->documents),
            'attached_appeals' => $this->getAttachedAppeals(),
            'structure_categories' => $this->structure_categories ? json_decode($this->structure_categories) : [],
            'case_actions' => CaseActionResource::collection($this->caseActions),
            'employee_actions' => EmployeeActionShowResource::collection($this->employeeActions),
            'violated_rights' => ViolatedRightShowResource::collection($this->violatedRights),
            'lastAppealDoers' => $this->getDoers(),
            'lastAppealDate' => $this->caseDates()->orderBy('id', 'desc')->first() ?? [],
            'canAddDoers' => $this->canAddDoers(),
        ];
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

    public function getAttachedAppeals()
    {
        if (empty($this->attached_appeals)) {
            return [];
        }
        $appealsIds = json_decode($this->attached_appeals);
        $appeals = Appeal::whereIn('id', $appealsIds)->get();

        return AppealListResource::collection($appeals);
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

    public function canAddDoers()
    {
        if($this->stage->id >= 26) {
            return true;
        }

        return false;
    }
}

