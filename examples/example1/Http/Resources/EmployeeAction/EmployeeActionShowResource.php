<?php
namespace App\Http\Resources\EmployeeAction;

use App\Http\Resources\AppealAnswer\AppealAnswerShowResource;
use App\Http\Resources\Case\CaseShowResource;
use App\Http\Resources\References\ActionToViolatorResource;
use App\Http\Resources\References\DefendantResource;
use App\Http\Resources\References\GovernmentAgencyResource;
use App\Http\Resources\References\OrganizationPositionResource;
use App\Models\Defendant;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeActionShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

    public function toArray($request)
    {
        $defendant_ids = json_decode($this->defendants);
        $defendants = Defendant::whereIn('id', $defendant_ids)->get();
        return [
            'id' => $this->id,
            'case_id' => $this->case_id,
            'appeal_id' => $this->appeal_id,
            'government_agency' => new GovernmentAgencyResource($this->governmentAgency),
            'defendants' => DefendantResource::collection($defendants),
            'action_to_violator' => new ActionToViolatorResource($this->actionToViolator),
            'document' => new AppealAnswerShowResource($this->document),
            'date' => $this->date,
            'note' => $this->note,
            'violated_right' => $this->violatedRight
        ];
    }
}
