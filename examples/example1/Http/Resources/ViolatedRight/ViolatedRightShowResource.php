<?php
namespace App\Http\Resources\ViolatedRight;

use App\Http\Resources\EmployeeAction\EmployeeActionShowResource;
use App\Http\Resources\References\DefendantResource;
use App\Http\Resources\References\GovernmentAgencyResource;
use App\Http\Resources\References\OrganizationPositionResource;
use App\Http\Resources\References\ViolationsClassifierResource;
use App\Models\Defendant;
use Illuminate\Http\Resources\Json\JsonResource;

class ViolatedRightShowResource extends JsonResource
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
            'violations_classifier' => new ViolationsClassifierResource($this->violationsClassifier),
            'government_agency' => new GovernmentAgencyResource($this->governmentAgency),
            'defendants' => DefendantResource::collection($defendants),
            'note' => $this->note,
            'employee_action' => new EmployeeActionShowResource($this->employeeAction)
        ];
    }
}
